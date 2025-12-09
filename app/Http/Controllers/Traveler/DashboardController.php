<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Itinerary;
use App\Models\ItineraryInvitation;
use App\Models\ExpertSuggestion;

class DashboardController extends Controller
{
    public function index()
    {
        $traveler = Auth::user()->traveler;

        // Owned or collaborative itineraries
        $itineraries = Itinerary::where('traveler_id', $traveler->id)
            ->orWhereHas('collaborators', fn($q) => $q->where('user_id', Auth::id()))
            ->with(['countries', 'items'])
            ->latest()
            ->take(5)
            ->get();

        // Pending invitations
        $pendingInvitations = ItineraryInvitation::where('email', Auth::user()->email)
            ->where('status', 'pending')
            ->with('itinerary.traveler.user')
            ->get();

        // Pending expert suggestions for traveler's itineraries
        $pendingSuggestions = ExpertSuggestion::whereHas('itineraryItem.itinerary', function($q) use ($traveler) {
                $q->where('traveler_id', $traveler->id);
            })
            ->where('status', 'pending')
            ->with(['itineraryItem.itinerary', 'expert.user', 'place'])
            ->latest()
            ->get();

        return view('traveler.dashboard', compact('traveler', 'itineraries', 'pendingInvitations', 'pendingSuggestions'));
    }
}
