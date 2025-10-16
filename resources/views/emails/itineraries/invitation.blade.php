@component('mail::message')
# You're invited!

You’ve been invited to collaborate on **{{ $itinerary->name }}**.

@component('mail::panel')
{{ $itinerary->description ?? 'No description provided.' }}
@endcomponent

Click below to view and accept the invitation:

@component('mail::button', ['url' => $acceptUrl])
View Invitation
@endcomponent

If you don’t wish to join, you can safely ignore this message.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
