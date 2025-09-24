<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\PreferenceProfile;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PreferenceController extends Controller
{
    public function store(Request $request, PreferenceProfile $preferenceProfile): RedirectResponse
    {
        $this->authorize('update', $preferenceProfile);

        $data = $request->validate([
            'key'   => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $preferenceProfile->preferences()->create($data);

        return back()->with('success', 'Preference added.');
    }

    public function edit(Preference $preference): View
    {
        $this->authorize('update', $preference->profile);

        return view('traveler.preferences.preferences.edit', compact('preference'));
    }

    public function update(Request $request, Preference $preference): RedirectResponse
    {
        $this->authorize('update', $preference->profile);

        $data = $request->validate([
            'key'   => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $preference->update($data);

        return redirect()->route('traveler.preference-profiles.show', $preference->preference_profile_id)
            ->with('success', 'Preference updated.');
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        $this->authorize('delete', $preference->profile);

        $profileId = $preference->preference_profile_id;
        $preference->delete();

        return redirect()->route('traveler.preference-profiles.show', $profileId)
            ->with('success', 'Preference deleted.');
    }
}

