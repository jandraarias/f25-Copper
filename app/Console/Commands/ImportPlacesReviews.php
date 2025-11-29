<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Place;
use App\Models\Review;

/*
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

    //protected $description = 'Import places & reviews from CSV (strictly keyed by place_id).';
    protected $description = 'Scan folders of CSVs, pair place/review files, and import them efficiently.';

    public function handle(): int
    {
        // Increases memory limit for large CSVs
        ini_set('memory_limit', '1G');

        $placesPath  = base_path('resources/data/places');
        $reviewsPath = base_path('resources/data/reviews');

        // Whether to simulate only (no DB writes)
        $dry = (bool) $this->option('dry-run');

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

        // Get full paths of all .csv files in each folder
        $placeFiles  = $this->listCsv($placesPath);
        $reviewFiles = $this->listCsv($reviewsPath);
        
        // If both are empty/missing, exit cleanly.
        if (empty($placeFiles)) {
            $this->warn('No place records found. Aborting import.');
            return self::SUCCESS;
        }

        // Loop over each places CSV file
        foreach ($placeFiles as $placeFile) {
            // For the current places file find a matching reviews file 
            $match = $this->findMatchingReviewFile(basename($placeFile), $reviewFiles);

            // blank line for readability
            $this->line('');

            // Log which files are being processed
            $this->info("Importing: " . basename($placeFile));
            if ($match) {
                $this->info("   Matched reviews: " . basename($match));
            } else {
                $this->warn("   No matching reviews found for this places file.");
            }

            // If we have a matching reviews file, build the external-id allowlist
            $allowIds = [];
            if ($match) {
                $allowIds = $this->collectReviewPlaceIds($match);
            }

            // Choose your threshold for minimum reviews a place must have to be imported
            $minReviews = 15;

            // 1) Import places, build ext map & name map (for this file only)
            [$extMap, $nameMap, $created, $updated, $placeCount] = $this->importPlacesCsv($placeFile, $dry, $allowIds, $minReviews);
            $this->info("   Places → created {$created}, updated {$updated} (rows seen: {$placeCount})");

            // 2) Import reviews (only if match exists)
            if ($match) {
                [$inserted, $skipped, $noExt, $noName, $rowsSeen] = $this->importReviewsCsv($match, $extMap, $nameMap, $dry);
                $this->info("   Reviews → inserted {$inserted}, skipped {$skipped} (no ext match: {$noExt}, no name match: {$noName}; rows seen: {$rowsSeen})");
            }
        }
    $this->info("\nAll done.");
    return self::SUCCESS;
    }

    private function listCsv(string $dir): array
    {
        // Initialize an empty array that will hold the full paths of any .csv files that are found
        $out = [];
        foreach (scandir($dir) ?: [] as $f) {
            if ($f === '.' || $f === '..') continue;
            $p = $dir . DIRECTORY_SEPARATOR . $f;
            if (is_file($p) && preg_match('/\.csv$/i', $f)) $out[] = $p;
        }
        return $out;
    }

    // “Compare by first two underscore-separated tokens”, like your original
    private function compareFileNames(string $placeFile, string $reviewFile): bool
    {   
        // slipts the file name into an array and takes the first two elements (ex:['williamsburg','food'])
        $place = array_slice(explode('_', $placeFile), 0, 2);       // explode('_', $placeFile) splits a string into an array based on the delimiter '_' ("williamsburg_food_places.csv" -> ['williamsburg','food','places.csv'])
        $review = array_slice(explode('_', $reviewFile), 0, 2);     // array_slice(..., 0, 2) extracts a portion of an array (the first two elements in this case)

        // Returns true only if both arrays are identical in content and order
        return $place === $review;
    }

    private function findMatchingReviewFile(string $placeBase, array $reviewFiles): ?string
    {
        foreach ($reviewFiles as $full) {
            if ($this->compareFileNames($placeBase, basename($full))) return $full;
        }
        return null;
    }

    /* ==================== CSV streaming ==================== */

    // Memory-friendly streaming reader. Returns a Generator of assoc rows.
    private function streamCsv(string $path): \Generator
    {
        // if the path isn’t a real file, log a warning and return no generator values.
        if (!is_file($path)) { $this->warn("Missing file: {$path}"); return; }

        // Open the file for reading. If it fails, log an error and stop.
        $fh = fopen($path, 'r');
        if (!$fh) { $this->error("Cannot open: {$path}"); return; }

        $header = null;

        // fgetcsv reads one CSV line at a time and parses it into an array of fields.
        while (($row = fgetcsv($fh)) !== false) {
            if ($header === null) {
                $header = array_map(fn($h) => Str::of($h)->lower()->trim()->toString(), $row);
                continue;
            }

            // Skip empty lines
            if (!array_filter($row, fn($v) => $v !== null && $v !== '')) continue;

            $assoc = [];
            // If a row has more columns than the header (messy CSV), we name the extra ones col_0, col_1, etc.
            foreach ($row as $i => $val) {
                $key = $header[$i] ?? ("col_{$i}");
                $assoc[$key] = is_string($val) ? trim($val) : $val;
            }
            yield $assoc;
        }
        // closes file when done
        fclose($fh);
    }

    /* ==================== Import: Places ==================== */

    private function importPlacesCsv(string $csvPath, bool $dry, array $allowedExtIds = [], int $minReviews = 0): array
    {
        $extMap = [];   // place_id -> places.id
        $nameMap = [];  // normalized name -> places.id

        $created = 0; $updated = 0; $seen = 0;

        foreach ($this->streamCsv($csvPath) as $r) {
            $seen++;

            // external id names
            $ext   = $this->s($r['place_id'] ?? null);
            $name  = $this->s($r['name'] ?? $r['place_name'] ?? null);
            if (!$name) continue;

            // 1) Must be present in reviews (if a reviews set is provided)
            if (!empty($allowedExtIds)) {
                // If no external id in the row OR not present in the reviews set -> skip
                if (!$ext || !isset($allowedExtIds[$ext])) continue;
            }
            // 2) Must meet minimum reviews count (if set)
            $reviewsCount = $this->i($r['reviews'] ?? $r['num_reviews'] ?? null);
            if ($minReviews > 0 && ($reviewsCount === null || $reviewsCount < $minReviews)) {
                continue;
            }

            // Split coordinates column into latitude and longitude
            if (!empty($r['coordinates']) && is_string($r['coordinates'])) {
                $raw = trim($r['coordinates']);
                if ($raw !== '' && str_starts_with($raw, '{')) {
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        if (isset($decoded['latitude']))  $r['latitude']  = $decoded['latitude'];
                        if (isset($decoded['longitude'])) $r['longitude'] = $decoded['longitude'];
                    }
                }
            }

            //Assign latitude and longitude to variables so we can use it to match table entries
            $lat = $this->f($r['lat'] ?? $r['latitude'] ?? null);
            $lon = $this->f($r['lon'] ?? $r['lng'] ?? $r['longitude'] ?? null);

            // Map the CSV to the Database columns
            $payload = [ 
                'name'        => $name,
                'description' => $this->s($r['description'] ?? null),
                'num_reviews' => $this->i($r['reviews'] ?? $r['num_reviews'] ?? null),
                'address'     => $this->s($r['address'] ?? null),
                'phone'       => $this->s($r['phone'] ?? null),
                'lat'         => $lat,
                'lon'         => $lon,
                'category'    => $this->flattenCategory($r['main_category'] ?? $r['category'] ?? $r['categories'] ?? null),
                'rating'      => $this->f($r['rating'] ?? null),  // place avg rating if provided
                'tags'        => $this->normalizeTags($this->s($r['review_keywords'] ?? null)),
                'image'       => $this->s($r['featured_image'] ?? $r['image'] ?? null),
                'source'      => 'google_maps_scraper',
                'meta'        => $r,
            ];

            // Dry run, doesnt write to Database
            if ($dry) {
                $created++;
                if ($ext) $extMap[$ext] = -1;
                $nameMap[$this->norm($name)] = -1;
                continue;
            }

            // upsert by (name, lat, lon) if both coords present, if not fall back to matching by name.
            $place = null;
            if ($lat !== null && $lon !== null) {
                $place = Place::where(['name' => $name, 'lat' => $lat, 'lon' => $lon])->first();
            }
            if (!$place) $place = Place::where('name', $name)->first();

            // Update existing or create a new place; update counters.
            if ($place) { $place->fill($payload)->save(); $updated++; }
            else        { $place = Place::create($payload);            $created++; }

            if ($ext) $extMap[$ext] = $place->id;
            $nameMap[$this->norm($name)] = $place->id;
        }
        // Give the caller everything needed for review linking and reporting
        return [$extMap, $nameMap, $created, $updated, $seen];
    }

    /* ==================== Import: Reviews ==================== */

    private function importReviewsCsv(string $csvPath, array $extMap, array $nameMap, bool $dry): array
    {
        $inserted = 0; $skipped = 0; $noExt = 0; $noName = 0; $seen = 0;

        // Reads one review row at a time
        foreach ($this->streamCsv($csvPath) as $r) {
            $seen++;

            $pid = $this->s($r['place_id'] ?? null);
            $placeId = null;

            // First try to link via external place_id from the CSV
            if ($pid && isset($extMap[$pid])) {
                $placeId = $dry ? 1 : $extMap[$pid]; // positive dummy id for dry-run
            }

            // If external id didn’t resolve, try name fallback
            if (!$placeId) {
                $nm = $this->norm($this->s($r['place_name'] ?? $r['name'] ?? null));
                if ($nm && isset($nameMap[$nm]) && $nameMap[$nm] > 0) {
                    $placeId = $nameMap[$nm];
                }
            }

            // If still no place_id match, skip this review
            if (!$placeId) { $pid ? $noExt++ : $noName++; $skipped++; continue; }

            $author = $this->s($r['author'] ?? $r['author_name'] ?? $r['username'] ?? $r['name'] ?? 'Anonymous');
            $text   = $this->s($r['review_text'] ?? $r['text'] ?? $r['comment'] ?? $r['content'] ?? '');
            $rating = $this->i($r['rating'] ?? $r['stars'] ?? $r['score'] ?? null);

            // Convert to DATE ONLY (Y-m-d) to match published_at_date (DATE) column
            $published = $this->toDateOnly($r['published_at_date'] ?? $r['published_at'] ?? $r['time'] ?? $r['timestamp'] ?? $r['date'] ?? null);

            if ($dry) { $inserted++; continue; }

            // Checks if a review already exists for the same place_id and same (author, text, published_at_date). If found, skip
            $q = Review::where('place_id', $placeId);
            ($author !== '') ? $q->where('author', $author) : $q->whereNull('author');
            ($text   !== '') ? $q->where('text', $text)     : $q->whereNull('text');
            ($published)     ? $q->where('published_at_date', $published)
                                : $q->whereNull('published_at_date');

            if ($q->exists()) { $skipped++; continue; }

            // Writes the review, then increments inserted
            Review::create([
                'place_id'          => $placeId,
                'place_name'        => $this->s($r['place_name'] ?? null), // if you have this column
                'source'            => 'google_maps_scraper',
                'rating'            => $rating,        // null ok if column is nullable
                'text'              => $text,
                'author'            => $author,        // requires `author` column
                'published_at_date' => $published,     // DATE (Y-m-d)
                'fetched_at'        => now(),
                'owner_response'                 => $this->s($r['response_from_owner_text'] ?? null),
                'owner_response_published_date'  => $this->toDateOnly($r['response_from_owner_date'] ?? null),
                'review_photos'                  => $this->s($r['review_photos'] ?? null),
                'meta'              => $r,             // stored as JSON by Eloquent
            ]);

            $inserted++;
        }

        return [$inserted, $skipped, $noExt, $noName, $seen];
    }

    /* ---------------- helpers ---------------- */

    // Return a set (assoc array) of external place_ids present in the reviews CSV
    private function collectReviewPlaceIds(string $reviewsCsv): array
    {
        $ids = [];
        foreach ($this->streamCsv($reviewsCsv) as $r) {
            $pid = $this->s($r['place_id'] ?? null);
            if ($pid) $ids[$pid] = true;
        }
        return $ids;
    }

    private function normalizeTags(?string $raw): ?string
    {
        if ($raw === null) return null;

        // If it looks like a paragraph (no commas and very long), drop it.
        if (mb_strpos($raw, ',') === false && mb_strlen($raw) > 120) {
            return null;
        }

        // Split on commas, clean each token
        $parts = array_filter(array_map(function ($p) {
            $p = trim($p);
            $p = preg_replace('/[^[:alnum:]\s\-\&]/u', '', $p); // keep letters/numbers/space/&/-
            $p = preg_replace('/\s+/u', ' ', $p);
            return $p;
        }, explode(',', $raw)));

        // Cap to a reasonable number of tags (e.g., 5)
        $parts = array_slice($parts, 0, 5);
        if (empty($parts)) return null;

        $out = implode(', ', $parts);

        // Hard cap to 255 to fit VARCHAR(255)
        if (mb_strlen($out) > 255) {
            $out = mb_substr($out, 0, 255);
        }
        return $out;
    }

    private function s($v): ?string
    {
        if ($v === null) return null;
        if (is_array($v) || is_object($v)) return trim(json_encode($v, JSON_UNESCAPED_UNICODE));
        return trim((string)$v);
    }

    // “Safe float.” Converts messy numeric-looking CSV values into a float (or null).
    private function f($v): ?float
    {
        if ($v === null || $v === '') return null;
        $s = str_replace([','], [''], (string)$v);
        return is_numeric($s) ? (float)$s : null;
    }

    // Safe int
    private function i($v): ?int
    {
        if ($v === null || $v === '') return null;
        return is_numeric($v) ? (int)$v : null;
    }
    
    // Normalize name (for nameMap)
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

    // Return DATE string (Y-m-d) or null
    private function toDateOnly($raw): ?string
    {
        $s = $this->s($raw);
        if ($s === null || $s === '') return null;

        // Epoch?
        if (ctype_digit($s)) {
            try { return Carbon::createFromTimestamp((int)$s)->toDateString(); } catch (\Throwable) { return null; }
        }

        // Strings like "6 months ago" won’t parse reliably; ignore those gracefully
        try {
            $dt = Carbon::parse($s, 'UTC');
            return $dt->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}