<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Itinerary;
use App\Models\ItineraryInvitation;

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

        return view('traveler.dashboard', compact('traveler', 'itineraries', 'pendingInvitations'));
    }
}
