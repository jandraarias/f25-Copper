<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
            Itinerary Collaboration Requests
        </h2>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <x-flash-messages />

            @if($invitations->count())
                <div class="space-y-4">
                    @foreach($invitations as $invitation)
                        <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                                    rounded-2xl shadow-soft hover:shadow-glow hover:scale-[1.01]
                                    transition-all duration-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                                            {{ $invitation->itinerary->name }}
                                        </h3>

                                        <div class="mt-2 space-y-1 text-sm text-ink-600 dark:text-sand-300">
                                            <p>
                                                <strong>Traveler:</strong>
                                                {{ $invitation->traveler->user->name }}
                                            </p>
                                            <p>
                                                <strong>Destination:</strong>
                                                {{ $invitation->itinerary->destination ?? 'Not specified' }}
                                            </p>
                                            <p>
                                                <strong>Duration:</strong>
                                                {{ $invitation->itinerary->start_date?->format('M d, Y') ?? 'Not set' }}
                                                @if($invitation->itinerary->end_date)
                                                    - {{ $invitation->itinerary->end_date->format('M d, Y') }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-ink-500 dark:text-sand-400 mt-2">
                                                Requested {{ $invitation->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('expert.itinerary-invitations.show', $invitation) }}"
                                           class="px-4 py-2 rounded-full border border-copper text-copper
                                                  hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                                                  transition-all duration-200 ease-out text-sm font-medium">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $invitations->links() }}
            @else
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                            rounded-2xl shadow-soft p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-16 h-16 text-sand-300 dark:text-sand-600 mx-auto mb-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>

                    <h3 class="text-lg font-semibold text-ink-900 dark:text-ink-100 mb-2">
                        No Pending Requests
                    </h3>
                    <p class="text-ink-600 dark:text-sand-300">
                        When travelers invite you to collaborate on their itineraries, you'll see them here.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
