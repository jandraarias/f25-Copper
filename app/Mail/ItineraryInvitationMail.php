<?php

namespace App\Mail;

use App\Models\ItineraryInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ItineraryInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public ItineraryInvitation $invitation;

    /**
     * Create a new message instance.
     */
    public function __construct(ItineraryInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $itinerary = $this->invitation->itinerary;

        return $this->subject("You're invited to collaborate on “{$itinerary->name}”")
                    ->markdown('emails.itineraries.invitation')
                    ->with([
                        'invitation' => $this->invitation,
                        'itinerary'  => $itinerary,
                        'acceptUrl'  => route('itinerary-invitations.show', $this->invitation->token),
                    ]);
    }
}
