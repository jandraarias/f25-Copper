<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the traveler’s itineraries.
     */
    public function index()
    {
        $traveler = Auth::user()->traveler;
        $itineraries = $traveler->itineraries()->latest()->paginate(10);

        return view('traveler.itineraries.index', compact('itineraries'));
    }

    /**
     * Show the form for creating a new itinerary.
     */
    public function create()
    {
        return view('traveler.itineraries.create');
    }

    /**
     * Store a newly created itinerary.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'country'     => ['required', 'string', 'max:255'],   // new required field
            'destination' => ['nullable', 'string', 'max:255'],   // main city, optional
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $traveler = Auth::user()->traveler;

        // Relation create will set traveler_id automatically
        $traveler->itineraries()->create(
            $request->only('name', 'country', 'destination', 'start_date', 'end_date', 'description')
        );

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary created successfully!');
    }

    /**
     * Display the specified itinerary.
     */
    public function show(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

        // Ensure items are eager loaded for the view
        $itinerary->load('items');

        return view('traveler.itineraries.show', compact('itinerary'));
    }

    /**
     * Show the form for editing the specified itinerary.
     */
    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        return view('traveler.itineraries.edit', compact('itinerary'));
    }

    /**
     * Update the specified itinerary.
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'country'     => ['required', 'string', 'max:255'],   // must always be filled
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $itinerary->update(
            $request->only('name', 'country', 'destination', 'start_date', 'end_date', 'description')
        );

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary updated successfully!');
    }

    /**
     * Remove the specified itinerary.
     */
    public function destroy(Itinerary $itinerary)
    {
        $this->authorize('delete', $itinerary);

        $itinerary->delete();

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary deleted successfully!');
    }
}
