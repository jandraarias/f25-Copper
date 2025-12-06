<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ $invitation->itinerary->name }}
            </h2>

            <a href="{{ route('expert.itinerary-invitations.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-8">
            <x-flash-messages />

            {{-- Itinerary Details Card --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                        rounded-3xl shadow-soft hover:shadow-glow transition-all duration-300">

                <div class="p-8 sm:p-10 text-ink-900 dark:text-ink-100">

                    <h3 class="text-2xl font-bold mb-6">Itinerary Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Traveler Info --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-1">
                                Traveler
                            </label>
                            <p class="text-lg text-ink-900 dark:text-sand-100">
                                {{ $invitation->traveler->user->name }}
                            </p>
                            <p class="text-sm text-ink-600 dark:text-sand-300">
                                {{ $invitation->traveler->user->email }}
                            </p>
                        </div>

                        {{-- Destination --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-1">
                                Destination
                            </label>
                            <p class="text-lg text-ink-900 dark:text-sand-100">
                                {{ $invitation->itinerary->destination ?? 'Not specified' }}
                            </p>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-1">
                                Start Date
                            </label>
                            <p class="text-lg text-ink-900 dark:text-sand-100">
                                {{ $invitation->itinerary->start_date?->format('M d, Y') ?? 'Not set' }}
                            </p>
                        </div>

                        {{-- End Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-1">
                                End Date
                            </label>
                            <p class="text-lg text-ink-900 dark:text-sand-100">
                                {{ $invitation->itinerary->end_date?->format('M d, Y') ?? 'Not set' }}
                            </p>
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-1">
                                Description
                            </label>
                            <p class="text-ink-900 dark:text-sand-100 leading-relaxed">
                                {{ $invitation->itinerary->description ?? 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Itinerary Items --}}
            @if($invitation->itinerary->items->count())
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                            rounded-3xl shadow-soft">

                    <div class="p-8 sm:p-10">
                        <h3 class="text-2xl font-bold mb-6 text-ink-900 dark:text-ink-100">
                            Itinerary Items ({{ $invitation->itinerary->items->count() }})
                        </h3>

                        <div class="space-y-4">
                            @foreach($invitation->itinerary->items as $item)
                                <div class="border border-sand-200 dark:border-ink-700 rounded-xl p-4
                                            hover:shadow-glow transition-all duration-200">

                                    <h4 class="font-semibold text-ink-900 dark:text-sand-100">
                                        {{ $item->place->name ?? 'Unnamed Place' }}
                                    </h4>

                                    <p class="text-sm text-ink-600 dark:text-sand-300 mt-1">
                                        {{ $item->place->description ?? 'No description' }}
                                    </p>

                                    @if($item->notes)
                                        <p class="text-sm text-ink-500 dark:text-sand-400 mt-2">
                                            <strong>Notes:</strong> {{ $item->notes }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            @if($invitation->status === 'pending')
                <div class="flex justify-center gap-4">
                    <form method="POST" action="{{ route('expert.itinerary-invitations.accept', $invitation) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-8 py-3 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                       hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            Accept Invitation
                        </button>
                    </form>

                    <form method="POST" action="{{ route('expert.itinerary-invitations.decline', $invitation) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-8 py-3 rounded-full border border-red-500 text-red-600
                                       hover:bg-red-50 dark:hover:bg-red-900/20 hover:shadow-glow hover:scale-[1.03]
                                       transition-all duration-200 ease-out font-semibold">
                            Decline
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center p-6 bg-sand-50 dark:bg-sand-900/40 border border-sand-200 dark:border-ink-700 rounded-2xl">
                    <p class="text-ink-600 dark:text-sand-300 font-medium">
                        @if($invitation->status === 'accepted')
                            ✓ You have accepted this invitation. You can now view and edit this itinerary.
                        @elseif($invitation->status === 'declined')
                            ✗ You have declined this invitation.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
