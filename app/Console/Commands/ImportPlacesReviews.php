<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Place;
use App\Models\Review;

/**
 * Artisan command: imports Places & Reviews from two CSVs.
 *
 * Key behaviors:
 * - Optional --truncate to wipe tables before import.
 * - Optional --dry-run to simulate without DB writes.
 * - Places are upserted (by name+lat+lon, else by name).
 * - Reviews link to places primarily by place_id, falling back to name.
 * - Unrated reviews are skipped (rating required).
 */

class ImportPlacesReviews extends Command
{
    protected $signature = 'data:import
        {--places= : Path to places CSV (absolute or relative to storage/app)}
        {--reviews= : Path to reviews CSV (absolute or relative to storage/app)}
        {--truncate : Truncate places & reviews before import}
        {--dry-run  : Parse and validate, but do not write to DB}';

    protected $description = 'Import places & reviews from CSV (strictly keyed by place_id).';

    public function handle(): int
    {
        // Increases memory limit for large CSVs
        ini_set('memory_limit', '1G');

        $placesPath  = $this->resolvePath($this->option('places')  ?: 'seed/places.csv');
        $reviewsPath = $this->resolvePath($this->option('reviews') ?: 'seed/reviews.csv');

        $this->info("Places CSV  : {$placesPath}");
        $this->info("Reviews CSV : {$reviewsPath}");

        // Optionally wipe both tables before importing.
        if ($this->option('truncate')) {
            if (! $this->confirm('This will DELETE all places and reviews. Continue?', false)) {
                $this->warn('Aborted.');
                return self::FAILURE;
            }
            Review::truncate();
            Place::truncate();
            $this->comment('Truncated places and reviews.');
        }

        // Read both CSVs fully into arrays 
        $places  = $this->readCsv($placesPath);
        $reviews = $this->readCsv($reviewsPath);

        // If both are empty/missing, exit cleanly.
        if (!count($places) && !count($reviews)) {
            $this->warn('No rows found in either CSV. Nothing to do.');
            return self::SUCCESS;
        }
        
        // Whether to simulate only (no DB writes)
        $dry = (bool) $this->option('dry-run');

        // Maps for linking reviews → places
        $extMap  = [];   // place_id (string) -> places.id
        $nameMap = [];   // normalized name -> places.id (fallback only)

        $created = 0; $updated = 0;
        foreach ($places as $r) {
            // Extract key fields from a place row
            $placeId = $this->str($r['place_id'] ?? null);
            $name    = $this->str($r['name'] ?? $r['place_name'] ?? null);
            if (!$name) continue; // must have a name

            $lat = $this->num($r['lat'] ?? $r['latitude'] ?? null);
            $lon = $this->num($r['lon'] ?? $r['lng'] ?? $r['longitude'] ?? null);

            if (!$dry) {
                // Try to find an existing place by (name, lat, lon) if coordinates exist; else by name only.
                $query = $lat !== null && $lon !== null
                    ? ['name'=>$name,'lat'=>$lat,'lon'=>$lon]
                    : ['name'=>$name];
                
                // Prepare persisted columns. `meta` stores raw CSV row for traceability.
                $payload = [
                    'name'     => $name,
                    'lat'      => $lat,
                    'lon'      => $lon,
                    'category' => $this->flattenCategory($r['main_category'] ?? $r['category'] ?? $r['categories'] ?? null),
                    'rating'   => $this->num($r['rating'] ?? null),
                    'source'   => 'gmaps_scrape_local',
                    'meta'     => $r, // array; Review::$casts will JSON it
                ];

                // Upsert behavior
                $place = Place::where($query)->first();
                if ($place) { $place->fill($payload)->save(); $updated++; }
                else        { $place = Place::create($payload); $created++; }

                // mapping for quick review linkage
                if ($placeId) $extMap[$placeId] = $place->id;
                if ($name)    $nameMap[$this->norm($name)] = $place->id;
            } else {
                // In dry-run, just count and build fake positive IDs so we can still test flows
                $created++;
                if ($placeId) $extMap[$placeId] = -1;
                if ($name)    $nameMap[$this->norm($name)] = -1;
            }
        }
        $this->info("Places → created {$created}, updated {$updated}");
        $this->line('Place map (place_id) count: '.count($extMap));

        // Quick visibility: how many distinct review place_ids actually match?
        $reviewIds = [];
        foreach ($reviews as $r) {
            $pid = $this->str($r['place_id'] ?? null);
            if ($pid) $reviewIds[$pid] = true;
        }
        $distinctReviewIds = array_keys($reviewIds);
        $hits = 0;
        foreach ($distinctReviewIds as $pid) if (isset($extMap[$pid])) $hits++;
        $this->line('Distinct review place_id: '.count($distinctReviewIds)."; match in places: {$hits}");

        // Now import reviews
        $inserted = 0; $skipped = 0; $noId = 0; $noName = 0;
        foreach ($reviews as $r) {
            // Preferred linkage: by external place_id
            $pid = $this->str($r['place_id'] ?? null);
            $placeId = null;

            $dry = (bool) $this->option('dry-run');

            if ($pid && isset($extMap[$pid])) {
                $placeId = $dry ? 1 : $extMap[$pid]; // positive dummy id in dry-run
            } else {
                // Fallback: link by normalized name.
                $nm = $this->norm($this->str($r['place_name'] ?? $r['name'] ?? null));
                if ($nm && isset($nameMap[$nm]) && $nameMap[$nm] > 0) {
                    $placeId = $nameMap[$nm];
                }
            }

            // If still not found, optionally create a stub Place from the review (only if not dry-run).
            if (!$placeId) {
                // Try to create a place on the fly (only if you’re OK with this behavior)
                $placeName = $this->str($r['place_name'] ?? $r['name'] ?? null);
                if ($placeName && !$dry) {
                    $new = Place::create([
                        'name'   => $placeName,
                        'source' => 'gmaps_scrape_local',
                        'meta'   => ['created_from_review' => true],
                    ]);
                    $placeId = $new->id;

                    // Optionally remember it so later reviews to the same name resolve fast
                    $nameKey = $this->norm($placeName);
                    if ($nameKey) $nameMap[$nameKey] = $placeId;
                }
                // If still missing, count which kind failed (missing pid vs missing name).
                if (!$placeId) { $pid ? $noId++ : $noName++; $skipped++; continue; }
            }
            
            // Extract review fields.
            $author = $this->str($r['author'] ?? $r['author_name'] ?? $r['username'] ?? $r['name'] ?? 'Anonymous');
            $text   = $this->str($r['review_text'] ?? $r['text'] ?? $r['comment'] ?? $r['content'] ?? '');
            $rating = $this->intOrNull($r['rating'] ?? $r['stars'] ?? $r['score'] ?? null);

            // skips unrated reviews
            if ($rating === null) { $skipped++; continue; }

            // Parse published date from various possible columns
            $published = $this->parseDate($this->str($r['published_at_date'] ?? $r['published_at'] ?? $r['time'] ?? $r['timestamp'] ?? $r['date'] ?? null));
            $fetchedAt = now();

            if (!$dry) {
                // basic de-dupe
                // Consider duplicates same if same place_id + author + text + published_at_date (NULLs handled explicitly).
                $q = Review::where('place_id', $placeId);
                $author !== '' ? $q->where('author', $author) : $q->whereNull('author');
                $text   !== '' ? $q->where('text', $text)     : $q->whereNull('text');
                $published ? $q->where('published_at_date', $published) : $q->whereNull('published_at_date');

                if ($q->exists()) { $skipped++; continue; }

                // Create the review.
                Review::create([
                    'place_id'          => $placeId,
                    'source'            => 'gmaps_scrape_local',
                    'rating'            => $rating,
                    'text'              => $text,
                    'author'            => $author,
                    'published_at_date' => $published,
                    'fetched_at'        => $fetchedAt,
                    'meta'              => $r,
                ]);
            }

            $inserted++;
        }

        // Summary log (with breakdown on why reviews were skipped)
        $this->info("Reviews → inserted {$inserted}, skipped {$skipped} (no ext match: {$noId}, no name match: {$noName})");
        $this->info('Import complete');
        return self::SUCCESS;
    }

    /* ---------------- helpers ---------------- */

    /**
     * Convert a path option into a real path.
     * - If absolute file exists → return as-is
     * - Else resolve relative to storage/app
     */
    private function resolvePath(string $input): string
    {
        return is_file($input) ? $input : Storage::disk('local')->path($input);
    }

    /**
     * Read a small/medium CSV fully into memory.
     * - First row = header (lowercased & trimmed)
     */
    private function readCsv(string $absPath): array
    {
        if (!is_file($absPath)) { $this->warn("Missing file: {$absPath}"); return []; }
        if (($fp = fopen($absPath, 'r')) === false) { $this->error("Cannot open: {$absPath}"); return []; }

        $rows = []; $header = null;
        while (($row = fgetcsv($fp)) !== false) {
            if ($header === null) { 
                // Use the first line as the header
                $header = array_map(fn($h)=>Str::of($h)->lower()->trim()->toString(), $row); 
                continue; 
            }
            
            // Skip fully empty rows.
            if (!array_filter($row, fn($v)=>$v!==null && $v!=='')) continue;

            $assoc=[];
            foreach ($row as $i=>$val) {
                $key = $header[$i] ?? ("col_{$i}");
                $assoc[$key] = is_string($val) ? trim($val) : $val;
            }
            $rows[]=$assoc;
        }
        fclose($fp);
        $this->comment("Read ".count($rows)." rows from ".basename($absPath));
        return $rows;
    }

    /**
     * Stream a CSV generator (alternative for very large files).
     * Not used in main flow right now, but available to avoid loading the entire file into memory.
     */
    private function streamCsv(string $absPath): \Generator
    {
        if (!is_file($absPath)) {
            $this->warn("Missing file: {$absPath}");
            if (false) yield []; // generator signature
            return;
        }

        $fh = fopen($absPath, 'r');
        if (!$fh) {
            $this->error("Unable to open: {$absPath}");
            if (false) yield [];
            return;
        }

        // Detect header
        $header = null;
        $lineno = 0;
        while (($row = fgetcsv($fh)) !== false) {
            $lineno++;
            if ($header === null) {
                // lower-case + trim headers
                $header = array_map(static fn($h) => strtolower(trim((string)$h)), $row);
                continue;
            }
            // skip fully-empty lines
            if (!array_filter($row, fn($v) => $v !== null && $v !== '')) continue;

            // build assoc
            $assoc = [];
            foreach ($row as $i => $val) {
                $key = $header[$i] ?? ("col_{$i}");
                $assoc[$key] = is_string($val) ? trim($val) : $val;
            }
            yield $assoc;
        }
        fclose($fh);
    }

    // Normalize any value to a trimmed string
    private function str($v): ?string
    {
        if ($v === null) return null;
        if (is_array($v) || is_object($v)) return trim(json_encode($v, JSON_UNESCAPED_UNICODE));
        return trim((string)$v);
    }

    // Parse a number (float) from common string formats like "4.6" or "1,234.5"
    private function num($v): ?float
    {
        if ($v === null || $v === '') return null;
        $s = str_replace([','], [''], (string)$v);
        return is_numeric($s) ? (float)$s : null;
    }

    // Int or null helper (used for ratings)
    private function intOrNull($v): ?int
    {
        if ($v === null || $v === '') return null;
        return is_numeric($v) ? (int)$v : null;
    }

    // Normalize a name for case-insensitive & spacing-insensitive comparisons
    private function norm(?string $name): ?string
    {
        return $name ? Str::of($name)->lower()->replace(['&',' and '], ' and ')->squish()->toString() : null;
    }

    private function flattenCategory(?string $raw): ?string
    {
        if (!$raw) return null;
        if (str_starts_with($raw, '[')) {
            $arr = json_decode($raw, true);
            if (is_array($arr)) return implode(',', $arr);
        }
        return $raw;
    }

    private function parseDate(?string $v): ?Carbon
    {
        if ($v === null || $v === '') return null;
        if (ctype_digit((string)$v)) return Carbon::createFromTimestamp((int)$v);
        try { return Carbon::parse($v, 'UTC'); } catch (\Throwable) { return null; }
    }
}