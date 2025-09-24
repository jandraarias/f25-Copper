<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use App\Models\PreferenceProfile;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Store a new preference under a profile.
     */
    public function store(Request $request, PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        $request->validate([
            'key'   => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $preferenceProfile->preferences()->create($request->only('key', 'value'));

        return redirect()
            ->route('traveler.preference-profiles.show', $preferenceProfile)
            ->with('success', 'Preference added successfully!');
    }

    /**
     * Show the form for editing a preference.
     */
    public function edit(Preference $preference)
    {
        $this->authorize('update', $preference);

        return view('traveler.preferences.preferences.edit', compact('preference'));
    }

    /**
     * Update a preference in storage.
     */
    public function update(Request $request, Preference $preference)
    {
        $this->authorize('update', $preference);

        $request->validate([
            'key'   => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $preference->update($request->only('key', 'value'));

        return redirect()
            ->route('traveler.preference-profiles.show', $preference->preference_profile_id)
            ->with('success', 'Preference updated successfully!');
    }

    /**
     * Remove a preference from storage.
     */
    public function destroy(Preference $preference)
    {
        $this->authorize('delete', $preference);

        $profileId = $preference->preference_profile_id;
        $preference->delete();

        return redirect()
            ->route('traveler.preference-profiles.show', $profileId)
            ->with('success', 'Preference deleted successfully!');
    }
}
