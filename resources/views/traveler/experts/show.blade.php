{{-- resources/views/traveler/experts/show.blade.php --}}

<x-app-layout>

    {{-- =============================== HEADER =============================== --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-6 h-6 text-copper"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2-8 4v2h16v-2c0-2-3.582-4-8-4z" />
                </svg>
                {{ $expert->name }}
            </h2>

            <a href="{{ route('traveler.messages.show', $expert) }}"
               class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                Message Expert
            </a>
        </div>
    </x-slot>

    {{-- =============================== MAIN CONTENT =============================== --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-12">

            {{-- ========================== Overview Card ========================== --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                        rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.01]
                        transition-all duration-200 ease-out">

                <div class="p-8 sm:p-10 text-ink-900 dark:text-ink-100">

                    <h3 class="text-2xl font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6 text-copper" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 4h16v16H4zM4 9h16" />
                        </svg>
                        Expert Overview
                    </h3>

                    {{-- PHOTO + DETAILS --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-10">

                        {{-- Photo --}}
                        <div class="flex-shrink-0">
                            <img src="{{ $expert->profile_photo_url ?? asset('storage/images/defaults/expert.png') }}"
                                class="w-40 h-40 rounded-3xl object-cover shadow-lg
                                        border border-sand-300 dark:border-ink-700"
                                alt="{{ $expert->name }}" />
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 space-y-6">

                            {{-- Name --}}
                            <h3 class="text-3xl font-bold">
                                {{ $expert->name }}
                            </h3>

                            {{-- City --}}
                            <p class="text-lg flex items-center gap-2 text-ink-700 dark:text-sand-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-copper" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <strong class="font-semibold">Location:</strong>
                                {{ $expert->city ?: 'Location not provided' }}
                            </p>

                            {{-- Expertise --}}
                            <p class="text-md flex items-start gap-2 text-ink-700 dark:text-sand-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mt-1 text-copper" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6l4 2" />
                                </svg>
                                <span>
                                    <strong class="font-semibold">Expertise:</strong><br>
                                    {{ $expert->expertise ?: 'No expertise listed yet.' }}
                                </span>
                            </p>

                            {{-- Languages --}}
                            <p class="text-md flex items-start gap-2 text-ink-700 dark:text-sand-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mt-1 text-copper" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5h12M9 3v2m6 14h6m-3-2v2M3 19h12M5 7h8m-8 4h4" />
                                </svg>
                                <span>
                                    <strong class="font-semibold">Languages:</strong><br>
                                    {{ $expert->languages ?: 'No languages listed yet.' }}
                                </span>
                            </p>

                            {{-- Experience --}}
                            <p class="text-md flex items-center gap-2 text-ink-700 dark:text-sand-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-copper" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3M5 21h14M5 7h14M5 11h14M5 15h14" />
                                </svg>
                                <strong class="font-semibold">Experience:</strong>
                                {{ $expert->experience_years ? $expert->experience_years . ' years' : 'No experience listed' }}
                            </p>

                            {{-- Bio --}}
                            <div class="pt-2">
                                <p class="text-md leading-relaxed text-ink-700 dark:text-sand-200">
                                    <strong class="font-semibold">Bio:</strong><br>
                                    {{ $expert->bio ?: 'No bio available.' }}
                                </p>
                            </div>

                            {{-- Rate & Availability --}}
                            <div class="space-y-4 mt-8 px-4 py-3 rounded-2xl bg-sand-50 
                                        dark:bg-sand-900/40 border border-sand-200 dark:border-ink-700">

                                {{-- Rate --}}
                                <p class="text-lg flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="w-5 h-5 text-copper" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3m0 0c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 3v6m0-12v3" />
                                    </svg>

                                    <strong>Rate:</strong>

                                    @if($expert->hourly_rate)
                                        <span class="text-ink-800 dark:text-sand-100">
                                            ${{ number_format($expert->hourly_rate, 2) }}
                                        </span>
                                    @else
                                        <span class="text-ink-500 dark:text-sand-400 italic">Not set</span>
                                    @endif
                                </p>

                                {{-- Availability --}}
                                <p class="text-lg flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="w-5 h-5 text-copper mt-1" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3M5 21h14M5 7h14M5 11h14M5 15h14" />
                                    </svg>

                                    <strong>Availability:</strong>

                                    @if($expert->availability)
                                        <span class="text-ink-800 dark:text-sand-100 leading-relaxed">
                                            {{ $expert->availability }}
                                        </span>
                                    @else
                                        <span class="text-ink-500 dark:text-sand-400 italic">Not provided</span>
                                    @endif
                                </p>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- ========================== Add to Itinerary ========================== --}}
            @if($availableItineraries->isNotEmpty())
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                            rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.01]
                            transition-all duration-200 ease-out">

                    <div class="p-8 sm:p-10 text-ink-900 dark:text-ink-100">

                        <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-6 h-6 text-copper" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 4v16m8-8H4" />
                            </svg>
                            Add Expert to Itinerary
                        </h3>

                        <p class="text-sm text-ink-600 dark:text-ink-300 mb-6">
                            Request {{ $expert->name }} to collaborate on one of your existing itineraries.
                        </p>

                        <form method="POST" action="{{ route('traveler.itineraries.invite-expert', ['itinerary' => '__ITINERARY_ID__']) }}"
                              x-data="{ selectedItinerary: '' }"
                              @submit.prevent="if(selectedItinerary) { $el.action = $el.action.replace('__ITINERARY_ID__', selectedItinerary); $el.submit(); }">
                            @csrf
                            <input type="hidden" name="expert_id" value="{{ $expert->id }}">

                            <div class="flex flex-col sm:flex-row gap-4 items-end">
                                <div class="flex-1">
                                    <label for="itinerary_select" class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-2">
                                        Select Itinerary
                                    </label>
                                    <select id="itinerary_select" 
                                            x-model="selectedItinerary"
                                            required
                                            class="w-full rounded-xl px-4 py-2.5 bg-white dark:bg-sand-900
                                                   border border-sand-300 dark:border-ink-700 text-ink-800 dark:text-sand-100
                                                   focus:ring focus:ring-copper/30 focus:border-copper transition">
                                        <option value="">Choose an itinerary...</option>
                                        @foreach($availableItineraries as $itinerary)
                                            <option value="{{ $itinerary->id }}">
                                                {{ $itinerary->name }}
                                                @if($itinerary->location)
                                                    - {{ $itinerary->location }}
                                                @endif
                                                @if($itinerary->start_date)
                                                    ({{ $itinerary->start_date->format('M j, Y') }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit"
                                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out
                                               disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="!selectedItinerary">
                                    Send Request
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
