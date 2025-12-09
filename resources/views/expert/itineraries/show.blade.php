<!-- resources/views/expert/itineraries/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ $itinerary->name }}
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('expert.itineraries.edit', $itinerary) }}"
                   class="px-4 py-2 rounded-full bg-copper text-white text-sm font-medium
                          hover:shadow-glow hover:scale-[1.03] transition shadow-soft">
                    ✏️ Edit & Suggest
                </a>

                <a href="{{ route('expert.itineraries.index') }}"
                   class="px-4 py-2 rounded-full bg-sand-200 dark:bg-ink-700 text-ink-800 dark:text-sand-100 text-sm
                          hover:bg-sand-300 dark:hover:bg-ink-600 shadow-soft transition">
                    ← Back to Itineraries
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $start = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M d, Y') : '—';
        $end   = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M d, Y') : '—';
        $travelerName = optional($itinerary->traveler?->user)->name ?? 'Unknown Traveler';
    @endphp

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- ================= Summary Card ================= --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                        rounded-3xl shadow-soft p-8 transition hover:shadow-glow hover:scale-[1.01]">

                <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100 mb-4">
                    Itinerary Overview
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">

                    <div>
                        <p class="text-ink-500 dark:text-ink-300">Traveler</p>
                        <p class="font-semibold text-ink-900 dark:text-ink-100">
                            {{ $travelerName }}
                        </p>
                    </div>

                    <div>
                        <p class="text-ink-500 dark:text-ink-300">Start Date</p>
                        <p class="font-semibold text-ink-900 dark:text-ink-100">
                            {{ $start }}
                        </p>
                    </div>

                    <div>
                        <p class="text-ink-500 dark:text-ink-300">End Date</p>
                        <p class="font-semibold text-ink-900 dark:text-ink-100">
                            {{ $end }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ================= Itinerary Items ================= --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                        rounded-3xl shadow-soft p-8 transition hover:shadow-glow hover:scale-[1.01]">

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
                        Itinerary Details
                    </h3>
                </div>

                @forelse($itinerary->items->groupBy('day') as $day => $items)
                    <div class="mb-8 pb-6 border-b border-sand-200 dark:border-ink-700 last:border-none last:pb-0">

                        <h4 class="text-lg font-semibold text-ink-900 dark:text-ink-100 mb-3">
                            Day {{ $day }}
                        </h4>

                        <div class="space-y-4">

                            @foreach($items as $item)
                                <div class="p-4 rounded-xl bg-sand-100 dark:bg-ink-800 border border-sand-200 dark:border-ink-700
                                            shadow-sm flex justify-between items-start">

                                    <div>
                                        <p class="font-semibold text-ink-900 dark:text-ink-100">
                                            {{ ucfirst($item->type) }} — {{ $item->title }}
                                        </p>

                                        @if($item->location)
                                            <p class="text-sm text-ink-600 dark:text-ink-300">
                                                {{ $item->location }}
                                            </p>
                                        @endif

                                        @if($item->notes)
                                            <p class="text-xs text-ink-500 dark:text-ink-400 mt-2 italic">
                                                "{{ $item->notes }}"
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Future: Add expert tag, comment, approve button --}}
                                </div>
                            @endforeach

                        </div>
                    </div>

                @empty
                    <p class="text-sm text-ink-500 dark:text-ink-300 italic">
                        This itinerary has no items yet.
                    </p>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
