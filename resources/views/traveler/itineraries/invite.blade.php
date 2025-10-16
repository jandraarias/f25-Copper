<x-app-layout>
    <div class="py-20 text-center max-w-lg mx-auto">
        <h1 class="text-2xl font-semibold text-copper mb-4">You've been invited!</h1>
        <p class="text-ink-700 dark:text-ink-200 mb-6">
            You’ve been invited to collaborate on
            <strong>{{ $invitation->itinerary->name }}</strong>.
        </p>

        @auth
            <form action="{{ route('itinerary-invitations.accept', $invitation->token) }}" method="POST" class="inline-block mr-3">
                @csrf
                <button class="px-6 py-2.5 bg-gradient-copper text-white rounded-full font-medium shadow-soft hover:shadow-glow hover:scale-[1.03] transition">
                    Accept Invitation
                </button>
            </form>
            <form action="{{ route('itinerary-invitations.decline', $invitation->token) }}" method="POST" class="inline-block">
                @csrf
                <button class="px-6 py-2.5 border border-ink-500 text-ink-700 rounded-full font-medium hover:text-copper hover:border-copper transition">
                    Decline
                </button>
            </form>
        @else
            <p class="text-sm text-ink-600 dark:text-ink-300 mt-4">
                Please <a href="{{ route('login') }}" class="text-copper hover:underline">log in</a> to respond to this invitation.
            </p>
        @endauth
    </div>
</x-app-layout>
