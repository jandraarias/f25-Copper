<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardsController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | INDEX — List all rewards
    |----------------------------------------------------------------------
    */
    public function index()
    {
        // Load all rewards with related place
        $rewards = Reward::with('place')->get();

        // Load traveler itineraries for “Quick Add” modals
        $itineraries = Auth::user()->traveler
            ->itineraries()
            ->select('id', 'name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('traveler.rewards.index', compact('rewards', 'itineraries'));
    }

    /*
    |----------------------------------------------------------------------
    | APPLY — Add reward to itinerary by replacing a food item
    |----------------------------------------------------------------------
    */
    public function apply(Request $request, Reward $reward)
    {
        $request->validate([
            'itinerary_id' => ['required', 'integer', 'exists:itineraries,id'],
        ]);

        $traveler = Auth::user()->traveler;

        // Ensure the itinerary belongs to the traveler
        $itinerary = $traveler->itineraries()
            ->with('items.place') // load items and places
            ->findOrFail($request->itinerary_id);

        $place = $reward->place;

        if (!$place) {
            return back()->with('error', 'This reward is missing a related place.');
        }

        /*
        |--------------------------------------------------------------------------
        | Step 1: Find a food item to replace
        |--------------------------------------------------------------------------
        */
        $itemToReplace = $itinerary->items
            ->filter(fn($item) => $item->type === 'food')
            ->sortBy('start_time')
            ->first();

        if (!$itemToReplace) {
            return back()->with('error', 'This itinerary has no food items to replace.');
        }

        /*
        |--------------------------------------------------------------------------
        | Step 2: Update the itinerary item with the reward’s place
        |--------------------------------------------------------------------------
        */
        $itemToReplace->update([
            'place_id'        => $place->id,
            'title'           => $place->name,
            'rating'          => $place->rating,
            'location'        => $place->address,
            'google_maps_url' => $place->meta['maps_url'] ?? null,
            'details'         => "Reward applied: {$reward->title}\n\n" . ($reward->description ?? ''),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Step 3: Redirect back to rewards page
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('traveler.rewards')
            ->with('success', "Reward applied to itinerary: {$itinerary->name}");
    }

    public function applyToItinerary(Request $req)
    {
        $req->validate([
            'reward_id' => 'required|exists:rewards,id',
            'itinerary_id' => 'required|exists:itineraries,id',
            'replace_item_id' => 'required|exists:itinerary_items,id',
        ]);

        $traveler = Auth::user()->traveler;

        // Validate ownership
        $itinerary = $traveler->itineraries()
            ->findOrFail($req->itinerary_id);

        $reward = Reward::with('place')->findOrFail($req->reward_id);
        $item = ItineraryItem::findOrFail($req->replace_item_id);

        if ($item->itinerary_id !== $itinerary->id) {
            return back()->with('error', 'You cannot modify this itinerary item.');
        }

        $place = $reward->place;

        // Apply the reward
        $item->update([
            'place_id' => $place->id,
            'title' => $place->name,
            'rating' => $place->rating,
            'location' => $place->address,
            'google_maps_url' => $place->meta['maps_url'] ?? null,
            'details' => "Reward applied: {$reward->title}\n\n" . ($reward->description ?? ''),
            'type' => 'food',
        ]);

        return back()->with('success', 'Reward applied successfully!');
    }
}
