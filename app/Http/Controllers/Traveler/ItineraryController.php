<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ItineraryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $itineraries = Itinerary::query()
            ->whereHas('traveler', fn ($q) => $q->where('user_id', $userId))
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
            'name'        => ['required', 'string', 'max:255'],
            'country'     => ['required', 'string', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'destination' => ['nullable', 'string', 'max:255'],
        ]);

        // Ensure a Traveler profile exists for this user.
        $user = Auth::user();
        $traveler = $user->traveler ?? Traveler::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name, 'email' => $user->email]
        );

        // Build the itinerary without mass-assigning location (in case it's not fillable).
        $itinerary = new Itinerary([
            'name'        => $data['name'],
            'country'     => $data['country'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            // Optional description → default to empty string to satisfy NOT NULL schemas.
            'description' => $data['description'] ?? '',
            'traveler_id' => $traveler->id,
        ]);

        // Satisfy NOT NULL "location" at the itinerary level (if your schema has it).
        $itinerary->location = $request->input('destination')
            ?? $request->input('country')
            ?? '';

        // Persist optional destination only if the column exists.
        if (Schema::hasColumn('itineraries', 'destination')) {
            $itinerary->destination = $request->input('destination');
        }

        $itinerary->save();

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
            'name'        => ['required', 'string', 'max:255'],
            'country'     => ['required', 'string', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'destination' => ['nullable', 'string', 'max:255'],
        ]);

        // Fill required + optional fields (description defaults to empty string).
        $itinerary->name        = $data['name'];
        $itinerary->country     = $data['country'];
        $itinerary->start_date  = $data['start_date'];
        $itinerary->end_date    = $data['end_date'];
        $itinerary->description = $data['description'] ?? '';

        // Keep DB happy for NOT NULL "location"
        $itinerary->location = $request->input('destination')
            ?? $request->input('country')
            ?? ($itinerary->location ?? '');

        // Optional destination, only if column exists.
        if (Schema::hasColumn('itineraries', 'destination')) {
            $itinerary->destination = $request->input('destination');
        }

        $itinerary->save();

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
