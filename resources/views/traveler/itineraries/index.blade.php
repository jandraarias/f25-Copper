<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('My Itineraries') }}
            </h2>

            <a href="{{ route('traveler.itineraries.create') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold
                      shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Itinerary
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- PAGE INTRO CARD --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 
                        border border-sand-200 dark:border-ink-700
                        transition-all duration-200 hover:shadow-glow hover:scale-[1.01]">
                
                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Your Itinerary Library
                </p>
                <p class="text-sm text-ink-600 dark:text-sand-100 mt-1 leading-relaxed">
                    All your journeys, plans, and adventures — organized in one place.
                </p>

                {{-- SMART INSIGHT --}}
                @php
                    $nextTrip = $itineraries
                        ->filter(fn($i) => $i->start_date && \Carbon\Carbon::parse($i->start_date)->isFuture())
                        ->sortBy('start_date')
                        ->first();
                @endphp

                @if ($nextTrip)
                    <p class="mt-4 text-sm text-ink-500 dark:text-sand-200 italic">
                        Your next trip starts on 
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse($nextTrip->start_date)->format('M j, Y') }}
                        </span>.
                    </p>
                @elseif ($itineraries->count())
                    <p class="mt-4 text-sm text-ink-500 dark:text-sand-200 italic">
                        You don't have any upcoming trips — maybe it’s time to plan one?
                    </p>
                @endif
            </div>

            {{-- FILTERS + SEARCH --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="flex gap-2">
                    {{-- ALL --}}
                    <a href="{{ route('traveler.itineraries.index') }}"
                       class="px-4 py-2 rounded-full text-sm font-medium border 
                              {{ request('filter') === null 
                                  ? 'bg-copper text-white border-copper shadow-soft'
                                  : 'border-sand-300 dark:border-ink-700
                                     text-ink-700 dark:text-sand-100 
                                     hover:bg-sand-100 dark:hover:bg-ink-700' }}">
                        All
                    </a>

                    {{-- UPCOMING --}}
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'upcoming']) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium border 
                              {{ request('filter') === 'upcoming' 
                                  ? 'bg-copper text-white border-copper shadow-soft'
                                  : 'border-sand-300 dark:border-ink-700
                                     text-ink-700 dark:text-sand-100 
                                     hover:bg-sand-100 dark:hover:bg-ink-700' }}">
                        Upcoming
                    </a>

                    {{-- PAST --}}
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'past']) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium border 
                              {{ request('filter') === 'past' 
                                  ? 'bg-copper text-white border-copper shadow-soft'
                                  : 'border-sand-300 dark:border-ink-700
                                     text-ink-700 dark:text-sand-100 
                                     hover:bg-sand-100 dark:hover:bg-ink-700' }}">
                        Past
                    </a>
                </div>

                {{-- SEARCH --}}
                <form method="GET" action="{{ route('traveler.itineraries.index') }}" class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search itineraries…"
                           class="px-4 py-2.5 w-64 rounded-full border border-sand-300 dark:border-ink-600
                                  bg-white dark:bg-sand-800 text-ink-800 dark:text-sand-200 shadow-sm
                                  placeholder:text-ink-400 dark:placeholder:text-ink-600
                                  focus:ring focus:ring-copper/30 focus:border-copper transition">

                    <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-copper hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- TABLE CARD --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                        rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.005] 
                        transition-all duration-200 ease-out">

                <div class="p-8">

                    @if($itineraries->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-sand-200 dark:divide-ink-700">
                                <thead class="bg-sand dark:bg-sand-900/40">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase tracking-wide">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase tracking-wide">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase tracking-wide">Countries</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase tracking-wide">Destination</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase tracking-wide">Dates</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase tracking-wide">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-sand-800 divide-y divide-sand-200 dark:divide-ink-700">
                                    @foreach($itineraries as $itinerary)
                                        <tr class="hover:bg-sand-50 dark:hover:bg-sand-900/50 transition duration-150 ease-out">

                                            {{-- ID --}}
                                            <td class="px-4 py-3 font-medium text-ink-700 dark:text-ink-200 whitespace-nowrap">
                                                #{{ $itinerary->id }}
                                            </td>

                                            {{-- NAME --}}
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-ink-900 dark:text-ink-100 text-base">
                                                    {{ $itinerary->name }}
                                                </div>
                                            </td>

                                            {{-- COUNTRIES --}}
                                            <td class="px-4 py-3 text-ink-700 dark:text-ink-200">
                                                {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}
                                            </td>

                                            {{-- DESTINATION (FIXED) --}}
                                            <td class="px-4 py-3 text-ink-700 dark:text-ink-200">
                                                {{ $itinerary->destination ?: ($itinerary->location ?: '—') }}
                                            </td>

                                            {{-- DATES --}}
                                            <td class="px-4 py-3 text-ink-700 dark:text-ink-200">
                                                @php
                                                    $sd = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M j, Y') : null;
                                                    $ed = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M j, Y') : null;
                                                @endphp

                                                {{ $sd ?? '—' }} @if($sd || $ed) — @endif {{ $ed ?? '—' }}
                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                <div class="flex items-center gap-2 justify-end">

                                                    {{-- VIEW --}}
                                                    <a href="{{ route('traveler.itineraries.show', $itinerary) }}"
                                                       class="px-3 py-1.5 rounded-full border border-ink-500
                                                              text-ink-700 dark:text-sand-100 text-sm
                                                              hover:border-copper hover:text-copper
                                                              hover:shadow-glow hover:scale-[1.03]
                                                              transition duration-200 ease-out">
                                                        View
                                                    </a>

                                                    {{-- EDIT --}}
                                                    <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                                                       class="px-3 py-1.5 rounded-full border border-copper
                                                              text-copper text-sm
                                                              hover:bg-copper hover:text-white
                                                              hover:shadow-glow hover:scale-[1.03]
                                                              transition duration-200 ease-out">
                                                        Edit
                                                    </a>

                                                    {{-- DELETE --}}
                                                    <form method="POST"
                                                          action="{{ route('traveler.itineraries.destroy', $itinerary) }}"
                                                          onsubmit="return confirm('Delete this itinerary?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-3 py-1.5 rounded-full border border-red-400
                                                                       text-red-500 text-sm
                                                                       hover:bg-red-500 hover:text-white
                                                                       hover:shadow-glow hover:scale-[1.03]
                                                                       transition duration-200 ease-out">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="mt-8">
                            {{ $itineraries->links() }}
                        </div>

                    @else
                        {{-- EMPTY STATE --}}
                        <div class="py-12 text-center text-ink-600 dark:text-sand-100">
                            <p class="text-lg font-medium mb-4">You don’t have any itineraries yet.</p>
                            <a href="{{ route('traveler.itineraries.create') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold
                                      shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                + Create Your First Itinerary
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
