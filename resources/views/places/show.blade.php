<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-4 
                    bg-gradient-to-r from-copper-100/60 to-transparent 
                    dark:from-copper-900/20 rounded-2xl shadow-soft
                    max-w-6xl mx-auto">

            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 12m0 0l4.243-4.243M13.414 12H3" />
                </svg>

                {{ $place->name }}
            </h2>

            <a href="{{ url()->previous() }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>


    {{-- MAIN CONTENT --}}
    <div class="pb-24 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-14">

            {{-- HERO IMAGE WITH CLICK-TO-ENLARGE + LIGHTBOX --}}
            <div x-data="{ open: false }" class="relative rounded-3xl overflow-hidden shadow-soft hover:shadow-glow transition-all duration-300">

                {{-- Constrained preview image --}}
                <img 
                    src="{{ $place->photo_url ?? asset('img/placeholder.jpg') }}"
                    alt="{{ $place->name }} photo"
                    class="w-full max-h-[350px] object-cover cursor-pointer"
                    @click="open = true"
                >

                {{-- Fullscreen overlay modal --}}
                <div 
                    x-show="open"
                    x-cloak
                    x-transition.opacity
                    class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center"
                    style="backdrop-filter: blur(2px);"
                >
                    {{-- Close button --}}
                    <button 
                        @click="open = false"
                        class="absolute top-6 right-6 text-white hover:text-copper-400 text-3xl font-bold focus:outline-none"
                        aria-label="Close image"
                    >
                        &times;
                    </button>

                    {{-- Full image (click does NOT close modal) --}}
                    <img 
                        src="{{ $place->photo_url ?? asset('img/placeholder.jpg') }}"
                        alt="{{ $place->name }} full image"
                        class="max-w-[95vw] max-h-[90vh] rounded-xl shadow-2xl object-contain"
                    >
                </div>
            </div>


            {{-- MAIN INFO CARD --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                        shadow-soft rounded-3xl p-8 space-y-10">

                {{-- TITLE & QUICK FACTS --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-6">

                    <div>
                        <h1 class="text-3xl font-bold text-ink-900 dark:text-sand-100 mb-4">
                            {{ $place->name }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-3 text-ink-700 dark:text-sand-200">

                            {{-- Rating --}}
                            @if ($place->rating)
                                <div class="flex items-center gap-1 text-amber-500 font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-amber-400" viewBox="0 0 24 24">
                                        <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.853 1.516 8.276L12 18.897l-7.452 4.538L6.064 15.16 0 9.306l8.332-1.151z"/>
                                    </svg>
                                    {{ number_format($place->rating, 1) }}
                                </div>
                            @endif

                            {{-- Price --}}
                            @if ($place->price_level)
                                <span class="px-3 py-1 rounded-full bg-copper-100 text-copper-800
                                             dark:bg-copper-800 dark:text-copper-100 text-xs font-semibold">
                                    {{ str_repeat('$', $place->price_level) }}
                                </span>
                            @endif

                            {{-- Category --}}
                            @if ($place->main_category)
                                <span class="px-3 py-1 rounded-full bg-sand-200 dark:bg-ink-700
                                             text-xs font-semibold">
                                    {{ $place->main_category }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- ADD TO ITINERARY --}}
                    @auth
                        @if (auth()->user()->isTraveler())
                            <div class="shrink-0">
                                <x-add-to-itinerary-modal
                                    :place="$place"
                                    :itineraries="$itineraries"
                                />
                            </div>
                        @endif
                    @endauth

                </div>


                {{-- DESCRIPTION --}}
                @if ($place->description)
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-ink-800 dark:text-sand-100">About</h3>
                        <p class="text-ink-700 dark:text-sand-200 leading-relaxed text-base">
                            @linkify($place->description)
                        </p>
                    </div>
                @endif


                {{-- ADDRESS --}}
                @if ($place->address)
                    <div class="pt-4 border-t border-sand-200 dark:border-ink-700">
                        <h3 class="text-sm uppercase font-semibold text-copper-700 dark:text-copper-300 mb-1">Address</h3>

                        <p class="text-ink-800 dark:text-sand-100 mb-1 break-words">
                            @linkify($place->address)
                        </p>

                        @if ($place->meta['google_maps_url'] ?? false)
                            <a href="{{ $place->meta['google_maps_url'] }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                View on Google Maps
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif


                {{-- TAGS --}}
                @if (!empty($place->tags))
                    <div class="pt-4 border-t border-sand-200 dark:border-ink-700">
                        <h3 class="text-sm uppercase font-semibold text-copper-700 dark:text-copper-300 mb-2">Tags</h3>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($place->tags as $tag)
                                <span class="px-2 py-1 bg-sand-100 dark:bg-ink-700 rounded-full
                                             text-xs text-ink-700 dark:text-sand-200">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif


                {{-- PHONE / WEBSITE --}}
                <div class="grid sm:grid-cols-2 gap-10 pt-6 border-t border-sand-200 dark:border-ink-700">
                    @if ($place->phone)
                        <div>
                            <h4 class="font-semibold mb-1 text-ink-700 dark:text-sand-200">Phone</h4>
                            <p class="text-ink-900 dark:text-sand-100">@linkify($place->phone)</p>
                        </div>
                    @endif

                    @if ($place->meta['website'] ?? false)
                        <div>
                            <h4 class="font-semibold mb-1 text-ink-700 dark:text-sand-200">Website</h4>
                            <p>@linkify($place->meta['website'])</p>
                        </div>
                    @endif
                </div>
            </div>


            {{-- HOURS --}}
            <x-place-hours :hours="$place->meta['hours'] ?? null" />


            {{-- REVIEWS --}}
            @include('reviews._review-section', [
                'reviews' => $reviews
            ])

        </div>
    </div>

</x-app-layout>
