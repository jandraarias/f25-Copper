<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // add this

class ItineraryController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // use facade
        $itineraries = Itinerary::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('traveler.itineraries.index', compact('itineraries'));
    }

    public function create()
    {
        return view('traveler.itineraries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'destination' => ['nullable', 'string', 'max:255'],
            'notes'       => ['nullable', 'string'],
        ]);

        $userId = Auth::id(); // use facade
        $itinerary = Itinerary::create($data + ['user_id' => $userId]);

        return redirect()
            ->route('traveler.itineraries.edit', $itinerary)
            ->with('success', 'Itinerary created.');
    }

    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);
        $itinerary->load(['items' => fn ($q) => $q->latest()]);
        return view('traveler.itineraries.edit', compact('itinerary'));
    }

    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'destination' => ['nullable', 'string', 'max:255'],
            'notes'       => ['nullable', 'string'],
        ]);

        $itinerary->update($data);

        return back()->with('success', 'Itinerary updated.');
    }

    public function destroy(Itinerary $itinerary)
    {
        $this->authorize('delete', $itinerary);
        $itinerary->delete();

        return redirect()
            ->route('traveler.itineraries.index')
            ->with('success', 'Itinerary deleted.');
    }
}
