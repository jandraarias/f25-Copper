<x-app-layout x-data>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r from-copper-100/60 to-transparent dark:from-copper-900/20 rounded-2xl shadow-soft">
            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                </svg>
                {{ $itinerary->name }}
            </h2>

            <a href="{{ route('traveler.itineraries.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper 
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03] 
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    {{-- MAIN BODY --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-10">

            {{-- AI Generation Info --}}
            @if ($itinerary->preferenceProfile)
                <div class="p-5 rounded-2xl bg-gradient-to-br from-sand-100 to-sand-50 dark:from-sand-800 dark:to-sand-900 border border-sand-200 dark:border-ink-700 shadow-soft">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 18a9 9 0 100-18 9 9 0 000 18z" />
                        </svg>
                        <p class="text-sm text-ink-800 dark:text-sand-200">
                            This itinerary was generated using your
                            <strong>{{ $itinerary->preferenceProfile->name }}</strong>
                            for <strong>{{ $itinerary->address ?? 'the selected city' }}</strong>.
                            You can make adjustments manually or regenerate it below.
                        </p>
                    </div>
                </div>
            @endif

            {{-- OVERVIEW CARD --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.01] transition-all duration-300">
                <div class="p-8 sm:p-10 text-ink-900 dark:text-ink-100">
                    <h3 class="text-2xl font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4zM4 9h16"/>
                        </svg>
                        Overview
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-y-4 gap-x-8 text-ink-800 dark:text-ink-200 leading-relaxed">
                        <p><span class="font-semibold">Description:</span> @linkify($itinerary->description ?? '—')</p>
                        <p><span class="font-semibold">Countries:</span> {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}</p>
                        <p><span class="font-semibold">City:</span> {{ $itinerary->address ?? '—' }}</p>
                        <p><span class="font-semibold">Preference Profile:</span> {{ $itinerary->preferenceProfile->name ?? '—' }}</p>
                        <p><span class="font-semibold">Dates:</span>
                            {{ $itinerary->start_date?->format('M j, Y') ?? '—' }}
                            –
                            {{ $itinerary->end_date?->format('M j, Y') ?? '—' }}
                        </p>
                    </div>

                    <div class="mt-10 flex flex-wrap gap-4 justify-end">
                        <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                           class="group flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft 
                                  hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:rotate-6 transition-transform duration-200"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h2M12 19v2M12 3v2M15 9h.01M9 9h.01" />
                            </svg>
                            Edit Itinerary
                        </a>

                        @if (Auth::id() === optional($itinerary->traveler->user)->id)
                            <form method="POST" action="{{ route('traveler.itineraries.generate', $itinerary) }}">
                                @csrf
                                <button type="submit"
                                        class="group flex items-center justify-center gap-2 px-6 py-2.5 rounded-full border border-copper text-copper 
                                               hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03] 
                                               transition-all duration-200 ease-out font-semibold shadow-soft">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:rotate-[15deg] transition-transform duration-200"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M20 4l-6 6M4 20l6-6" />
                                    </svg>
                                    Regenerate Itinerary
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PLANNED ACTIVITIES --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <h3 class="text-2xl font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-copper" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                        </svg>
                        Planned Activities
                    </h3>

                    @php
                        $grouped = $itinerary->items->groupBy(fn($item) =>
                            optional($item->start_time)
                                ? \Illuminate\Support\Carbon::parse($item->start_time)->format('Y-m-d')
                                : 'unscheduled'
                        );
                    @endphp

                    @if ($grouped->isEmpty())
                        <div class="text-center py-10 text-ink-600 dark:text-sand-100">
                            <p class="text-lg">No itinerary items yet.</p>
                            <p class="text-sm mt-2">You can regenerate or add items manually.</p>
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach ($grouped as $date => $items)
                                <div x-data="{ open: true }" class="rounded-2xl border border-sand-200 dark:border-ink-700 shadow-sm">
                                    {{-- Day Header --}}
                                    <button @click="open = !open"
                                            class="w-full flex items-center justify-between px-6 py-4 bg-sand-50 dark:bg-sand-900/50 
                                                   text-copper-700 dark:text-copper-300 font-semibold text-lg rounded-t-2xl 
                                                   hover:bg-sand-100 dark:hover:bg-sand-800 transition-all">
                                        <span>
                                            {{ $date === 'unscheduled'
                                                ? 'Unscheduled Items'
                                                : \Illuminate\Support\Carbon::parse($date)->format('l, M j, Y') }}
                                        </span>
                                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                                        </svg>
                                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- Items Grid --}}
                                    <div x-show="open" x-collapse class="p-6 bg-white dark:bg-sand-800 rounded-b-2xl">
                                        <div class="grid sm:grid-cols-2 gap-8">
                                            @foreach ($items as $item)
                                                @include('traveler.itineraries.partials.item-row-display', ['item' => $item])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- COLLABORATORS --}}
            @if ($itinerary->isCollaborative() && ($itinerary->collaborators->isNotEmpty() || $itinerary->invitations->isNotEmpty()))
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft p-8">
                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100 mb-4">Collaborators</h3>
                    <div class="space-y-2 text-ink-800 dark:text-sand-200">
                        @foreach ($itinerary->collaborators as $collab)
                            <p>👥 {{ $collab->name }} <span class="text-sm text-ink-500">({{ $collab->email }})</span></p>
                        @endforeach
                        @foreach ($itinerary->invitations as $invite)
                            <p>📨 {{ $invite->email }} <span class="text-sm text-ink-500">({{ ucfirst($invite->status) }})</span></p>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
