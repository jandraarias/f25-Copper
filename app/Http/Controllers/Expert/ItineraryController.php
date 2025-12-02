<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    public function index()
    {
        // Later: fetch only itineraries assigned to this expert
        // Example:
        // $itineraries = Itinerary::where('expert_id', auth()->id())->get();
        // return view('expert.itineraries.index', compact('itineraries'));

        return view('expert.itineraries.index');
    }

    public function show(Itinerary $itinerary)
    {
        // Enforce expert access
        // Adjust this based on your data structure

        // ❗ Example if Itinerary has `expert_id`
        // if ($itinerary->expert_id !== auth()->id()) {
        //     abort(403);
        // }

        return view('expert.itineraries.show', compact('itinerary'));
    }
}
