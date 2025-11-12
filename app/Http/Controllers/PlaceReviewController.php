<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceReviewController extends Controller
{
    public function index(Place $place, Request $request)
    {
        // For now just return empty paginator so UI loads
        return response()->json([
            'data' => [],
            'total' => 0,
            'from' => 0,
            'to' => 0,
            'current_page' => 1,
            'last_page' => 1,
        ]);
    }
}
