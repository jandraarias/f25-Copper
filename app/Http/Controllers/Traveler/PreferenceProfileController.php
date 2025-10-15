<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use App\Models\PreferenceProfile;
use App\Models\PreferenceOption;
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
        [$mainOptions, $subMap] = $this->loadOptions();

        return view('traveler.preferences.profiles.form', [
            'mainOptions' => $mainOptions,
            'subMap'      => $subMap,
            'preferences' => collect(), // empty for new
        ]);
    }

    /**
     * Store a newly created preference profile and its preferences.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $traveler = Auth::user()->traveler;

        $profile = $traveler->preferenceProfiles()->create([
            'name' => $request->input('name'),
        ]);

        $this->syncPreferences($profile, $request);

        return redirect()
            ->route('traveler.preference-profiles.show', $profile)
            ->with('success', 'Preference profile created successfully!');
    }

    /**
     * Display the specified preference profile.
     */
    public function show(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('view', $preferenceProfile);

        // For the “view” mode — no editing, just show preferences nicely
        $preferences = $preferenceProfile->preferences()->get();

        // Load lookup map for displaying activity names
        $subs  = PreferenceOption::where('type', 'sub')->get(['id', 'name', 'parent_id']);
        $mains = PreferenceOption::where('type', 'main')->pluck('name', 'id');

        $activityLookup = [];
        foreach ($subs as $sub) {
            $activityLookup[$sub->name] = [
                'main' => $mains[$sub->parent_id] ?? 'Unknown',
                'sub'  => $sub->name,
            ];
        }

        return view('traveler.preferences.profiles.show', [
            'preferenceProfile' => $preferenceProfile,
            'preferences'       => $preferences,
            'activityLookup'    => $activityLookup,
        ]);
    }

    /**
     * Show the edit form for a profile.
     */
    public function edit(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        [$mainOptions, $subMap] = $this->loadOptions();
        $preferences = $preferenceProfile->preferences()->get();

        return view('traveler.preferences.profiles.form', [
            'preferenceProfile' => $preferenceProfile,
            'mainOptions'       => $mainOptions,
            'subMap'            => $subMap,
            'preferences'       => $preferences,
        ]);
    }

    /**
     * Update an existing profile and its preferences.
     */
    public function update(Request $request, PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $preferenceProfile->update(['name' => $request->input('name')]);

        $this->syncPreferences($preferenceProfile, $request);

        return redirect()
            ->route('traveler.preference-profiles.show', $preferenceProfile)
            ->with('success', 'Preference profile updated successfully!');
    }

    /**
     * Remove the specified profile.
     */
    public function destroy(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('delete', $preferenceProfile);

        $preferenceProfile->delete();

        return redirect()
            ->route('traveler.preference-profiles.index')
            ->with('success', 'Preference profile deleted successfully!');
    }

    /**
     * Sync preferences from the unified tabbed form.
     */
    protected function syncPreferences(PreferenceProfile $profile, Request $request)
    {
        // Delete old preferences before syncing new ones
        $profile->preferences()->delete();

        // --- Activities ---
        if ($request->filled('activities')) {
            $subs = PreferenceOption::whereIn('id', $request->activities)->get();
            foreach ($subs as $sub) {
                $profile->preferences()->create([
                    'key'   => 'activity',
                    'value' => $sub->name,
                ]);
            }
        }

        // --- Budget ---
        foreach (['budget_min', 'budget_max'] as $key) {
            if ($request->filled($key)) {
                $profile->preferences()->create([
                    'key'   => $key,
                    'value' => $request->input($key),
                ]);
            }
        }

        // --- Dietary ---
        if ($request->filled('dietary')) {
            foreach ($request->dietary as $diet) {
                $profile->preferences()->create([
                    'key'   => 'dietary',
                    'value' => $diet,
                ]);
            }
        }

        // --- Accommodation ---
        if ($request->filled('accommodation')) {
            foreach ($request->accommodation as $accom) {
                $profile->preferences()->create([
                    'key'   => 'accommodation',
                    'value' => $accom,
                ]);
            }
        }
    }

    /**
     * Load reusable main/sub interest options.
     */
    protected function loadOptions(): array
    {
        $mainOptions = PreferenceOption::where('type', 'main')
            ->orderBy('name')
            ->get(['id', 'name']);

        $subMap = PreferenceOption::where('type', 'sub')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id'])
            ->groupBy('parent_id')
            ->map(fn($items) => $items->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->values())
            ->toArray();

        return [$mainOptions, $subMap];
    }
}
