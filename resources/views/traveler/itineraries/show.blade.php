<x-app-layout x-data>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ $itinerary->name }}
            </h2>

            <a href="{{ route('traveler.itineraries.index') }}"
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
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-10">

            {{-- 🧠 AI Generation Info --}}
            @if ($itinerary->preferenceProfile)
                <div class="p-4 rounded-2xl bg-sand-100 dark:bg-sand-700 text-sm text-ink-700 dark:text-sand-200 border border-sand-300 dark:border-ink-600 shadow-soft">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper mt-0.5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 18a9 9 0 100-18 9 9 0 000 18z" />
                        </svg>
                        <p>
                            This itinerary was generated using your
                            <strong>{{ $itinerary->preferenceProfile->name }}</strong>
                            for <strong>{{ $itinerary->location ?? 'the selected city' }}</strong>.
                            You can make adjustments manually or regenerate it below.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Overview Card --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft 
                        hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <x-flash-messages />

                    <h3 class="text-xl font-semibold mb-6">Overview</h3>

                    <div class="space-y-3 text-ink-800 dark:text-ink-200">
                        <p><span class="font-semibold">Description:</span> {{ $itinerary->description ?? '—' }}</p>
                        <p><span class="font-semibold">Countries:</span> {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}</p>
                        <p><span class="font-semibold">City:</span> {{ $itinerary->location ?? '—' }}</p>
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

                        {{-- 🧠 Regenerate Button (only creator) --}}
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

            {{-- Planned Activities --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <h3 class="text-xl font-semibold mb-6">Planned Activities</h3>

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
                        <div class="space-y-10">
                            @foreach ($grouped as $date => $items)
                                <div>
                                    <h4 class="text-lg font-semibold text-copper mb-4">
                                        {{ $date === 'unscheduled' ? 'Unscheduled Items' : \Illuminate\Support\Carbon::parse($date)->format('l, M j, Y') }}
                                    </h4>

                                    <div class="space-y-3">
                                        @foreach ($items as $item)
                                            <div x-data="{ expanded: false }"
                                                 class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 
                                                        border border-sand-200 dark:border-ink-700 rounded-xl px-4 py-3 shadow-sm 
                                                        hover:shadow-glow hover:scale-[1.01] transition-all duration-200 
                                                        overflow-hidden break-words bg-white/70 dark:bg-sand-800/60">

                                                {{-- Left side: item info --}}
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-ink-900 dark:text-ink-100 break-words">
                                                        {{ ucfirst($item->type) }} — {{ $item->title }}
                                                    </p>

                                                    @if ($item->details)
                                                        <p x-bind:class="expanded ? 'line-clamp-none' : 'line-clamp-3'"
                                                           class="text-sm text-ink-600 dark:text-sand-200 mt-1 break-words transition-all duration-200 ease-in-out">
                                                            {{ $item->details }}
                                                        </p>

                                                        @if (Str::length($item->details) > 160)
                                                            <button type="button"
                                                                    @click="expanded = !expanded"
                                                                    class="mt-1 text-xs text-copper hover:underline focus:outline-none">
                                                                <span x-show="!expanded">Read more</span>
                                                                <span x-show="expanded">Show less</span>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>

                                                {{-- Right side: times --}}
                                                <div class="text-right text-sm text-ink-700 dark:text-sand-100 shrink-0">
                                                    <p>
                                                        {{ $item->start_time ? \Illuminate\Support\Carbon::parse($item->start_time)->format('g:ia') : '—' }}
                                                        –
                                                        {{ $item->end_time ? \Illuminate\Support\Carbon::parse($item->end_time)->format('g:ia') : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- 📋 Collaborators Section (optional but useful) --}}
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
