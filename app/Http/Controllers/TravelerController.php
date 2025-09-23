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

        if (! $traveler) {
            return redirect()
                ->route('profile.edit')
                ->with('warning', 'Please complete your traveler profile before using the dashboard.');
        }

        return view('traveler.dashboard', compact('traveler'));
    }
}
