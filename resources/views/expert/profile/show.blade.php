<!-- resources/views/expert/profile/show.blade.php -->

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
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2-8 4v2h16v-2c0-2-3.582-4-8-4z" />
                </svg>
                {{ auth()->user()->name }} — Expert Profile
            </h2>

            <a href="{{ route('expert.profile.edit') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full 
                    border border-copper text-copper 
                    hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                    transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11 5h2M12 19v2M12 3v2M15 9h.01M9 9h.01" />
                </svg>
                Edit Profile
            </a>
        </div>
    </x-slot>



    {{-- MAIN CONTENT --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-12">


            {{-- PROFILE OVERVIEW --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                        rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.01]
                        transition-all duration-300">

                <div class="p-8 sm:p-10 text-ink-900 dark:text-ink-100">

                    <h3 class="text-2xl font-bold mb-8 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6 text-copper" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 4h16v16H4zM4 9h16" />
                        </svg>
                        Profile Overview
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
                                {{ auth()->user()->name }}
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
                                {{ $expert->city ?: 'No city selected' }}
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

                            {{-- Years of Experience --}}
                            <p class="text-md flex items-center gap-2 text-ink-700 dark:text-sand-200">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-copper" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3M5 21h14M5 7h14M5 11h14M5 15h14" />
                                </svg>
                                <strong class="font-semibold">Experience:</strong>
                                {{ $expert->experience_years !== null ? $expert->experience_years . ' years' : 'No experience listed' }}
                            </p>

                            {{-- Bio --}}
                            <div class="pt-2">
                                <p class="text-md leading-relaxed text-ink-700 dark:text-sand-200">
                                    <strong class="font-semibold">Bio:</strong><br>
                                    {{ $expert->bio ?: 'No bio added yet.' }}
                                </p>
                            </div>

                            {{-- Hourly Rate & Availability --}}
                            <div class="space-y-4 mt-8 px-4 py-3 rounded-2xl bg-sand-50 
                                        dark:bg-sand-900/40 border border-sand-200 dark:border-ink-700">

                                {{-- Hourly Rate --}}
                                <p class="text-lg flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="w-5 h-5 text-copper" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3m0 0c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 3v6m0-12v3" />
                                    </svg>

                                    <strong>Hourly Rate:</strong>

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

            {{-- REVIEWS --}}
            @if($expert->reviews && $expert->reviews->count())
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
                            rounded-3xl shadow-soft hover:shadow-glow transition-all duration-300">

                    <div class="p-8 sm:p-10">
                        <h3 class="text-2xl font-bold mb-6 flex items-center gap-2 text-ink-900 dark:text-ink-100">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-6 h-6 text-copper" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.3 2.3a1 1 0 011.4 0l2 3.7 4.1.6a1 1 0 01.6 1.7l-3 2.9.7 4a1 1 0 01-1.5 1l-3.6-1.9-3.6 1.9a1 1 0 01-1.5-1l.7-4-3-2.9a1 1 0 01.6-1.7l4.1-.6 2-3.7z" />
                            </svg>
                            Reviews ({{ $expert->reviews->count() }})
                        </h3>

                        <div class="space-y-4">
                            @foreach ($expert->reviews as $review)
                                <div class="p-5 rounded-2xl bg-sand-50 dark:bg-sand-900
                                            border border-sand-200 dark:border-ink-700 shadow-sm">

                                    <p class="text-sm text-ink-800 dark:text-sand-100 leading-relaxed">
                                        “{{ $review->content }}”
                                    </p>

                                    <p class="text-xs text-ink-500 dark:text-sand-300 mt-1">
                                        — Rated {{ $review->rating }}/5
                                    </p>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
