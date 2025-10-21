<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Traveler Dashboard') }}
            </h2>
            <a href="{{ route('traveler.itineraries.create') }}"
               class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                + New Itinerary
            </a>
        </div>
    </x-slot>

    @php
        $traveler = $traveler ?? optional(auth()->user())->traveler;
        $itineraries = $itineraries ?? collect();
        $preferenceProfiles = optional($traveler)->preferenceProfiles ?? collect();
        $pendingInvitations = $pendingInvitations ?? collect();
    @endphp

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- Welcome Card --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Welcome, {{ $traveler->name ?? auth()->user()->name }}!
                </p>
                <p class="text-sm text-ink-500 dark:text-sand-100 mt-1">
                    Here’s your travel summary — a snapshot of your journeys and preferences.
                </p>
            </div>

            {{-- ================= Pending Invitations ================= --}}
            @if($pendingInvitations->isNotEmpty())
                <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                            p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 2.21-1.79 4-4 4m0 0H5a2 2 0 01-2-2V7a2 2 0 012-2h3.5a2 2 0 011.6.8L12 8h7a2 2 0 012 2v5a2 2 0 01-2 2h-4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">Pending Itinerary Invitations</h3>
                    </div>

                    <p class="text-sm text-ink-600 dark:text-ink-300 mb-4">
                        You’ve been invited to collaborate on these itineraries:
                    </p>

                    <div class="divide-y divide-sand-200 dark:divide-ink-700">
                        @foreach($pendingInvitations as $invitation)
                            <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="font-semibold text-ink-900 dark:text-ink-100">
                                        {{ $invitation->itinerary->name }}
                                    </h4>
                                    <p class="text-sm text-ink-500 dark:text-ink-300">
                                        Invited by {{ $invitation->itinerary->traveler->user->name ?? 'another traveler' }}
                                    </p>
                                </div>

                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('itinerary-invitations.accept', $invitation->token) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-1.5 rounded-full bg-gradient-copper text-white font-medium text-sm
                                                       hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                            Accept
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('itinerary-invitations.decline', $invitation->token) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-1.5 rounded-full border border-red-500 text-red-600 dark:text-red-400
                                                       hover:bg-red-500 hover:text-white font-medium text-sm
                                                       hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Itineraries Summary --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.5l8.954-4.477a1.125 1.125 0 011.092 0L21.25 7.5M2.25 7.5v9a1.125 1.125 0 00.598.995l8.954 4.477a1.125 1.125 0 001.092 0l8.954-4.477a1.125 1.125 0 00.598-.995v-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">Upcoming Itineraries</h3>
                </div>

                @forelse ($itineraries as $itinerary)
                    @php
                        $sd = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M d, Y') : null;
                        $ed = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M d, Y') : null;
                    @endphp

                    <div class="mb-5 pb-4 border-b border-sand-200 dark:border-ink-700 last:border-0 last:mb-0 last:pb-0">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-ink-900 dark:text-ink-100 text-lg">
                                    {{ $itinerary->name }}
                                </p>
                                <p class="text-sm text-ink-500 dark:text-sand-100">
                                    {{ $sd ?? '—' }} → {{ $ed ?? '—' }}
                                </p>
                            </div>
                            <a href="{{ route('traveler.itineraries.show', $itinerary) }}"
                               class="group inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-copper text-copper font-medium text-sm
                                      hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </div>

                        @if($itinerary->items && $itinerary->items->count())
                            <ul class="list-disc ml-6 mt-3 text-sm text-ink-700 dark:text-sand-100">
                                @foreach ($itinerary->items->take(3) as $item)
                                    <li>
                                        <span class="font-medium">{{ ucfirst($item->type) }}</span>:
                                        {{ $item->title }}
                                        @if(!empty($item->location))
                                            — {{ $item->location }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @if($itinerary->items->count() > 3)
                                <p class="text-xs text-ink-500 dark:text-ink-400 mt-1 italic">…and more</p>
                            @endif
                        @else
                            <p class="text-sm text-ink-500 dark:text-ink-400 mt-2 italic">No items yet.</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-ink-500 dark:text-sand-100 italic">You don’t have any itineraries yet.</p>
                @endforelse
            </div>

            {{-- Preference Profiles Summary --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-forest/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-forest" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">Preference Profiles</h3>
                </div>

                @forelse ($preferenceProfiles as $profile)
                    <div class="mb-5 pb-4 border-b border-sand-200 dark:border-ink-700 last:border-0 last:mb-0 last:pb-0">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div>
                                <p class="font-semibold text-ink-900 dark:text-ink-100 text-lg">{{ $profile->name }}</p>
                                <p class="text-sm text-ink-500 dark:text-sand-100">
                                    {{ $profile->preferences->count() }} Preferences
                                </p>
                            </div>
                            <a href="{{ route('traveler.preference-profiles.show', $profile) }}"
                               class="group inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-forest text-forest font-medium text-sm
                                      hover:bg-forest hover:text-white hover:shadow-glow transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-ink-500 dark:text-sand-100 italic">You don’t have any preference profiles yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
