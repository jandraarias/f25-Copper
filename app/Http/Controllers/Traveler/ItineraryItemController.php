<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use Illuminate\Http\Request;

class ItineraryItemController extends Controller
{
    /**
     * Store a newly created item in an itinerary.
     */
    public function store(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

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

    /**
     * Update the specified itinerary item.
     */
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

    /**
     * Remove the specified itinerary item.
     */
    public function destroy(ItineraryItem $item)
    {
        $this->authorize('delete', $item->itinerary);

        $itineraryId = $item->itinerary_id;
        $item->delete();

        return redirect()
            ->route('traveler.itineraries.show', $itineraryId)
            ->with('success', 'Itinerary item deleted successfully!');
    }
}
