<div style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#111827;">
    <h2>New Itinerary Collaboration Request</h2>

    <p>Hello {{ $invitation->expert->name }},</p>

    <p>{{ $invitation->traveler->user->name ?? 'A traveler' }} has invited you to collaborate on the itinerary "{{ $itinerary->name }}" ({{ $itinerary->destination ?? 'destination not set' }}).</p>

    <p>
        Dates: {{ $itinerary->start_date ?? 'TBD' }} &ndash; {{ $itinerary->end_date ?? 'TBD' }}
    </p>

    <p>
        To review the request and accept or decline, please sign in and visit your Itinerary Invitations page:
    </p>

    <p>
        <a href="{{ url('/expert/itinerary-invitations') }}">View Itinerary Invitations</a>
    </p>

    <p>
        If you'd prefer to reply later, you can also find this invitation inside your account under "Itinerary Collaboration Requests." Thank you!
    </p>

    <p>— The Team</p>
</div>
