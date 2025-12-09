<!-- resources/views/expert/itineraries/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ $itinerary->name }} — Edit & Suggest
            </h2>

            <a href="{{ route('expert.itineraries.show', $itinerary) }}"
               class="px-4 py-2 rounded-full bg-sand-200 dark:bg-ink-700 text-ink-800 dark:text-sand-100 text-sm
                      hover:bg-sand-300 dark:hover:bg-ink-600 shadow-soft transition">
                ← Back to View
            </a>
        </div>
    </x-slot>

    @php
        $start = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M d, Y') : '—';
        $end   = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M d, Y') : '—';
        $travelerName = optional($itinerary->traveler?->user)->name ?? 'Unknown Traveler';
    @endphp

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <x-flash-messages />

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

            {{-- ================= Itinerary Items with Edit Options ================= --}}
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
                        Itinerary Activities — Make Suggestions
                    </h3>
                </div>

                <p class="text-sm text-ink-600 dark:text-ink-300 mb-6">
                    Click on any activity below to suggest a replacement from existing places or submit a new place suggestion.
                </p>

                @forelse($itinerary->items->groupBy(function ($item) {
                    return $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('Y-m-d') : 'unscheduled';
                }) as $date => $items)
                    <div class="mb-8 pb-6 border-b border-sand-200 dark:border-ink-700 last:border-none last:pb-0">

                        <h4 class="text-lg font-semibold text-ink-900 dark:text-ink-100 mb-4">
                            @if($date !== 'unscheduled')
                                {{ \Carbon\Carbon::parse($date)->format('l, M d') }}
                            @else
                                Unscheduled Activities
                            @endif
                        </h4>

                        <div class="space-y-4">
                            @foreach($items as $item)
                                <div x-data="itemEditor({{ $item->id }})" class="p-4 rounded-xl bg-sand-100 dark:bg-ink-800 border border-sand-200 dark:border-ink-700
                                            shadow-sm">

                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="font-semibold text-ink-900 dark:text-ink-100">
                                                {{ ucfirst($item->type) }} — {{ $item->title }}
                                            </p>

                                            @if($item->location)
                                                <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                                    📍 {{ $item->location }}
                                                </p>
                                            @endif

                                            @if($item->place && $item->place->rating)
                                                <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                                    ⭐ {{ $item->place->rating }} ({{ $item->place->num_reviews ?? 0 }} reviews)
                                                </p>
                                            @endif

                                            @if($item->start_time)
                                                <p class="text-xs text-ink-500 dark:text-ink-400 mt-2">
                                                    🕐 {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}
                                                    @if($item->end_time)
                                                        - {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Edit Button --}}
                                        <button @click="toggleEditor()"
                                                class="px-3 py-1.5 text-sm rounded-lg border border-copper text-copper font-medium
                                                       hover:bg-copper hover:text-white transition duration-200">
                                            Suggest Edit
                                        </button>
                                    </div>

                                    {{-- Existing Suggestions --}}
                                    @if($item->expertSuggestions->count() > 0)
                                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                            <p class="text-xs font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                                Pending Suggestions ({{ $item->expertSuggestions->count() }})
                                            </p>
                                            <div class="space-y-2">
                                                @foreach($item->expertSuggestions as $suggestion)
                                                    <div class="text-xs text-blue-700 dark:text-blue-300 p-2 bg-white dark:bg-ink-800 rounded">
                                                        @if($suggestion->type === 'replacement' && $suggestion->place)
                                                            <strong>{{ $suggestion->place->name }}</strong> — {{ $suggestion->place->address ?? $suggestion->place->location }}
                                                        @elseif($suggestion->placeSuggestion)
                                                            <strong>{{ $suggestion->placeSuggestion->name }}</strong> (New suggestion)
                                                        @endif
                                                        @if($suggestion->reason)
                                                            <p class="mt-1 italic">"{{ $suggestion->reason }}"</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Suggestion Form (Hidden by default) --}}
                                    <div x-show="showEditor" x-transition class="mt-4 pt-4 border-t border-sand-300 dark:border-ink-700">
                                        <div class="space-y-4">
                                            {{-- Tab Selector --}}
                                            <div class="flex gap-2 mb-4">
                                                <button @click="activeTab = 'existing'"
                                                        :class="{
                                                            'bg-copper text-white': activeTab === 'existing',
                                                            'bg-sand-200 dark:bg-ink-700 text-ink-800 dark:text-sand-100': activeTab !== 'existing'
                                                        }"
                                                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                                                    Search Existing Places
                                                </button>
                                                <button @click="activeTab = 'new'"
                                                        :class="{
                                                            'bg-copper text-white': activeTab === 'new',
                                                            'bg-sand-200 dark:bg-ink-700 text-ink-800 dark:text-sand-100': activeTab !== 'new'
                                                        }"
                                                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                                                    Suggest New Place
                                                </button>
                                            </div>

                                            {{-- Existing Places Search --}}
                                            <div x-show="activeTab === 'existing'" x-transition class="space-y-3">
                                                <input x-model="searchQuery"
                                                       @input="searchPlaces()"
                                                       type="text"
                                                       placeholder="Search for places by name, location..."
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">

                                                <div x-show="searchResults.length > 0" class="space-y-2 max-h-64 overflow-y-auto">
                                                    <template x-for="place in searchResults" :key="place.id">
                                                        <div class="p-3 bg-white dark:bg-ink-800 border border-sand-200 dark:border-ink-700 rounded-lg cursor-pointer
                                                                    hover:shadow-md transition"
                                                             @click="selectPlace(place)">
                                                            <p class="font-medium text-ink-900 dark:text-sand-100" x-text="place.name"></p>
                                                            <p class="text-sm text-ink-600 dark:text-ink-300" x-text="place.address"></p>
                                                            <p class="text-xs text-copper mt-1">
                                                                <span x-show="place.rating">⭐ <span x-text="place.rating"></span></span>
                                                                <span x-show="place.num_reviews"> (<span x-text="place.num_reviews"></span> reviews)</span>
                                                            </p>
                                                        </div>
                                                    </template>
                                                </div>

                                                <div x-show="searched && searchResults.length === 0" class="p-3 text-sm text-ink-500 dark:text-ink-400">
                                                    No places found. Try a different search term.
                                                </div>

                                                {{-- Selected Place --}}
                                                <div x-show="selectedPlace" x-transition class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                                    <p class="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">Selected Place</p>
                                                    <p class="font-medium text-ink-900 dark:text-sand-100" x-text="selectedPlace.name"></p>
                                                    <button @click="selectedPlace = null" type="button" class="text-xs text-green-600 dark:text-green-300 mt-2 underline">
                                                        Clear Selection
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- New Place Suggestion --}}
                                            <div x-show="activeTab === 'new'" x-transition class="space-y-3">
                                                <input x-model="newPlace.name"
                                                       type="text"
                                                       placeholder="Place Name *"
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">

                                                <input x-model="newPlace.description"
                                                       type="text"
                                                       placeholder="Description"
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">

                                                <input x-model="newPlace.location"
                                                       type="text"
                                                       placeholder="Location / Address"
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">

                                                <select x-model="newPlace.type"
                                                        class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                               bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                               focus:outline-none focus:ring-2 focus:ring-copper">
                                                    <option value="">Type (Activity/Food/etc)</option>
                                                    <option value="activity">Activity</option>
                                                    <option value="food">Food & Dining</option>
                                                    <option value="accommodation">Accommodation</option>
                                                    <option value="transport">Transport</option>
                                                </select>

                                                <input x-model="newPlace.rating"
                                                       type="number"
                                                       placeholder="Rating (0-5)"
                                                       min="0"
                                                       max="5"
                                                       step="0.1"
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">

                                                <input x-model="newPlace.phone"
                                                       type="text"
                                                       placeholder="Phone (optional)"
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">

                                                <input x-model="newPlace.website"
                                                       type="url"
                                                       placeholder="Website (optional)"
                                                       class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                              bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                              placeholder-ink-400 dark:placeholder-ink-500
                                                              focus:outline-none focus:ring-2 focus:ring-copper">
                                            </div>

                                            {{-- Reason / Notes --}}
                                            <textarea x-model="reason"
                                                      placeholder="Why are you suggesting this change? (optional)"
                                                      class="w-full px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600
                                                             bg-white dark:bg-ink-800 text-ink-900 dark:text-sand-100
                                                             placeholder-ink-400 dark:placeholder-ink-500
                                                             focus:outline-none focus:ring-2 focus:ring-copper
                                                             h-20 resize-none"></textarea>

                                            {{-- Submit Buttons --}}
                                            <div class="flex gap-3">
                                                <button @click="submitSuggestion({{ $item->id }})"
                                                        :disabled="isSubmitting"
                                                        class="flex-1 px-4 py-2 rounded-lg bg-gradient-copper text-white font-medium
                                                               hover:shadow-glow hover:scale-[1.02] transition disabled:opacity-50
                                                               focus:outline-none focus:ring-2 focus:ring-copper focus:ring-offset-2">
                                                    <span x-show="!isSubmitting">Submit Suggestion</span>
                                                    <span x-show="isSubmitting">Submitting...</span>
                                                </button>

                                                <button @click="toggleEditor()"
                                                        type="button"
                                                        class="flex-1 px-4 py-2 rounded-lg border border-sand-300 dark:border-ink-600 text-ink-900 dark:text-sand-100
                                                               font-medium hover:bg-sand-100 dark:hover:bg-ink-700 transition">
                                                    Cancel
                                                </button>
                                            </div>

                                            <div x-show="submissionStatus" x-transition
                                                 :class="{
                                                     'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800': submissionStatus.success,
                                                     'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800': !submissionStatus.success
                                                 }"
                                                 class="p-3 rounded-lg border text-sm"
                                                 x-text="submissionStatus.message">
                                            </div>
                                        </div>
                                    </div>
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

            {{-- View Traveler's Decisions --}}
            <div class="bg-gradient-copper/10 dark:bg-copper/5 border border-copper/20 rounded-3xl p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-ink-900 dark:text-ink-100 mb-2">
                            View Suggestion Status
                        </h3>
                        <p class="text-sm text-ink-600 dark:text-ink-300">
                            Check which of your suggestions have been approved or rejected by the traveler.
                        </p>
                    </div>
                    <a href="{{ route('expert.itineraries.show', $itinerary) }}"
                       class="px-6 py-2.5 rounded-full bg-copper text-white font-semibold text-sm
                              hover:shadow-glow hover:scale-[1.03] transition">
                        View Status →
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Alpine Component Script --}}
    <script>
        function itemEditor(itemId) {
            return {
                showEditor: false,
                activeTab: 'existing',
                searchQuery: '',
                searchResults: [],
                searched: false,
                selectedPlace: null,
                searchTimeout: null,
                newPlace: {
                    name: '',
                    description: '',
                    location: '',
                    type: '',
                    rating: '',
                    phone: '',
                    website: '',
                },
                reason: '',
                isSubmitting: false,
                submissionStatus: null,

                toggleEditor() {
                    this.showEditor = !this.showEditor;
                    if (!this.showEditor) {
                        this.resetForm();
                    }
                },

                async searchPlaces() {
                    // Debounce the search
                    clearTimeout(this.searchTimeout);
                    
                    if (!this.searchQuery.trim()) {
                        this.searchResults = [];
                        this.searched = false;
                        return;
                    }

                    this.searchTimeout = setTimeout(async () => {
                        try {
                            const url = '{{ route('expert.search-places') }}?query=' + encodeURIComponent(this.searchQuery);
                            console.log('Searching places with URL:', url);
                            
                            const response = await fetch(url);
                            const data = await response.json();

                            console.log('Search response:', data);

                            if (data.ok && data.results) {
                                this.searchResults = data.results;
                                this.searched = true;
                                console.log('Found results:', data.results.length);
                            } else {
                                console.warn('No results or error:', data);
                                this.searchResults = [];
                                this.searched = true;
                            }
                        } catch (error) {
                            console.error('Search error:', error);
                            this.searchResults = [];
                            this.searched = true;
                        }
                    }, 300); // Wait 300ms after user stops typing
                },

                selectPlace(place) {
                    this.selectedPlace = place;
                    this.activeTab = 'existing';
                },

                async submitSuggestion(itemIdVal) {
                    if (this.isSubmitting) return;

                    // Validation
                    if (this.activeTab === 'existing' && !this.selectedPlace) {
                        this.submissionStatus = {
                            success: false,
                            message: 'Please select a place.',
                        };
                        return;
                    }

                    if (this.activeTab === 'new' && !this.newPlace.name.trim()) {
                        this.submissionStatus = {
                            success: false,
                            message: 'Please enter a place name.',
                        };
                        return;
                    }

                    this.isSubmitting = true;

                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('reason', this.reason);

                    if (this.activeTab === 'existing' && this.selectedPlace) {
                        formData.append('place_id', this.selectedPlace.id);
                    } else if (this.activeTab === 'new') {
                        formData.append('new_place[name]', this.newPlace.name);
                        formData.append('new_place[description]', this.newPlace.description);
                        formData.append('new_place[location]', this.newPlace.location);
                        formData.append('new_place[type]', this.newPlace.type);
                        formData.append('new_place[rating]', this.newPlace.rating);
                        formData.append('new_place[phone]', this.newPlace.phone);
                        formData.append('new_place[website]', this.newPlace.website);
                    }

                    const itineraryId = {{ $itinerary->id }};
                    const suggestionUrl = `{{ route('expert.itineraries.suggest-replacement', ['itinerary' => $itinerary->id]) }}`.replace('{{ $itinerary->id }}', itineraryId) + '?item_id=' + itemIdVal;

                    try {
                        const response = await fetch(suggestionUrl, {
                            method: 'POST',
                            body: formData,
                        });

                        const data = await response.json();

                        if (data.ok) {
                            this.submissionStatus = {
                                success: true,
                                message: data.message,
                            };
                            setTimeout(() => {
                                this.toggleEditor();
                                location.reload();
                            }, 1500);
                        } else {
                            this.submissionStatus = {
                                success: false,
                                message: data.message || 'Error submitting suggestion.',
                            };
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                        this.submissionStatus = {
                            success: false,
                            message: 'Network error. Please try again.',
                        };
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                resetForm() {
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.searched = false;
                    this.selectedPlace = null;
                    this.newPlace = {
                        name: '',
                        description: '',
                        location: '',
                        type: '',
                        rating: '',
                        phone: '',
                        website: '',
                    };
                    this.reason = '';
                    this.submissionStatus = null;
                },
            };
        }
    </script>
</x-app-layout>
