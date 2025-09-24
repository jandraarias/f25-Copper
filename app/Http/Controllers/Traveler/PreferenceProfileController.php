<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\PreferenceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PreferenceProfileController extends Controller
{
    public function index(): View
    {
        $traveler = Auth::user()->traveler;
        $profiles = PreferenceProfile::with('preferences')
            ->where('traveler_id', $traveler->id)
            ->paginate(10);

        return view('traveler.preferences.profiles.index', compact('profiles'));
    }

    public function create(): View
    {
        return view('traveler.preferences.profiles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['traveler_id'] = Auth::user()->traveler->id;

        PreferenceProfile::create($validated);

        return redirect()->route('traveler.preference-profiles.index')
            ->with('success', 'Profile created successfully.');
    }

    public function show(PreferenceProfile $preferenceProfile): View
    {
        $this->authorize('view', $preferenceProfile);

        $preferenceProfile->load('preferences');

        return view('traveler.preferences.profiles.show', compact('preferenceProfile'));
    }

    public function edit(PreferenceProfile $preferenceProfile): View
    {
        $this->authorize('update', $preferenceProfile);

        return view('traveler.preferences.profiles.edit', compact('preferenceProfile'));
    }

    public function update(Request $request, PreferenceProfile $preferenceProfile): RedirectResponse
    {
        $this->authorize('update', $preferenceProfile);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $preferenceProfile->update($validated);

        return redirect()->route('traveler.preference-profiles.index')
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy(PreferenceProfile $preferenceProfile): RedirectResponse
    {
        $this->authorize('delete', $preferenceProfile);

        $preferenceProfile->delete();

        return redirect()->route('traveler.preference-profiles.index')
            ->with('success', 'Profile deleted successfully.');
    }
}
