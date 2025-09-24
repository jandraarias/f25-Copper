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
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $traveler = Auth::user()->traveler;

        $traveler->itineraries()->create($request->only('name', 'destination', 'start_date', 'end_date', 'notes'));

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
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $itinerary->update($request->only('name', 'destination', 'start_date', 'end_date', 'notes'));

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
