<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\ItineraryInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryInvitationController extends Controller
{
    /**
     * Display the invitation landing page.
     * 
     * In this app, the page is rarely needed because most acceptances
     * happen while already logged in—but this still supports email flows.
     */
    public function show(string $token)
    {
        $invitation = ItineraryInvitation::where('token', $token)->firstOrFail();

        // Redirect if the invitation is no longer pending
        if ($invitation->status !== 'pending') {
            return redirect()
                ->route('traveler.dashboard')
                ->with('info', "This invitation has already been {$invitation->status}.");
        }

        // If the logged-in user matches the email, skip the landing page
        if (Auth::check() && strtolower(Auth::user()->email) === strtolower($invitation->email)) {
            return redirect()->route('traveler.dashboard');
        }

        return view('traveler.itineraries.invite', compact('invitation'));
    }

    /**
     * Accept an itinerary invitation.
     */
    public function accept(Request $request, string $token)
    {
        $invitation = ItineraryInvitation::where('token', $token)->firstOrFail();

        // Invitation must still be pending
        if ($invitation->status !== 'pending') {
            return redirect()
                ->route('traveler.dashboard')
                ->with('warning', 'This invitation is no longer active.');
        }

        // User must be logged in
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('warning', 'Please log in to accept this invitation.');
        }

        $user = Auth::user();

        // Ensure the invitation email matches the logged-in user
        if (strtolower($user->email) !== strtolower($invitation->email)) {
            return redirect()
                ->route('traveler.dashboard')
                ->with('error', 'You are not authorized to accept this invitation.');
        }

        $itinerary = $invitation->itinerary;

        // Attach the user as a collaborator (idempotent)
        $itinerary->collaborators()->syncWithoutDetaching($user->id);

        // DELETE the invitation after successful acceptance (fix for duplicate pending rows)
        $invitation->delete();

        return redirect()
            ->route('traveler.itineraries.show', $itinerary)
            ->with('success', 'You have successfully joined this itinerary!');
    }

    /**
     * Decline an itinerary invitation.
     */
    public function decline(string $token)
    {
        $invitation = ItineraryInvitation::where('token', $token)->firstOrFail();

        // Invitation must still be pending
        if ($invitation->status !== 'pending') {
            return redirect()
                ->route('traveler.dashboard')
                ->with('warning', 'This invitation is no longer active.');
        }

        // Declining an invite does NOT add the user as a collaborator
        // If the invitation model handles status setting, use that:
        $invitation->update(['status' => 'declined']);

        return redirect()
            ->route('traveler.dashboard')
            ->with('info', 'You have declined the invitation.');
    }
}
