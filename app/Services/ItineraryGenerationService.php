<?php

namespace App\Services;

use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItineraryGenerationService
{
    /**
     * Generate itinerary items using DB-backed Places.
     *
     * @param  Itinerary  $itinerary
     * @param  array{force?:bool}  $options
     * @return array{ok:bool, error?:string, created_count?:int, skipped?:string}
     */
    public function generateForItinerary(Itinerary $itinerary, array $options = []): array
    {
        // ------------------------------------------------------------------
        // 0) Guard clauses
        // ------------------------------------------------------------------
        if (!$itinerary->start_date || !$itinerary->end_date) {
            return ['ok' => false, 'error' => 'Itinerary dates are required'];
        }

        if (!$itinerary->preference_profile_id) {
            return ['ok' => false, 'error' => 'A preference profile must be associated'];
        }

        // City: we’ll use itinerary->location first, fall back to ->destination
        $city = trim((string)($itinerary->location ?? $itinerary->destination ?? ''));
        if ($city === '') {
            return ['ok' => false, 'error' => 'Itinerary location (city) is required'];
        }

        // Prevent duplicate generations unless forced
        $force = (bool)($options['force'] ?? false);
        if (!$force && $itinerary->items()->exists()) {
            return [
                'ok' => true,
                'created_count' => 0,
                'skipped' => 'Items already exist. Pass ["force" => true] to regenerate.'
            ];
        }

        // ------------------------------------------------------------------
        // 1) Pull preferences
        // ------------------------------------------------------------------
        $profile = $itinerary->preferenceProfile()->with('preferences')->first();
        $prefs = $this->flattenPreferences($profile?->preferences ?? collect());
        // $prefs example: ['budget' => 'low', 'dietary' => 'vegetarian', 'interests' => ['history','museums']]

        // ------------------------------------------------------------------
        // 2) Query candidate places for city
        //     We filter by address containing the city string. Your data import
        //     stores address inside meta JSON.
        // ------------------------------------------------------------------
        $candidates = Place::query()
            ->where('meta->address', 'like', '%' . $city . '%')
            ->get();

        if ($candidates->isEmpty()) {
            return ['ok' => false, 'error' => "No places found for {$city}"];
        }

        // Partition into activities and food based on Place::type accessor
        [$activities, $foods] = $this->partitionByType($candidates);

        if ($activities->isEmpty() && $foods->isEmpty()) {
            return ['ok' => false, 'error' => "No usable places (food/activities) found for {$city}"];
        }

        // ------------------------------------------------------------------
        // 3) Score and sort by preference fit
        // ------------------------------------------------------------------
        $activities = $this->scoreAndSort($activities, $prefs);
        $foods      = $this->scoreAndSort($foods, $prefs);

        // ------------------------------------------------------------------
        // 4) Build days and create 2 activities + 2 food per day
        // ------------------------------------------------------------------
        $days = $this->eachDate(Carbon::parse($itinerary->start_date), Carbon::parse($itinerary->end_date));

        // If forcing, clear existing items first
        if ($force) {
            $itinerary->items()->delete();
        }

        $usedNames = ['activity' => [], 'food' => []];
        $created = 0;

        foreach ($days as $day) {
            // Pick unique places per category
            $pickedActivities = $this->pickUnique($activities, $usedNames['activity'], 2);
            $usedNames['activity'] = array_merge($usedNames['activity'], $pickedActivities->pluck('name')->all());

            $pickedFoods = $this->pickUnique($foods, $usedNames['food'], 2);
            $usedNames['food'] = array_merge($usedNames['food'], $pickedFoods->pluck('name')->all());

            // Time slots: simple, tweak as needed
            $slots = [
                ['type' => 'activity', 'time' => '10:00:00', 'dur_min' => 120],
                ['type' => 'food',     'time' => '12:30:00', 'dur_min' => 60],
                ['type' => 'activity', 'time' => '15:00:00', 'dur_min' => 120],
                ['type' => 'food',     'time' => '18:30:00', 'dur_min' => 90],
            ];

            // Interleave A, F, A, F
            $pairs = collect()
                ->push($pickedActivities->get(0))
                ->push($pickedFoods->get(0))
                ->push($pickedActivities->get(1))
                ->push($pickedFoods->get(1));

            foreach ($slots as $i => $slot) {
                /** @var Place|null $place */
                $place = $pairs->get($i);
                if (!$place) {
                    continue;
                }

                $start = Carbon::parse($day->toDateString() . ' ' . $slot['time']);
                $end   = (clone $start)->addMinutes($slot['dur_min']);

                ItineraryItem::create([
                    'itinerary_id' => $itinerary->id,
                    'place_id'     => $place->id,             // requires nullable FK to places
                    'type'         => $slot['type'],          // 'activity' | 'food'
                    'title'        => $place->name,
                    'location'     => $place->address,        // from Place accessor (meta['address'])
                    'start_time'   => $start,
                    'end_time'     => $end,
                    'details'      => $this->buildDetails($place, $prefs),
                ]);

                $created++;
            }
        }

        return ['ok' => true, 'created_count' => $created];
    }

    // ----------------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------------

    /**
     * Turn Preference rows into a simple associative array.
     * Handles values that are comma-separated by normalizing to arrays where needed.
     */
    private function flattenPreferences(Collection $collection): array
    {
        $out = [];

        foreach ($collection as $pref) {
            $key = Str::slug((string)$pref->key, '_');   // e.g., "Food Type" -> "food_type"
            $val = $pref->value;

            // Normalize comma lists into arrays for keys that look like sets
            if (is_string($val) && preg_match('/,/', $val)) {
                $val = collect(preg_split('/\s*,\s*/', $val))
                    ->filter()
                    ->map(fn ($v) => strtolower(trim($v)))
                    ->values()
                    ->all();
            }

            $out[$key] = $val;
        }

        // Optional alias: interests might be stored as json in profile too — merge if present
        if (isset($collection[0]?->profile?->interests) && is_array($collection[0]->profile->interests)) {
            $out['interests'] = array_values(array_unique(array_merge(
                $out['interests'] ?? [],
                array_map(fn ($v) => strtolower(trim((string)$v)), $collection[0]->profile->interests)
            )));
        }

        return $out;
    }

    /**
     * Partition places into [activities, foods] using Place::type accessor.
     *
     * @return array{0:Collection,1:Collection}
     */
    private function partitionByType(Collection $places): array
    {
        $foods = $places->filter(fn (Place $p) => $p->type === 'food')->values();
        $activities = $places->filter(fn (Place $p) => $p->type === 'activity')->values();
        return [$activities, $foods];
    }

    /**
     * Score and sort a collection of Place models by preference fit.
     */
    private function scoreAndSort(Collection $places, array $prefs): Collection
    {
        $scored = $places->map(function (Place $p) use ($prefs) {
            $score = 0.0;

            // Tag overlap (interests)
            $prefTags = (array)($prefs['interests'] ?? $prefs['tags'] ?? []);
            if (!empty($prefTags)) {
                $overlap = count(array_intersect($p->tags, $prefTags));
                $score += 3 * $overlap;
            }

            // Budget alignment (map low/medium/high -> 1/2/3; place price_level may be null)
            if (isset($prefs['budget'])) {
                $map = ['low' => 1, 'medium' => 2, 'high' => 3];
                $target = $map[strtolower((string)$prefs['budget'])] ?? null;
                $placeLevel = $p->price_level ?? null;
                if ($target && $placeLevel) {
                    $score -= abs($placeLevel - $target);
                }
            }

            // Dietary constraints (only applied to food)
            if (($prefs['dietary'] ?? null) && $p->type === 'food') {
                $diet = strtolower((string)$prefs['dietary']);
                // If the diet tag is missing, penalize. If present, reward.
                $score += in_array($diet, $p->tags, true) ? 2 : -3;
            }

            // Rating preference
            $score += (float)($p->rating ?? 0) * 0.5;

            // Slight boost to variety by random tiebreaker
            $score += mt_rand(0, 10) / 1000;

            return [$p, $score];
        });

        return $scored
            ->sortByDesc(fn ($pair) => $pair[1])
            ->map(fn ($pair) => $pair[0])
            ->values();
    }

    /**
     * Inclusive date range.
     *
     * @return array<int,Carbon>
     */
    private function eachDate(Carbon $start, Carbon $end): array
    {
        $days = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $days[] = $d->copy();
        }
        return $days;
    }

    /**
     * Pick N places not already used by name.
     */
    private function pickUnique(Collection $sorted, array $usedNames, int $count): Collection
    {
        $picked = collect();
        foreach ($sorted as $place) {
            if (!in_array($place->name, $usedNames, true)) {
                $picked->push($place);
                if ($picked->count() >= $count) break;
            }
        }
        return $picked;
    }

    /**
     * Build human-friendly details for an itinerary item.
     */
    private function buildDetails(Place $place, array $prefs): string
    {
        $bits = [];

        if ($place->address) {
            $bits[] = $place->address;
        }

        if ($place->price_level) {
            $bits[] = 'Price level ' . $place->price_level;
        }

        if ($place->rating) {
            $bits[] = 'Rating ' . number_format($place->rating, 1);
        }

        // Add link if present in meta
        $link = $place->meta['link'] ?? $place->meta['url'] ?? null;
        if ($link) {
            $bits[] = $link;
        }

        // Include matched tags if we have interests
        $prefTags = (array)($prefs['interests'] ?? $prefs['tags'] ?? []);
        if (!empty($prefTags)) {
            $matched = array_intersect($place->tags, $prefTags);
            if (!empty($matched)) {
                $bits[] = 'Matches: ' . implode(', ', $matched);
            }
        }

        return implode(' • ', $bits);
    }
}
