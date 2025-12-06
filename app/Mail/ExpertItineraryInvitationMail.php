<?php

namespace App\Mail;

use App\Models\ExpertItineraryInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpertItineraryInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ExpertItineraryInvitation $invitation) {}

    public function build()
    {
        $itinerary = $this->invitation->itinerary;

        return $this->subject("New collaboration request: {$itinerary->name}")
                    ->view('emails.expert_itinerary_invitation')
                    ->with([
                        'invitation' => $this->invitation,
                        'itinerary' => $itinerary,
                    ]);
    }
}
