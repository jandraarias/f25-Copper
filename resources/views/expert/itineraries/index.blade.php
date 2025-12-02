<!-- resources/views/expert/itineraries/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Assigned Itineraries') }}
            </h2>
        </div>
    </x-slot>

    @php
        $itineraries = $itineraries ?? collect();
    @endphp

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- Summary / Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-sand-800 p-6 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700">
                    <p class="text-sm text-ink-500 dark:text-ink-300">Total Itineraries</p>
                    <p class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                        {{ $itineraries->count() }}
                    </p>
                </div>

                <div class="bg-white dark:bg-sand-800 p-6 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700">
                    <p class="text-sm text-ink-500 dark:text-ink-300">Upcoming Trips</p>
                    <p class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                        {{ $itineraries->filter(fn($i) => $i->start_date >= now())->count() }}
                    </p>
                </div>

                <div class="bg-white dark:bg-sand-800 p-6 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700">
                    <p class="text-sm text-ink-500 dark:text-ink-300">Past Trips</p>
                    <p class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                        {{ $itineraries->filter(fn($i) => $i->end_date < now())->count() }}
                    </p>
                </div>

            </div>

            {{-- ================ Itineraries List ================ --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.005]">

                <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100 mb-6">
                    Itineraries Assigned to You
                </h3>

                @forelse ($itineraries as $itinerary)

                    @php
                        $sd = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M d, Y') : '—';
                        $ed = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M d, Y') : '—';
                    @endphp

                    <div class="mb-6 pb-6 border-b border-sand-200 dark:border-ink-700 last:border-0 last:mb-0 last:pb-0">

                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

                            {{-- Left side --}}
                            <div>
                                <p class="font-semibold text-lg text-ink-900 dark:text-ink-100">
                                    {{ $itinerary->name }}
                                </p>

                                <p class="text-sm text-ink-500 dark:text-ink-300">
                                    {{ $sd }} → {{ $ed }}
                                </p>

                                <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                    Traveler:
                                    <span class="font-medium">
                                        {{ $itinerary->traveler->user->name ?? 'Unknown traveler' }}
                                    </span>
                                </p>
                            </div>

                            {{-- Right side --}}
                            <a href="#"
                               class="group inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-copper text-copper font-medium text-sm
                                      hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-4 h-4 transition-transform group-hover:translate-x-0.5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>

                        </div>

                        {{-- Preview of Top Items --}}
                        @if($itinerary->items?->count())
                            <ul class="list-disc ml-6 mt-3 text-sm text-ink-700 dark:text-sand-100">
                                @foreach ($itinerary->items->take(3) as $item)
                                    <li>
                                        <span class="font-medium">{{ ucfirst($item->type) }}</span>:
                                        {{ $item->title }}
                                        @if($item->location)
                                            — {{ $item->location }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            @if($itinerary->items->count() > 3)
                                <p class="text-xs text-ink-500 dark:text-ink-400 mt-1 italic">…and more</p>
                            @endif
                        @else
                            <p class="text-sm text-ink-500 dark:text-ink-400 mt-2 italic">
                                This itinerary has no items yet.
                            </p>
                        @endif

                    </div>

                @empty
                    <p class="italic text-ink-600 dark:text-ink-300">No itineraries assigned yet.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
