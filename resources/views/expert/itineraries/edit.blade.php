{{-- resources/views/expert/itineraries/edit.blade.php --}}
<x-app-layout>

    {{-- ================= HEADER ================= --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ __('Edit Itinerary Items') }}
            </h2>

            <a href="{{ route('expert.itineraries.show', $itinerary) }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    {{-- ================= PAGE WRAPPER ================= --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen overflow-x-hidden"
         x-data="{ showProfile: false }">

        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-10">

            {{-- ================= FLASH MESSAGES ================= --}}
            <x-flash-messages />

            {{-- ================= ITINERARY INFO BOX ================= --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                        rounded-3xl shadow-soft p-8">
                <h3 class="text-xl font-semibold mb-4 text-ink-900 dark:text-ink-100">
                    Itinerary Information
                </h3>

                <div class="grid sm:grid-cols-2 gap-4 text-ink-700 dark:text-sand-100">
                    <p><strong>Name:</strong> {{ $itinerary->name }}</p>
                    <p><strong>Traveler:</strong> {{ optional($itinerary->traveler->user)->name }}</p>
                    <p><strong>Location:</strong> {{ $itinerary->location ?? '—' }}</p>
                    <p><strong>Dates:</strong>
                        {{ $itinerary->start_date?->format('M j, Y') ?? '—' }} –
                        {{ $itinerary->end_date?->format('M j, Y') ?? '—' }}
                    </p>
                </div>

                {{-- ============ Toggle Preference Profile ============ --}}
                @if ($itinerary->preferenceProfile)
                    <button
                        @click="showProfile = !showProfile"
                        class="mt-6 px-5 py-2.5 rounded-full border border-copper text-copper text-sm
                               hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200">
                        <span x-show="!showProfile">Show Preference Profile</span>
                        <span x-show="showProfile">Hide Preference Profile</span>
                    </button>

                    {{-- Collapsible Content --}}
                    <div x-show="showProfile" x-collapse class="mt-6">
                        @php
                            $profile = $itinerary->preferenceProfile;
                            $preferences = $profile->preferences;
                            $activities = $preferences->where('key', 'activity');
                            $budget = $preferences->where('key', 'budget')->first()?->value;
                            $dietary = $preferences->where('key', 'dietary')->pluck('value');
                            $cuisine = $preferences->where('key', 'cuisine')->pluck('value');
                        @endphp

                        <div class="space-y-6">
                            {{-- Overview --}}
                            <div class="bg-sand-50 dark:bg-sand-900 border border-sand-200 dark:border-ink-700
                                        rounded-2xl p-6 shadow-sm">
                                <h4 class="text-lg font-semibold text-copper mb-2">Preference Profile</h4>
                                <p class="text-ink-700 dark:text-sand-100">
                                    {{ $profile->name }}
                                </p>
                            </div>

                            {{-- Activities --}}
                            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-2xl p-6">
                                <h4 class="text-lg font-semibold text-ink-900 dark:text-sand-100 mb-2">Activities</h4>
                                @if ($activities->isEmpty())
                                    <p class="text-sm text-ink-600">No activities defined.</p>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($activities as $a)
                                            <span class="px-3 py-1 rounded-full bg-copper/20 text-copper text-sm">
                                                {{ $a->value }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Budget --}}
                            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-2xl p-6">
                                <h4 class="text-lg font-semibold">Budget</h4>
                                <p class="text-sm">{{ $budget ? ucfirst($budget) : '—' }}</p>
                            </div>

                            {{-- Dietary --}}
                            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-2xl p-6">
                                <h4 class="text-lg font-semibold">Dietary Preferences</h4>
                                @if ($dietary->isEmpty())
                                    <p class="text-sm">—</p>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($dietary as $d)
                                            <span class="px-3 py-1 rounded-full bg-forest/20 text-forest text-sm">
                                                {{ $d }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Cuisine --}}
                            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-2xl p-6">
                                <h4 class="text-lg font-semibold">Cuisine Preferences</h4>
                                @if ($cuisine->isEmpty())
                                    <p class="text-sm">—</p>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($cuisine as $c)
                                            <span class="px-3 py-1 rounded-full bg-rose-200 text-rose-700 text-sm">
                                                {{ $c }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ================= GROUPED ITEMS ================= --}}
            @php
                $grouped = $itinerary->items
                    ->sortBy('start_time')
                    ->groupBy(fn ($item) =>
                        $item->start_time
                            ? \Illuminate\Support\Carbon::parse($item->start_time)->format('Y-m-d')
                            : 'unscheduled'
                    );
            @endphp

            <div class="space-y-10">

                @foreach ($grouped as $date => $items)
                    <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                                rounded-3xl shadow-soft overflow-hidden" x-data="{ open: true }">

                        {{-- ====== Day Header ====== --}}
                        <button @click="open = !open"
                                class="w-full flex justify-between items-center px-6 py-4 bg-sand-50 dark:bg-sand-900/50
                                       text-lg font-semibold text-ink-900 dark:text-sand-100">
                            <span>
                                {{ $date === 'unscheduled'
                                    ? 'Unscheduled Items'
                                    : \Illuminate\Support\Carbon::parse($date)->format('l, F j, Y') }}
                            </span>
                            <svg x-bind:class="open ? 'rotate-180' : ''"
                                 class="w-5 h-5 transition-transform duration-300" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- ====== CARDS WRAPPER ====== --}}
                        <div x-show="open" x-collapse class="p-6 space-y-4">

                            {{-- Quick Add (collapsed toggle row, specific to this day) --}}
                            @include('expert.itineraries.item-quick-add', ['itinerary' => $itinerary])

                            {{-- Render existing items as collapsible cards (closed by default) --}}
                            @foreach ($items as $item)
                                @include('expert.itineraries.item-row', [
                                    'item' => $item,
                                    'itinerary' => $itinerary
                                ])
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>

    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3; /* <- add this */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

</x-app-layout>
