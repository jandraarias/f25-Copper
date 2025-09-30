<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Itinerary;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $traveler = $user->traveler;

        $itineraryCount = $traveler ? Itinerary::where('traveler_id', $traveler->id)->count() : 0;

        return view('traveler.dashboard', [
            'itineraryCount' => $itineraryCount,
        ]);
    }
}
