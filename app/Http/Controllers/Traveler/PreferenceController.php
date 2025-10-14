<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use App\Models\PreferenceOption;
use App\Models\PreferenceProfile;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Display the form for creating a new preference under a profile.
     */
    public function create(PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        // Load main and sub options for the dropdowns
        $mainOptions = PreferenceOption::where('type', 'main')
            ->orderBy('name')
            ->get(['id', 'name']);

        // Build a parent_id => [{id, name}, ...] map for Alpine
        $subMap = PreferenceOption::where('type', 'sub')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id'])
            ->groupBy('parent_id')
            ->map(fn($items) => $items->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->values())
            ->toArray();

        // For legacy/manual preferences: fetch distinct keys (optional)
        $allKeys = Preference::select('key')->distinct()->pluck('key');

        return view('traveler.preferences.preferences.create', [
            'preferenceProfile' => $preferenceProfile,
            'allKeys'           => $allKeys,
            'mainOptions'       => $mainOptions,
            'subMap'            => $subMap,
        ]);
    }

    /**
     * Store a newly created preference.
     */
    public function store(Request $request, PreferenceProfile $preferenceProfile)
    {
        $this->authorize('update', $preferenceProfile);

        // Check if this is an Activity Preferences submission (multi-select)
        if ($request->has('main_interest_id') && $request->has('sub_interest_ids')) {
            $validated = $request->validate([
                'main_interest_id'   => ['required', 'exists:preference_options,id'],
                'sub_interest_ids'   => ['required', 'array', 'min:1'],
                'sub_interest_ids.*' => ['exists:preference_options,id'],
            ]);

            $main = PreferenceOption::findOrFail($validated['main_interest_id']);

            // Create a preference for each selected sub-interest
            foreach ($validated['sub_interest_ids'] as $subId) {
                $sub = PreferenceOption::find($subId);

                if ($sub) {
                    $preferenceProfile->preferences()->create([
                        'key'   => $main->name, // e.g. "Nature & Wildlife"
                        'value' => $sub->name,  // e.g. "National Parks"
                        'requirement' => ($sub->category === 'dietary') ? 'dietary' : 'general',
                    ]);
                }
            }

            return redirect()
                ->route('traveler.preference-profiles.show', $preferenceProfile)
                ->with('success', 'Activity preferences added successfully!');
        }

        // Fallback for other preferences (manual key/value entry)
        $validated = $request->validate([
            'key'   => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

         $preferenceProfile->preferences()->create([
        'key'         => $validated['key'],
        'value'       => $validated['value'],
        'requirement' => 'general',  // default to general if manual entry
        ]);

        return redirect()
            ->route('traveler.preference-profiles.show', $preferenceProfile)
            ->with('success', 'Preference added successfully!');
    }

    /**
     * Show the form for editing an existing preference.
     */
    public function edit(Preference $preference)
    {
        $this->authorize('update', $preference);

        // Load main/sub options
        $mainOptions = PreferenceOption::where('type', 'main')
            ->orderBy('name')
            ->get(['id', 'name']);

        $subMap = PreferenceOption::where('type', 'sub')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id'])
            ->groupBy('parent_id')
            ->map(fn($items) => $items->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->values())
            ->toArray();

        // Attempt to map the existing preference's value back to its option
        $currentSub = PreferenceOption::where('type', 'sub')
            ->where('name', $preference->value)
            ->first();
        $currentMainId = $currentSub?->parent_id;
        $currentSubId  = $currentSub?->id;

        return view('traveler.preferences.preferences.edit', [
            'preference'    => $preference,
            'mainOptions'   => $mainOptions,
            'subMap'        => $subMap,
            'currentMainId' => $currentMainId,
            'currentSubId'  => $currentSubId,
        ]);
    }

    /**
     * Update a preference in storage.
     */
    public function update(Request $request, Preference $preference)
    {
        $this->authorize('update', $preference);

        // Determine if this is an activity-type preference
        if ($request->has(['main_interest_id', 'sub_interest_id'])) {
            $validated = $request->validate([
                'main_interest_id' => ['required', 'exists:preference_options,id'],
                'sub_interest_id'  => ['required', 'exists:preference_options,id'],
            ]);

            $sub = PreferenceOption::findOrFail($validated['sub_interest_id']);

            $preference->update([
                'key'   => 'activity',
                'value' => $sub->name,
                'requirement' => ($sub->category === 'dietary') ? 'dietary' : 'general',
            ]);
        } else {
            $validated = $request->validate([
                'key'   => ['required', 'string', 'max:255'],
                'value' => ['required', 'string', 'max:255'],
            ]);

            $preference->update([
            'key'         => $validated['key'],
            'value'       => $validated['value'],
            'requirement' => 'general', // default for manual edits
            ]);
        }

        return redirect()
            ->route('traveler.preference-profiles.show', $preference->preference_profile_id)
            ->with('success', 'Preference updated successfully!');
    }

    /**
     * Delete a preference.
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
