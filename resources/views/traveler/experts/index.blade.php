<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Local Experts') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- Intro Card --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Discover Local Experts
                </p>
                <p class="text-sm text-ink-600 dark:text-sand-200 mt-1">
                    Connect with experienced guides who know their cities inside and out.
                    Search, sort by popularity or newest, and filter by one or more cities.
                </p>
            </div>

            {{-- Controls (Single GET form) --}}
            <form method="GET"
                  class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft p-6
                         flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                {{-- Search --}}
                <div class="w-full sm:w-1/3">
                    <label for="q" class="block text-sm font-medium text-ink-700 dark:text-sand-200 mb-1">
                        {{ __('Search (name, city, bio)') }}
                    </label>
                    <div class="relative">
                        <input type="text" id="q" name="q" value="{{ $q }}"
                               placeholder="{{ __('Search experts…') }}"
                               class="w-full rounded-xl px-4 py-2.5 pr-10 bg-white dark:bg-sand-900
                                      border border-sand-300 dark:border-ink-700
                                      text-ink-800 dark:text-sand-100
                                      focus:ring focus:ring-copper/30 focus:border-copper transition" />
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
                        {{ __('Sort') }}
                    </label>
                    <select name="sort" id="sort"
                            class="w-full rounded-xl px-4 py-2.5 bg-white dark:bg-sand-900
                                   border border-sand-300 dark:border-ink-700
                                   text-ink-800 dark:text-sand-100
                                   focus:ring focus:ring-copper/30 focus:border-copper transition
                                   min-w-[220px] sm:min-w-[260px]">
                        <option value="popularity" {{ $sort === 'popularity' ? 'selected' : '' }}>
                            {{ __('Most Popular') }}
                        </option>
                        <option value="alphabetical" {{ $sort === 'alphabetical' ? 'selected' : '' }}>
                            {{ __('Alphabetical (A–Z)') }}
                        </option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>
                            {{ __('Newest Experts First') }}
                        </option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="w-full sm:w-auto flex gap-3 sm:self-end">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                   hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                        {{ __('Apply') }}
                    </button>

                    @if ($q || !empty($cities) || ($sort && $sort !== 'popularity'))
                        <a href="{{ route('traveler.experts') }}"
                           class="px-5 py-2.5 rounded-full border border-sand-300 dark:border-ink-700
                                  text-ink-700 dark:text-sand-100 bg-white dark:bg-sand-900
                                  hover:bg-sand-100 dark:hover:bg-ink-700 shadow-soft transition-all">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </div>
            </form>

            {{-- Experts Grid --}}
            @if($experts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                    @foreach($experts as $expert)
                        <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl
                                    p-6 shadow-soft hover:shadow-glow hover:scale-[1.02] transition-all duration-200">

                            {{-- Photo --}}
                            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden mb-5 shadow-sm">
                                <img src="{{ $expert->photo_url ?? asset('data/images/placeholders/expert-avatar.png') }}"
                                     alt="{{ $expert->name }}"
                                     class="w-full h-full object-cover">
                            </div>

                            {{-- Name --}}
                            <h3 class="text-center text-xl font-semibold text-ink-900 dark:text-ink-100">
                                {{ $expert->name }}
                            </h3>

                            {{-- City --}}
                            <p class="text-center text-sm text-ink-600 dark:text-sand-200 mt-1">
                                {{ $expert->city }}
                            </p>

                            {{-- Reviews --}}
                            <p class="text-center text-sm text-copper font-semibold mt-2">
                                {{ $expert->reviews_count }} {{ Str::plural('review', $expert->reviews_count) }}
                            </p>

                            {{-- Bio (short) --}}
                            @if($expert->bio)
                                <p class="mt-3 text-sm text-ink-700 dark:text-sand-100 line-clamp-3 text-center">
                                    {{ $expert->bio }}
                                </p>
                            @endif

                            {{-- Button --}}
                            <div class="mt-6 text-center">
                                <button
                                    class="px-4 py-1.5 rounded-full border border-copper text-copper
                                           hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                                           transition-all duration-200 text-sm font-medium">
                                    {{ __('View Profile') }}
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $experts->onEachSide(1)->links() }}
                </div>

            @else
                {{-- Empty State --}}
                <div class="bg-white dark:bg-sand-800 rounded-3xl p-12 text-center shadow-soft border border-sand-200 dark:border-ink-700">
                    <p class="text-lg font-medium text-ink-700 dark:text-sand-100">
                        {{ __('No experts match your filters.') }}
                    </p>
                    <p class="text-sm text-ink-500 dark:text-sand-300 mt-1">
                        {{ __('Try clearing filters or searching differently.') }}
                    </p>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
