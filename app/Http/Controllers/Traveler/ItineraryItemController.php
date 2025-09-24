<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ItineraryItemController extends Controller
{
    public function store(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $this->authorize('update', $itinerary);

        $data = $request->validate([
            'type'       => ['required', 'in:flight,hotel,activity,transfer,note'],
            'title'      => ['required', 'string', 'max:255'],
            'location'   => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date'],
            'end_time'   => ['nullable', 'date', 'after_or_equal:start_time'],
            'details'    => ['nullable', 'string'],
        ]);

        $itinerary->items()->create($data);

        return back()->with('success', 'Item added.');
    }

    public function update(Request $request, ItineraryItem $item): RedirectResponse
    {
        $this->authorize('update', $item->itinerary);

        $data = $request->validate([
            'type'       => ['required', 'in:flight,hotel,activity,transfer,note'],
            'title'      => ['required', 'string', 'max:255'],
            'location'   => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date'],
            'end_time'   => ['nullable', 'date', 'after_or_equal:start_time'],
            'details'    => ['nullable', 'string'],
        ]);

        $item->update($data);

        return back()->with('success', 'Item updated.');
    }

    public function destroy(ItineraryItem $item): RedirectResponse
    {
        $this->authorize('delete', $item->itinerary);

        $item->delete();

        return back()->with('success', 'Item removed.');
    }
}
