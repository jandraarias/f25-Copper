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
            'countries'   => ['required', 'array', 'min:1'],
            'countries.*' => ['integer', 'exists:countries,id'],
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $traveler = Auth::user()->traveler;

        // Create itinerary (traveler_id set via relationship)
        $itinerary = $traveler->itineraries()->create(
            $request->only('name', 'destination', 'start_date', 'end_date', 'description')
        );

        // Attach countries via pivot
        $itinerary->countries()->attach($request->input('countries'));

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

        // Eager load items + countries
        $itinerary->load(['items', 'countries']);

        return view('traveler.itineraries.show', compact('itinerary'));
    }

    /**
     * Show the form for editing the specified itinerary.
     */
    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        // Load countries for multi-select
        $itinerary->load('countries');

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
            'countries'   => ['required', 'array', 'min:1'],
            'countries.*' => ['integer', 'exists:countries,id'],
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        // Update itinerary itself
        $itinerary->update(
            $request->only('name', 'destination', 'start_date', 'end_date', 'description')
        );

        // Sync updated countries
        $itinerary->countries()->sync($request->input('countries'));

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

        // Detach related countries before deletion
        $itinerary->countries()->detach();

        $itinerary->delete();

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary deleted successfully!');
    }
}
