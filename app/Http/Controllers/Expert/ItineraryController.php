<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{
    /**
     * List itineraries this expert has accepted invitations for.
     */
    public function index()
    {
        $expert = Auth::user()->expert;

        $itineraries = Itinerary::whereHas('expertInvitations', function ($q) use ($expert) {
            $q->where('expert_id', $expert->id)
              ->where('status', 'accepted');
        })->get();

        return view('expert.itineraries.index', compact('itineraries'));
    }

    /**
     * Show a specific itinerary (expert must be authorized).
     */
    public function show(Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary);

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
