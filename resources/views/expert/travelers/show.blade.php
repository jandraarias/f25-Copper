<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2 
            bg-gradient-to-r from-copper-100/60 to-transparent 
            dark:from-copper-900/20 rounded-2xl shadow-soft">

            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-copper"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h4m-6 0H7m6 0v-2a4 4 0 00-4-4H9m4 6H5v-2a4 4 0 014-4h0" />
                </svg>
                Traveler Details
            </h2>

            <a href="{{ route('expert.travelers.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full 
                    border border-copper text-copper 
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



    {{-- MAIN CONTENT --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-12">

            {{-- Traveler Card --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                        rounded-3xl shadow-soft transition-all duration-300 hover:shadow-glow">

                <div class="p-10 text-ink-900 dark:text-ink-100">

                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row gap-10 items-start sm:items-center">

                        {{-- Photo --}}
                        <img src="{{ $traveler->user->profile_photo_url ?? asset('storage/images/defaults/traveler.png') }}"
                            class="w-44 h-44 rounded-3xl object-cover shadow-lg 
                                    border border-sand-300 dark:border-ink-700"
                            alt="Traveler photo" />

                        {{-- Details --}}
                        <div class="space-y-4 flex-1">

                            {{-- Name --}}
                            <h3 class="text-3xl font-bold">
                                {{ $traveler->user->name }}
                            </h3>

                            {{-- Email --}}
                            <p class="text-md flex items-center gap-2 text-ink-700 dark:text-sand-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 text-copper"
                                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 12H8m0 0l4 4m-4-4 4-4" />
                                </svg>
                                {{ $traveler->user->email }}
                            </p>

                            {{-- Phone --}}
                            @if($traveler->user->phone_number)
                                <p class="text-md flex items-center gap-2 text-ink-700 dark:text-sand-200">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5 text-copper"
                                         fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 5a2 2 0 012-2h2l2 5-2 1 4 7 4-2 1 2H7a2 2 0 01-2-2V5z" />
                                    </svg>
                                    {{ $traveler->user->phone_number }}
                                </p>
                            @endif

                            {{-- DOB --}}
                            @if($traveler->user->date_of_birth)
                                <p class="text-md text-ink-700 dark:text-sand-200">
                                    <strong>DOB:</strong>
                                    {{ $traveler->user->date_of_birth->format('M d, Y') }}
                                </p>
                            @endif

                            {{-- Last Active --}}
                            <p class="text-xs text-ink-500 dark:text-sand-300">
                                Last active: {{ $traveler->updated_at->diffForHumans() }}
                            </p>


                            {{-- Message Traveler Button --}}
                            <div class="pt-4">
                                <a href="{{ route('expert.messages.show', $traveler)
 }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full 
                                          bg-gradient-copper text-white font-semibold shadow-soft
                                          hover:shadow-glow hover:scale-[1.03] transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Message Traveler
                                </a>
                            </div>

                        </div>
                    </div>


                    {{-- Bio --}}
                    <div class="mt-10">
                        <h3 class="text-xl font-semibold mb-3">Bio</h3>
                        <p class="text-md leading-relaxed text-ink-700 dark:text-sand-200">
                            {{ $traveler->bio ?: 'No bio added yet.' }}
                        </p>
                    </div>

                </div>
            </div>


            {{-- Itineraries (Optional Section) --}}
            @if($traveler->itineraries->count())
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                            rounded-3xl shadow-soft p-10">

                    <h3 class="text-2xl font-bold mb-6 text-ink-900 dark:text-sand-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6 text-copper"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 4h16v16H4zM4 9h16" />
                        </svg>
                        Traveler Itineraries
                    </h3>

                    <div class="space-y-4">
                        @foreach ($traveler->itineraries as $itinerary)
                            <div class="p-5 rounded-2xl bg-sand-50 dark:bg-sand-900
                                        border border-sand-200 dark:border-ink-700 shadow-sm">
                                <p class="text-lg font-semibold text-ink-800 dark:text-sand-100">
                                    {{ $itinerary->title }}
                                </p>
                                <p class="text-sm text-ink-600 dark:text-sand-300">
                                    {{ $itinerary->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif

        </div>
    </div>

</x-app-layout>
