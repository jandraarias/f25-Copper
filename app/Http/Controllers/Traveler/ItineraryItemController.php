<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Place;
use Illuminate\Http\Request;

class ItineraryItemController extends Controller
{
    public function store(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        // Optional: block manual add if AI-generated items exist
        if ($itinerary->items()->count() > 0) {
            return back()->with('warning', 'This itinerary is AI-generated. You can edit or regenerate it instead.');
        }

        $request->validate([
            'type'      => ['required', 'string', 'max:50'],
            'title'     => ['required', 'string', 'max:255'],
            'location'  => ['nullable', 'string', 'max:255'],
            'start_time'=> ['nullable', 'date'],
            'end_time'  => ['nullable', 'date', 'after_or_equal:start_time'],
            'details'   => ['nullable', 'string'],
        ]);

        $itinerary->items()->create($request->only('type', 'title', 'location', 'start_time', 'end_time', 'details'));

        return redirect()
            ->route('traveler.itineraries.show', $itinerary)
            ->with('success', 'Itinerary item added successfully!');
    }

    public function update(Request $request, ItineraryItem $item)
    {
        $this->authorize('update', $item->itinerary);

        $request->validate([
            'type'      => ['required', 'string', 'max:50'],
            'title'     => ['required', 'string', 'max:255'],
            'location'  => ['nullable', 'string', 'max:255'],
            'start_time'=> ['nullable', 'date'],
            'end_time'  => ['nullable', 'date', 'after_or_equal:start_time'],
            'details'   => ['nullable', 'string'],
        ]);

        $item->update($request->only('type', 'title', 'location', 'start_time', 'end_time', 'details'));

        return redirect()
            ->route('traveler.itineraries.show', $item->itinerary_id)
            ->with('success', 'Itinerary item updated successfully!');
    }

    public function destroy(ItineraryItem $item)
    {
        $this->authorize('delete', $item->itinerary);

        $itineraryId = $item->itinerary_id;
        $item->delete();

        return redirect()
            ->route('traveler.itineraries.show', $itineraryId)
            ->with('success', 'Itinerary item deleted successfully!');
    }

    public function addPlace(Request $request, Place $place)
    {
        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
        ]);

        $itinerary = $request->user()->traveler->itineraries()->findOrFail($request->itinerary_id);

        $itinerary->items()->create([
            'place_id'   => $place->id,
            'title'      => $place->name,
            'details'    => $place->description ?? null,
            'location'   => $place->address ?? null,
            'rating'     => $place->rating ?? null,
            'google_maps_url' => $place->meta['google_maps_url'] ?? null,
            'start_time' => null,
            'end_time'   => null,
        ]);

        return back()->with('success', "{$place->name} was added to your itinerary.");
    }
}
