<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\ExpertItineraryInvitation;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItineraryInvitationController extends Controller
{
    /**
     * Show pending invitations for the current expert.
     */
    public function index()
    {
        $expert = Auth::user()->expert;

        $invitations = ExpertItineraryInvitation::where('expert_id', $expert->id)
            ->where('status', 'pending')
            ->with(['itinerary', 'traveler'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('expert.itinerary-invitations.index', compact('invitations'));
    }

    /**
     * Show a specific invitation.
     */
    public function show(ExpertItineraryInvitation $invitation)
    {
        $expert = Auth::user()->expert;

        if ($invitation->expert_id !== $expert->id) {
            abort(403);
        }

        $invitation->load(['itinerary.items.place', 'itinerary.traveler', 'traveler']);

        return view('expert.itinerary-invitations.show', compact('invitation'));
    }

    /**
     * Accept an itinerary invitation.
     */
    public function accept(Request $request, ExpertItineraryInvitation $invitation)
    {
        $expert = Auth::user()->expert;

        if ($invitation->expert_id !== $expert->id) {
            abort(403);
        }

        $invitation->accept();

        // If the request expects JSON (AJAX), return a JSON response for the frontend to update UI
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'invitation_id' => $invitation->id,
                'status' => 'accepted',
                'itinerary' => [
                    'id' => $invitation->itinerary->id,
                    'name' => $invitation->itinerary->name,
                ],
                'message' => 'Invitation accepted',
            ]);
        }

        return redirect()
            ->route('expert.itineraries.show', $invitation->itinerary)
            ->with('success', 'You have accepted the itinerary invitation! You can now view and edit this itinerary.');
    }

    /**
     * Decline an itinerary invitation.
     */
    public function decline(Request $request, ExpertItineraryInvitation $invitation)
    {
        $expert = Auth::user()->expert;

        if ($invitation->expert_id !== $expert->id) {
            abort(403);
        }

        $invitation->decline();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'invitation_id' => $invitation->id,
                'status' => 'declined',
                'message' => 'Invitation declined',
            ]);
        }

        return redirect()
            ->route('expert.itinerary-invitations.index')
            ->with('success', 'You have declined the itinerary invitation.');
    }
}
