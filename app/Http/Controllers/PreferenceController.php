<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preference;
use App\Models\Preferenceprofile;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->traveler->preferenceprofile ?? null;

        // Get all preferences to show in form
        $preferences = Preference::all();

        // Get selected preference ids if profile exists
        $selectedPreferences = $profile ? $profile->preferences->pluck('id')->toArray() : [];

        return view('preferences.edit', compact('preferences', 'selectedPreferences'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'preferences' => 'array',
            'preferences.*' => 'exists:preferences,id',
        ]);

        $user = Auth::user();
        // Ensure traveler exists
        if (!$user->traveler) {
            $user->traveler()->create();
            $user->refresh();
        }
        $profile = $user->traveler->preferenceprofile ?? null;

        if (!$profile) {
            // Create preference profile if not exists
            $profile = Preferenceprofile::create(['traveler_id' => $user->traveler->id]);
        }

        // Sync selected preferences
        $profile->preferences()->sync($request->preferences ?? []);

        return redirect()->route('preferences.edit')->with('success', 'Preferences updated!');
    }
}
