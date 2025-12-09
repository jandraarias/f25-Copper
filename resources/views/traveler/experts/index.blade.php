<x-app-layout>

    {{-- ========================= HEADER ========================= --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-sand-100 flex items-center gap-2">
                Local Experts
            </h2>

            {{-- Simple CTA for consistency --}}
            <a href="{{ route('traveler.messages.index') }}"
               class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                Inbox
            </a>
        </div>
    </x-slot>


    {{-- ========================= MAIN CONTENT ========================= --}}
    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- ========================= Intro Card ========================= --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Discover Local Experts
                </p>
                <p class="text-sm text-ink-600 dark:text-sand-200 mt-1">
                    Connect with knowledgeable local guides, ask questions, and get personalized insights
                    tailored to your travel plans.
                </p>
            </div>


            {{-- ========================= Search & Sort ========================= --}}
            <form method="GET"
                  class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                         rounded-3xl shadow-soft p-6 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">

                {{-- Search --}}
                <div class="w-full sm:w-1/3">
                    <label for="q" class="block text-sm font-medium text-ink-700 dark:text-sand-200 mb-1">
                        Search (name, city, bio)
                    </label>
                    <div class="relative">
                        <input type="text" id="q" name="q" value="{{ $q }}"
                               class="w-full rounded-xl px-4 py-2.5 pr-10 bg-white dark:bg-sand-900
                                      border border-sand-300 dark:border-ink-700 text-ink-800 dark:text-sand-100
                                      focus:ring focus:ring-copper/30 focus:border-copper transition"
                               placeholder="Search experts..." />
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-ink-500 dark:text-sand-300"
                             fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z" />
                        </svg>
                    </div>
                </div>

                {{-- Sort --}}
                <div class="w-full sm:w-1/4">
                    <label for="sort" class="block text-sm font-medium text-ink-700 dark:text-sand-200 mb-1">
                        Sort
                    </label>
                    <select id="sort" name="sort"
                            class="w-full rounded-xl px-4 py-2.5 bg-white dark:bg-sand-900
                                   border border-sand-300 dark:border-ink-700 text-ink-800 dark:text-sand-100
                                   focus:ring focus:ring-copper/30 focus:border-copper transition">
                        <option value="popularity" {{ $sort === 'popularity' ? 'selected' : '' }}>Most Popular</option>
                        <option value="alphabetical" {{ $sort === 'alphabetical' ? 'selected' : '' }}>A–Z</option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Experts</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="w-full sm:w-auto flex gap-3">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                   hover:shadow-glow hover:scale-[1.03] transition-all duration-200">
                        Apply
                    </button>

                    @if ($q || $sort !== 'popularity')
                        <a href="{{ route('traveler.experts.index') }}"
                           class="px-5 py-2.5 rounded-full border border-sand-300 dark:border-ink-700
                                  text-ink-700 dark:text-sand-100 bg-white dark:bg-sand-900
                                  hover:bg-sand-100 dark:hover:bg-ink-700 shadow-soft transition-all">
                            Clear
                        </a>
                    @endif
                </div>
            </form>


            {{-- ========================= Experts Grid ========================= --}}
            @if($experts->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                    @foreach($experts as $expert)
                        <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                                    rounded-3xl p-6 shadow-soft transition-all hover:shadow-glow hover:scale-[1.02]">

                            {{-- Avatar --}}
                            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden mb-5 shadow-soft 
                                        border border-sand-300 dark:border-ink-700">
                                <img src="{{ $expert->profile_photo_url ?? asset('storage/images/defaults/expert.png') }}"
                                    class="w-full h-full object-cover"
                                    alt="{{ $expert->name }}" />
                            </div>

                            {{-- Name --}}
                            <h3 class="text-center text-xl font-semibold text-ink-900 dark:text-sand-100">
                                {{ $expert->name }}
                            </h3>

                            {{-- City --}}
                            <p class="text-center text-sm text-ink-700 dark:text-sand-300 mt-1">
                                {{ $expert->city }}
                            </p>

                            {{-- Rate --}}
                            @if($expert->hourly_rate)
                                <p class="text-center text-sm text-copper font-semibold mt-2">
                                    Rate: ${{ number_format($expert->hourly_rate, 2) }}
                                </p>
                            @endif

                            {{-- Bio preview --}}
                            @if($expert->bio)
                                <p class="mt-3 text-sm text-ink-700 dark:text-sand-100 text-center line-clamp-3">
                                    {{ $expert->bio }}
                                </p>
                            @endif

                            <div class="mt-6 flex flex-col gap-3">

                                {{-- View Profile --}}
                                <a href="{{ route('traveler.experts.show', $expert) }}"
                                   class="block text-center px-4 py-2 rounded-full border border-copper text-copper
                                          hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                                          transition-all duration-200 text-sm font-medium">
                                    View Profile
                                </a>

                                {{-- Message --}}
                                <a href="{{ route('traveler.messages.show', $expert) }}"
                                   class="block text-center px-4 py-2 rounded-full bg-gradient-copper text-white
                                          font-semibold shadow-soft hover:shadow-glow hover:scale-[1.03]
                                          transition-all duration-200 text-sm">
                                    Message Expert
                                </a>

                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $experts->onEachSide(1)->links() }}
                </div>

            @else
                {{-- EMPTY STATE --}}
                <div class="bg-white dark:bg-sand-800 rounded-3xl p-12 text-center shadow-soft 
                            border border-sand-200 dark:border-ink-700">
                    <p class="text-lg font-medium text-ink-700 dark:text-sand-100">
                        No experts match your search filters.
                    </p>
                    <p class="text-sm text-ink-500 dark:text-sand-300 mt-1">
                        Try adjusting your filters or removing your search term.
                    </p>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
