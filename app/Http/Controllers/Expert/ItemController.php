<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Show the create form for a new item.
     */
    public function create(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        return view('expert.itineraries.items.create', [
            'itinerary' => $itinerary,
        ]);
    }

    /**
     * Store a new itinerary item.
     */
    public function store(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $validated = $request->validate([
            'type'       => 'required|string|max:255',
            'title'      => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time'   => 'nullable|date|after_or_equal:start_time',
            'location'   => 'nullable|string|max:255',
            'details'    => 'nullable|string',
        ]);

        $validated['itinerary_id'] = $itinerary->id;

        ItineraryItem::create($validated);

        return redirect()
            ->route('expert.itineraries.edit', $itinerary)
            ->with('success', 'Item added successfully.');
    }

    /**
     * Show the edit form for an item.
     */
    public function edit(ItineraryItem $item)
    {
        $this->authorize('update', $item->itinerary);

        return view('expert.itineraries.items.edit', [
            'item' => $item,
            'itinerary' => $item->itinerary,
        ]);
    }

    /**
     * Update an existing itinerary item.
     */
    public function update(Request $request, ItineraryItem $item)
    {
        $this->authorize('update', $item->itinerary);

        $validated = $request->validate([
            'type'       => 'required|string|max:255',
            'title'      => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time'   => 'nullable|date|after_or_equal:start_time',
            'location'   => 'nullable|string|max:255',
            'details'    => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()
            ->route('expert.itineraries.edit', $item->itinerary)
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Delete an itinerary item.
     */
    public function destroy(ItineraryItem $item)
    {
        $this->authorize('update', $item->itinerary);

        $itinerary = $item->itinerary;
        $item->delete();

        return redirect()
            ->route('expert.itineraries.edit', $itinerary)
            ->with('success', 'Item removed successfully.');
    }
}
