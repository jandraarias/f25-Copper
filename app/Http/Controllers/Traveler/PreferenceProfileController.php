<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\PreferenceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceProfileController extends Controller
{
    /**
     * Display a listing of the traveler’s preference profiles.
     */
    public function index()
    {
        $traveler = Auth::user()->traveler;
        $profiles = $traveler->preferenceProfiles()->latest()->paginate(10);

        return view('traveler.preferences.profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new preference profile.
     */
    public function create()
    {
        return view('traveler.preferences.profiles.create');
    }

    /**
     * Store a newly created preference profile in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $traveler = Auth::user()->traveler;

        $traveler->preferenceProfiles()->create([
            'name' => $request->input('name'),
        ]);

        return redirect()
            ->route('traveler.preference-profiles.index')
            ->with('success', 'Preference profile created successfully!');
    }

    /**
     * Display the specified preference profile.
     */
    public function show(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('view', $preferenceProfile);

        return view('traveler.preferences.profiles.show', compact('preferenceProfile'));
    }

    /**
     * Show the form for editing the specified preference profile.
     */
    public function edit(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        return view('traveler.preferences.profiles.edit', compact('preferenceProfile'));
    }

    /**
     * Update the specified preference profile in storage.
     */
    public function update(Request $request, PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $preferenceProfile->update([
            'name' => $request->input('name'),
        ]);

        return redirect()
            ->route('traveler.preference-profiles.index')
            ->with('success', 'Preference profile updated successfully!');
    }

    /**
     * Remove the specified preference profile from storage.
     */
    public function destroy(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('delete', $preferenceProfile);

        $preferenceProfile->delete();

        return redirect()
            ->route('traveler.preference-profiles.index')
            ->with('success', 'Preference profile deleted successfully!');
    }
}
