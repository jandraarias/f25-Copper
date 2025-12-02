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
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-10">

            {{-- PROFILE OVERVIEW CARD --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                        rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.01] 
                        transition-all duration-300">

                <div class="p-8 sm:p-10 text-ink-900 dark:text-ink-100">

                    {{-- Image + Info Row --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-8">

                        {{-- Profile Photo --}}
                        <div>
                            <img src="{{ $expert->photo_url ?: 'https://via.placeholder.com/200' }}"
                                 class="w-40 h-40 rounded-3xl object-cover shadow-lg border border-sand-300 dark:border-ink-700"
                                 alt="Expert photo" />
                        </div>

                        {{-- Main Info --}}
                        <div class="flex-1 space-y-3">

                            <h3 class="text-3xl font-bold flex items-center gap-2">
                                {{ auth()->user()->name }}
                            </h3>

                            <p class="text-lg text-ink-700 dark:text-sand-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $expert->city ?: 'No city selected' }}
                            </p>

                            <p class="text-md leading-relaxed text-ink-700 dark:text-sand-200">
                                {{ $expert->bio ?: 'No bio added yet.' }}
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            {{-- REVIEWS SECTION --}}
            @if($expert->reviews && $expert->reviews->count())
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                            rounded-3xl shadow-soft hover:shadow-glow transition-all duration-300">

                    <div class="p-8 sm:p-10">
                        <h3 class="text-2xl font-bold mb-6 flex items-center gap-2 text-ink-900 dark:text-ink-100">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="w-6 h-6 text-copper" fill="none" viewBox="0 0 24 24" 
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.3 2.3a1 1 0 011.4 0l2 3.7 4.1.6a1 1 0 01.6 1.7l-3 2.9.7 4a1 1 0 01-1.5 1l-3.6-1.9-3.6 1.9a1 1 0 01-1.5-1l.7-4-3-2.9a1 1 0 01.6-1.7l4.1-.6 2-3.7z" />
                            </svg>
                            Reviews ({{ $expert->reviews->count() }})
                        </h3>

                        <div class="space-y-4">
                            @foreach ($expert->reviews as $review)
                                <div class="p-4 rounded-xl bg-sand-50 dark:bg-sand-900 border border-sand-200 dark:border-ink-700">
                                    <p class="text-sm text-ink-800 dark:text-sand-100 leading-relaxed">
                                        "{{ $review->content }}"
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
