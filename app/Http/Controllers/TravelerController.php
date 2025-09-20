<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TravelerController extends Controller
{
    public function index()
    {
        $traveler = Auth::user()
            ->traveler()
            ->with(['itineraries.items', 'preferenceProfiles.preferences'])
            ->first();

        return view('traveler.dashboard', compact('traveler'));
    }
}
