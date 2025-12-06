<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExpertItineraryInvitation;
use App\Models\Itinerary;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $expert = $user->expert;

        // Pending invitations for this expert
        $pendingInvitations = collect();
        if ($expert) {
            $pendingInvitations = ExpertItineraryInvitation::where('expert_id', $expert->id)
                ->pending()
                ->with(['itinerary', 'traveler.user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Itineraries where expert has accepted invitation
        $itineraries = collect();
        if ($expert) {
            $itineraryIds = ExpertItineraryInvitation::where('expert_id', $expert->id)
                ->accepted()
                ->pluck('itinerary_id')
                ->toArray();

            $itineraries = Itinerary::whereIn('id', $itineraryIds)
                ->with(['items'])
                ->get();
        }

        return view('expert.dashboard', compact('expert', 'pendingInvitations', 'itineraries'));
    }
}
