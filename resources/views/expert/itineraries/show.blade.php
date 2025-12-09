<!-- resources/views/expert/itineraries/show.blade.php -->

<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r from-copper-100/60 to-transparent dark:from-copper-900/20 rounded-2xl shadow-soft">
            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                </svg>
                {{ $itinerary->name }}
            </h2>

            <a href="{{ route('expert.itineraries.index') }}"
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

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-10">

            {{-- MAP SECTION --}}
            <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
            <div id="itinerary-map"
                 data-itinerary-id="{{ $itinerary->id }}"
                 x-data
                 x-init="initItineraryMap()"
                 style="width:100%; height:400px;"
                 class="w-full rounded-2xl shadow-soft mb-8">
            </div>

            <script>
                function initItineraryMap() {
                    const el = document.getElementById('itinerary-map');
                    const itineraryId = el.dataset.itineraryId;

                    const map = L.map(el).setView([37.2702, -76.7075], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    fetch(`/traveler/itineraries/${itineraryId}/places`)
                        .then(response => response.json())
                        .then(places => {
                            if (!places.length) return;
                            const markers = [];

                            places.forEach(place => {
                                if (place.lat && place.lon) {
                                    const popupContent = `
                                        <strong>${place.name}</strong><br>
                                        ${place.description ?? ''}
                                    `;
                                    const marker = L.marker([place.lat, place.lon])
                                        .addTo(map)
                                        .bindPopup(popupContent);
                                    markers.push(marker);
                                }
                            });

                            if (markers.length) {
                                const group = L.featureGroup(markers);
                                map.fitBounds(group.getBounds().pad(0.2));
                            }
                        })
                        .catch(err => console.error('Error loading places:', err));
                }
            </script>
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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
                        <p><span class="font-semibold">Traveler:</span> {{ $itinerary->traveler->user->name ?? '—' }}</p>
                        <p><span class="font-semibold">Countries:</span> {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}</p>
                        <p><span class="font-semibold">City:</span> {{ $itinerary->location ?? '—' }}</p>
                        <p><span class="font-semibold">Dates:</span>
                            {{ $itinerary->start_date?->format('M j, Y') ?? '—' }}
                            –
                            {{ $itinerary->end_date?->format('M j, Y') ?? '—' }}
                        </p>
                        <p><span class="font-semibold">Preference Profile:</span> {{ $itinerary->preferenceProfile->name ?? '—' }}</p>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="mt-10 flex flex-wrap gap-4 justify-end">
                        {{-- Edit --}}
                        <a href="{{ route('expert.itineraries.edit', $itinerary) }}"
                           class="group flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft 
                                  hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:rotate-6 transition-transform duration-200"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.232 5.232a3 3 0 114.243 4.243L7.5
                                         21H3v-4.5l12.232-11.268z"/>
                            </svg>
                            Edit Itinerary
                        </a>
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
                            <p class="text-sm mt-2">You can add or edit items manually.</p>
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
        </div>
    </div>
</x-app-layout>
