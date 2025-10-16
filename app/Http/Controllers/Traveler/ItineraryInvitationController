<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Models\ItineraryInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryInvitationController extends Controller
{
    /**
     * Display the invitation acceptance page (email-based flow).
     * 
     * In your current setup, this is optional —
     * invitations are mainly accepted through the dashboard.
     */
    public function show(string $token)
    {
        $invitation = ItineraryInvitation::where('token', $token)->firstOrFail();

        // If already handled, redirect to dashboard with info
        if ($invitation->status !== 'pending') {
            return redirect()
                ->route('traveler.dashboard')
                ->with('info', "This invitation has already been {$invitation->status}.");
        }

        // If user logged in and email matches, skip to dashboard
        if (Auth::check() && strtolower(Auth::user()->email) === strtolower($invitation->email)) {
            return redirect()->route('traveler.dashboard');
        }

        // Otherwise, show invitation info (useful if emails are enabled later)
        return view('traveler.itineraries.invite', compact('invitation'));
    }

    /**
     * Accept an itinerary invitation.
     * 
     * Works for both in-app and email-based acceptance.
     */
    public function accept(Request $request, string $token)
    {
        $invitation = ItineraryInvitation::where('token', $token)->firstOrFail();

        // Must be pending
        if ($invitation->status !== 'pending') {
            return redirect()
                ->route('traveler.dashboard')
                ->with('warning', 'This invitation is no longer active.');
        }

        // Require login
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('warning', 'Please log in to accept this invitation.');
        }

        $user = Auth::user();

        // Prevent mismatched email acceptance
        if (strtolower($user->email) !== strtolower($invitation->email)) {
            return redirect()
                ->route('traveler.dashboard')
                ->with('error', 'You are not authorized to accept this invitation.');
        }

        // Accept and attach collaborator
        $invitation->accept($user);

        return redirect()
            ->route('traveler.dashboard')
            ->with('success', 'You have successfully joined this itinerary!');
    }

    /**
     * Decline an itinerary invitation.
     * 
     * Works for both dashboard and email links.
     */
    public function decline(string $token)
    {
        $invitation = ItineraryInvitation::where('token', $token)->firstOrFail();

        // Must be pending
        if ($invitation->status !== 'pending') {
            return redirect()
                ->route('traveler.dashboard')
                ->with('warning', 'This invitation is no longer active.');
        }

        // Mark as declined
        $invitation->decline();

        return redirect()
            ->route('traveler.dashboard')
            ->with('info', 'You have declined the invitation.');
    }
}
