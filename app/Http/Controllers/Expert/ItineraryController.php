<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ItineraryController extends Controller
{
    /**
     * List itineraries this expert has accepted invitations for.
     */
    public function index()
    {
        $expert = Auth::user()->expert;

        if (! $expert) {
            // No expert profile yet => no itineraries
            $itineraries = collect();
            $total = 0;
            $upcomingCount = 0;
            $pastCount = 0;
            return view('expert.itineraries.index', compact('itineraries', 'total', 'upcomingCount', 'pastCount'));
        }

        // Base query: itineraries where this expert has an accepted invitation
        $base = Itinerary::query()
            ->whereHas('expertInvitations', function ($q) use ($expert) {
                $q->where('expert_id', $expert->id)
                  ->where('status', 'accepted');
            });

        // Counts across the full matching set
        $total = (clone $base)->count();
        $upcomingCount = (clone $base)->whereDate('start_date', '>=', today())->count();
        $pastCount = (clone $base)->whereDate('end_date', '<', today())->count();

        // Paginate results for the list view
        $itineraries = $base->with(['traveler.user', 'items'])
            ->orderBy('start_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('expert.itineraries.index', compact('itineraries', 'total', 'upcomingCount', 'pastCount'));
    }

    public function show(Itinerary $itinerary)
    {
        $expert = Auth::user()->expert;
        if (! $expert) {
            abort(403);
        }

        // Ensure the itinerary is assigned to this expert (accepted invite)
        $assigned = $itinerary->expertInvitations()
            ->where('expert_id', $expert->id)
            ->where('status', 'accepted')
            ->exists();

        if (! $assigned) {
            abort(403);
        }

        $itinerary->load(['items.place', 'countries', 'traveler.user']);

        return view('expert.itineraries.show', compact('itinerary'));
    }

    /**
     * Edit a specific itinerary.
     */
    public function edit(Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        return view('expert.itineraries.edit', compact('itinerary'));
    }

    /**
     * Update a specific itinerary.
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        $this->authorize('update', $itinerary);

        // add your update logic here
        // $itinerary->update($request->validated());

        return redirect()
            ->route('expert.itineraries.show', $itinerary)
            ->with('success', 'Itinerary updated successfully.');
    }
}
