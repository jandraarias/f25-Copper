<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the user's itineraries.
     */
    public function index(): View
    {
        $traveler = Auth::user()->traveler;

        $itineraries = Itinerary::with('items')
            ->where('traveler_id', $traveler->id)
            ->latest('start_date')
            ->paginate(10);

        return view('traveler.itineraries.index', compact('itineraries'));
    }

    /**
     * Show the form for creating a new itinerary.
     */
    public function create(): View
    {
        return view('traveler.itineraries.create');
    }

    /**
     * Store a newly created itinerary.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'country'     => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $validated['traveler_id'] = Auth::user()->traveler->id;

        Itinerary::create($validated);

        return redirect()->route('traveler.itineraries.index')
            ->with('success', 'Itinerary created successfully.');
    }

    /**
     * Display the specified itinerary.
     */
    public function show(Itinerary $itinerary): View
    {
        $this->authorize('view', $itinerary);

        $itinerary->load('items');

        return view('traveler.itineraries.show', compact('itinerary'));
    }

    /**
     * Show the form for editing the specified itinerary.
     */
    public function edit(Itinerary $itinerary): View
    {
        $this->authorize('update', $itinerary);

        return view('traveler.itineraries.edit', compact('itinerary'));
    }

    /**
     * Update the specified itinerary.
     */
    public function update(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $this->authorize('update', $itinerary);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'country'     => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $itinerary->update($validated);

        return redirect()->route('traveler.itineraries.index')
            ->with('success', 'Itinerary updated successfully.');
    }

    /**
     * Remove the specified itinerary.
     */
    public function destroy(Itinerary $itinerary): RedirectResponse
    {
        $this->authorize('delete', $itinerary);

        $itinerary->delete();

        return redirect()->route('traveler.itineraries.index')
            ->with('success', 'Itinerary deleted successfully.');
    }
}
