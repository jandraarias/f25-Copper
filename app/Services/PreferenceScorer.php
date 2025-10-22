<?php

namespace App\Services;

use App\Models\Place;

class PreferenceScorer
{
    public function score(Place $place, array $preferences): float
    {
        $score = 0;

        // Budget alignment
        if (isset($preferences['budget'])) {
            $map = ['low' => 1, 'medium' => 2, 'high' => 3];
            $prefLevel = $map[$preferences['budget']] ?? null;
            $placeLevel = $place->price_level ?? 2;
            if ($prefLevel !== null) {
                $score -= abs($placeLevel - $prefLevel);
            }
        }

        // Tags match (interests)
        if (isset($preferences['interests'])) {
            $interests = is_array($preferences['interests'])
                ? $preferences['interests']
                : explode(',', $preferences['interests']);
            $overlap = count(array_intersect($place->tags, $interests));
            $score += 3 * $overlap;
        }

        // Dietary constraints (skip food places that conflict)
        if (($preferences['dietary'] ?? null) && $place->type === 'food') {
            if (!in_array($preferences['dietary'], $place->tags)) {
                $score -= 5; // hard penalty
            }
        }

        // Prefer highly rated places
        $score += ($place->rating ?? 0) * 0.5;

        return $score;
    }
}
