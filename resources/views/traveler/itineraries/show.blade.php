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

    {{-- ALPINE ROOT FOR PAGE STATE --}}
    <div x-data="{ showCollaboratorsModal: false, showDisableConfirm: false }">
    

        {{-- MAIN BODY --}}
        <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-10">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-900 shadow-soft">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50 text-amber-900 shadow-soft">
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-2xl border border-red-200 bg-red-50 text-red-900 shadow-soft">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- AI Generation Info --}}
                @if ($itinerary->preferenceProfile)
                    <div class="p-5 rounded-2xl bg-gradient-to-br from-sand-100 to-sand-50 dark:from-sand-800 dark:to-sand-900 border border-sand-200 dark:border-ink-700 shadow-soft">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M12 18a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                            <p class="text-sm text-ink-800 dark:text-sand-100">
                                This itinerary was generated using your
                                <strong>{{ $itinerary->preferenceProfile->name }}</strong>
                                for <strong>{{ $itinerary->location ?? 'the selected city' }}</strong>.
                                You can make adjustments manually or regenerate it below.
                            </p>
                        </div>
                    </div>
                @endif

                
                {{-- Leaflet CSS --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
                {{-- Itinerary Map --}}
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
                    console.log("Initializing map for itinerary", itineraryId);
                    
                    const map = L.map(el).setView([37.2702, -76.7075], 13);

                    // Add OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    // Fetch Itinerary places
                    fetch(`/traveler/itineraries/${itineraryId}/places`)
                        .then(response => response.json())
                        .then(places => {
                            console.log("Loaded places:", places);
                            if(!places.length) return;

                            const markers = [];

                            places.forEach(place => {
                                if(place.lat && place.lon) {
                                    const popupContent = `
                                        <strong>${place.name}</strong><br>
                                        ${place.description ?? ''}<br>
                                        Distance from Williamsburg: ${place.distance_from_williamsburg} miles
                                        `;
                                    
                                        const marker = L.marker([place.lat, place.lon])
                                        .addTo(map)
                                        .bindPopup(popupContent);

                                markers.push(marker);
                            }
                        });

                        if(markers.length) {
                            const group = L.featureGroup(markers);
                            map.fitBounds(group.getBounds().pad(0.2));
                        }
                    })
                    .catch(err => console.error('Error loading places:', err));
                }
            </script>

            {{-- Leaflet JS --}}
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
                            <p><span class="font-semibold">Description:</span> @linkify($itinerary->description ?? '—')</p>
                            <p><span class="font-semibold">Countries:</span> {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}</p>
                            <p><span class="font-semibold">City:</span> {{ $itinerary->location ?? '—' }}</p>
                            <p><span class="font-semibold">Preference Profile:</span> {{ $itinerary->preferenceProfile->name ?? '—' }}</p>
                            @php
                                $acceptedExpertInvite = $itinerary->expertInvitations->firstWhere('status', 'accepted');
                                $pendingExpertInvite = $itinerary->expertInvitations->firstWhere('status', 'pending');
                            @endphp
                            <p>
                                <span class="font-semibold">Assigned Local Expert:</span>
                                @if ($acceptedExpertInvite)
                                    <a href="{{ route('traveler.experts.show', $acceptedExpertInvite->expert) }}"
                                       class="text-copper hover:underline font-semibold">
                                        {{ $acceptedExpertInvite->expert->name ?? '—' }}
                                    </a>
                                @elseif ($pendingExpertInvite)
                                    Pending Approval
                                @else
                                    —
                                @endif
                            </p>
                            <p><span class="font-semibold">Dates:</span>
                                {{ $itinerary->start_date?->format('M j, Y') ?? '—' }}
                                –
                                {{ $itinerary->end_date?->format('M j, Y') ?? '—' }}
                            </p>
                            <p>
                                <span class="font-semibold">Collaboration:</span>
                                @if ($itinerary->isCollaborative())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Enabled
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-sand-100 text-ink-600 dark:bg-sand-800 dark:text-ink-200">
                                        Disabled
                                    </span>
                                @endif
                            </p>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="mt-10 flex flex-wrap gap-4 justify-end">

                            {{-- Edit --}}
                            <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                               class="group flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft 
                                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:rotate-6 transition-transform duration-200"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h2M12 19v2M12 3v2M15 9h.01M9 9h.01" />
                                </svg>
                                Edit Itinerary
                            </a>

                            {{-- View Expert Suggestions --}}
                            @if ($itinerary->expertInvitations()->where('status', 'accepted')->exists())
                                <a href="{{ route('traveler.itineraries.suggestions.index', $itinerary) }}"
                                   class="group flex items-center justify-center gap-2 px-6 py-2.5 rounded-full border border-blue-400 text-blue-700 dark:text-blue-200
                                          hover:bg-blue-600 hover:text-white hover:shadow-glow hover:scale-[1.03]
                                          transition-all duration-200 ease-out font-semibold shadow-soft dark:border-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Expert Suggestions
                                </a>
                            @endif

                            @if (Auth::id() === optional($itinerary->traveler->user)->id)
                                {{-- Collaboration Toggle + Manage --}}

                                {{-- Enable button (only when OFF) --}}
                                @if (! $itinerary->isCollaborative())
                                    <form method="POST" action="{{ route('traveler.itineraries.enable-collaboration', $itinerary) }}">
                                        @csrf
                                        <button type="submit"
                                                class="group flex items-center justify-center gap-2 
                                                       px-6 py-2.5 rounded-full border border-copper text-copper
                                                       hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                                                       transition-all duration-200 ease-out font-semibold shadow-soft">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M17 20h5v-2a3 3 0 00-3-3h-2m-6 5H3v-2a3 3 0 013-3h2m0-5a4 4 0 118 0v1H8v-1zm4 4v4" />
                                            </svg>
                                            Enable Collaboration
                                        </button>
                                    </form>
                                @endif

                                {{-- Manage + Disable (only when ON) --}}
                                @if ($itinerary->isCollaborative())
                                    <button type="button"
                                            @click="showCollaboratorsModal = true"
                                            class="group flex items-center justify-center gap-2 
                                                   px-6 py-2.5 rounded-full border border-ink-400 text-ink-700 dark:text-ink-100
                                                   hover:border-copper hover:text-copper hover:shadow-glow hover:scale-[1.03]
                                                   transition-all duration-200 ease-out font-semibold shadow-soft">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M17 20h5v-2a3 3 0 00-3-3h-2m-6 5H3v-2a3 3 0 013-3h2m0-5a4 4 0 118 0v1H8v-1zm4 4v4" />
                                        </svg>
                                        Manage Collaborators
                                    </button>

                                    <button type="button"
                                        @click="showDisableConfirm = true"
                                        class="group flex items-center justify-center gap-2 
                                            px-6 py-2.5 rounded-full
                                            bg-red-50 text-red-700 border border-red-300
                                            dark:bg-red-900/30 dark:text-red-300 dark:border-red-700
                                            hover:bg-red-600 hover:text-white hover:border-red-600
                                            dark:hover:bg-red-600 dark:hover:text-white dark:hover:border-red-600
                                            hover:shadow-glow hover:scale-[1.03]
                                            transition-all duration-200 ease-out font-semibold shadow-soft">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Disable Collaboration
                                </button>
                                @endif
                            @endif

                            {{-- Regenerate --}}
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

                {{-- COLLABORATORS SUMMARY CARD --}}
                @if ($itinerary->isCollaborative() && ($itinerary->collaborators->isNotEmpty() || $itinerary->invitations->isNotEmpty()))
                    <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft p-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">Collaborators</h3>
                            @if (Auth::id() === optional($itinerary->traveler->user)->id)
                                <button type="button"
                                        @click="showCollaboratorsModal = true"
                                        class="text-sm font-medium text-copper hover:text-copper-600 dark:hover:text-copper-300 underline-offset-2 hover:underline">
                                    Manage
                                </button>
                            @endif
                        </div>

                        <div class="space-y-2 text-ink-800 dark:text-sand-100">
                            @foreach ($itinerary->collaborators as $collab)
                                <p>
                                    👥 {{ $collab->name }}
                                    <span class="text-sm text-ink-500">({{ $collab->email }})</span>
                                </p>
                            @endforeach
                            @foreach ($itinerary->invitations as $invite)
                                <p>
                                    📨 {{ $invite->email }}
                                    <span class="text-sm text-ink-500">({{ ucfirst($invite->status) }})</span>
                                </p>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- MANAGE COLLABORATORS MODAL --}}
        @if (Auth::id() === optional($itinerary->traveler->user)->id && $itinerary->isCollaborative())
            <div x-cloak
                 x-show="showCollaboratorsModal"
                 x-transition.opacity.duration.200ms
                 @keydown.escape.window="showCollaboratorsModal = false"
                 @click.self="showCollaboratorsModal = false"
                 class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                <div
                    @click.stop
                    x-transition.scale.duration.200ms
                    class="w-full max-w-lg mx-4 rounded-3xl bg-white dark:bg-sand-900 border border-sand-200 dark:border-ink-700 shadow-glow p-6">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-ink-900 dark:text-sand-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-3-3h-2m-6 5H3v-2a3 3 0 013-3h2m0-5a4 4 0 118 0v1H8v-1zm4 4v4" />
                                </svg>
                                Manage Collaborators
                            </h2>
                            <p class="text-xs text-ink-500 dark:text-ink-300 mt-1">
                                Invite other Copper travelers to co-edit this itinerary and see current collaborators.
                            </p>
                        </div>
                        <button type="button"
                                @click="showCollaboratorsModal = false"
                                class="text-ink-400 hover:text-ink-700 dark:hover:text-ink-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Current Collaborators & Invites --}}
                    <div class="space-y-3 mb-6">
                        <h3 class="text-sm font-semibold text-ink-700 dark:text-sand-100">
                            Current collaborators
                        </h3>

                        @if ($itinerary->collaborators->isEmpty() && $itinerary->invitations->isEmpty())
                            <p class="text-sm text-ink-500 dark:text-ink-300">
                                No collaborators yet. Invite someone below to get started.
                            </p>
                        @else
                            <div class="space-y-2 text-sm text-ink-800 dark:text-sand-100">
                                @foreach ($itinerary->collaborators as $collab)
                                    <div class="flex items-center justify-between gap-3 rounded-2xl bg-sand-50 dark:bg-sand-800 px-3 py-2">
                                        <div>
                                            <p class="font-medium">{{ $collab->name }}</p>
                                            <p class="text-xs text-ink-500">{{ $collab->email }}</p>
                                        </div>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200">
                                            Joined
                                        </span>
                                    </div>
                                @endforeach
                                @foreach ($itinerary->invitations as $invite)
                                    <div class="flex items-center justify-between gap-3 rounded-2xl bg-sand-50 dark:bg-sand-800 px-3 py-2">
                                        <div>
                                            <p class="font-medium">{{ $invite->email }}</p>
                                            <p class="text-xs text-ink-500">
                                                Invitation {{ strtolower($invite->status) }}
                                            </p>
                                        </div>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
                                            Pending
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Invite Form --}}
                    <div class="border-t border-sand-200 dark:border-ink-700 pt-4">
                        <h3 class="text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">
                            Invite a collaborator
                        </h3>
                        <form method="POST" action="{{ route('traveler.itineraries.invite', $itinerary) }}" class="space-y-3">
                            @csrf
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="email"
                                       name="email"
                                       required
                                       placeholder="friend@example.com"
                                       class="flex-1 border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                              px-4 py-2.5 dark:bg-sand-900 text-sm" />
                                <button type="submit"
                                        class="px-4 py-2.5 rounded-full bg-gradient-copper text-white text-sm font-semibold shadow-soft
                                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                    Send Invite
                                </button>
                            </div>
                            <p class="text-xs text-ink-500 dark:text-ink-300">
                                We’ll email them an invitation link to join this itinerary.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- DISABLE COLLABORATION CONFIRM MODAL --}}
        @if (Auth::id() === optional($itinerary->traveler->user)->id)
            <div
                x-cloak
                x-show="showDisableConfirm"
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/40 backdrop-blur-sm"
                @keydown.escape.window="showDisableConfirm = false"
                @click.self="showDisableConfirm = false"
            >
                {{-- Modal Panel --}}
                <div
                    @click.stop
                    x-transition.scale.duration.200ms
                    class="relative z-[1000] w-full max-w-md mx-4 rounded-3xl bg-white dark:bg-sand-900 border border-red-200/80 dark:border-red-800/60 shadow-glow p-6"
                >
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-ink-900 dark:text-sand-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 00-7.07 17.07L19.07 4.93A9.96 9.96 0 0012 2z" />
                                </svg>
                                Disable collaboration?
                            </h2>
                            <p class="text-xs text-ink-500 dark:text-ink-300 mt-1">
                                This will remove all collaborators and cancel any pending invitations.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="showDisableConfirm = false"
                            class="text-ink-400 hover:text-ink-700 dark:hover:text-ink-100"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button"
                                @click="showDisableConfirm = false"
                                class="px-4 py-2.5 rounded-full border border-sand-300 text-ink-700 dark:text-ink-100 text-sm
                                       hover:border-ink-500 hover:text-ink-900 dark:hover:text-sand-50 transition-all duration-200 ease-out">
                            Cancel
                        </button>

                        <form method="POST" action="{{ route('traveler.itineraries.disable-collaboration', $itinerary) }}">
                            @csrf
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-full bg-red-600 text-white text-sm font-semibold shadow-soft
                                           hover:bg-red-700 hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                Yes, disable collaboration
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
     
</x-app-layout>
