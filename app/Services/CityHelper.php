<?php

namespace App\Services;

use App\Models\Place;
use Illuminate\Support\Facades\DB;

class CityHelper
{
    public static function all()
    {
        $cities = Place::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$.city')) AS city")
            ->whereNotNull(DB::raw("JSON_EXTRACT(meta, '$.city')"))
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->filter()
            ->values();

        // Fallback
        if ($cities->isEmpty()) {
            return collect(['Williamsburg, VA']);
        }

        return $cities;
    }
}
