<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Your Explorer Rewards') }}
            </h2>

            <a href="{{ route('traveler.itineraries.create') }}"
               class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                + New Itinerary
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- Intro --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">

                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Your Travel Achievements
                </p>
                <p class="text-sm text-ink-500 dark:text-sand-100 mt-1">
                    These rewards celebrate your journeys, discoveries, and exploring spirit.
                </p>
            </div>


            {{-- Rewards Grid --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11.7 2.3a1 1 0 011.6 0l2.1 3.8 4.3.6a1 1 0 01.6 1.7l-3.1 3 0.7 4.2a1 1 0 01-1.5 1l-3.8-2-3.8 2a1 1 0 01-1.5-1l0.7-4.2-3.1-3a1 1 0 01.6-1.7l4.3-.6 2.1-3.8z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
                        Earned Rewards
                    </h3>
                </div>

                @if($rewards->isEmpty())
                    <p class="text-sm text-ink-500 dark:text-ink-300 italic">
                        You haven’t earned any rewards yet — keep exploring!
                    </p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        @foreach($rewards as $reward)
                            <div x-data="{ showModal: false }"
                                 class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                                        p-6 flex flex-col items-center text-center transition-all duration-200 ease-out
                                        hover:shadow-glow hover:scale-[1.02]">

                                {{-- Icon --}}
                                <div class="w-16 h-16 mb-4 flex items-center justify-center rounded-full bg-copper/10">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-8 h-8 text-copper" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11.7 2.3a1 1 0 011.6 0l2.1 3.8 4.3.6a1 1 0 01.6 1.7l-3.1 3 0.7 4.2a1 1 0 01-1.5 1l-3.8-2-3.8 2a1 1 0 01-1.5-1l0.7-4.2-3.1-3a1 1 0 01.6-1.7l4.3-.6 2.1-3.8z" />
                                    </svg>
                                </div>

                                {{-- Title --}}
                                <h4 class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                                    {{ $reward->title }}
                                </h4>

                                {{-- Description --}}
                                <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                    {{ $reward->description }}
                                </p>

                                {{-- Place --}}
                                @if($reward->place)
                                    <p class="text-xs text-ink-500 dark:text-ink-400 mt-3 italic">
                                        Related place:
                                        <span class="font-medium">{{ $reward->place->name }}</span>
                                    </p>
                                @endif

                                {{-- Button: Add to Itinerary --}}
                                <button @click="showModal = true"
                                        class="mt-5 px-4 py-2 text-sm font-semibold rounded-full border border-copper text-copper
                                               hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200">
                                    Add to Itinerary
                                </button>


                                {{-- Modal --}}
                                <div x-show="showModal"
                                     x-cloak
                                     @keydown.escape.window="showModal = false"
                                     class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 backdrop-blur-sm">

                                    <div @click.away="showModal = false"
                                         class="bg-white dark:bg-sand-900 rounded-2xl shadow-glow w-full max-w-md p-6
                                                border border-sand-200 dark:border-ink-700 transition">

                                        <h3 class="text-lg font-semibold text-ink-900 dark:text-sand-100 mb-3">
                                            Apply Reward to Itinerary
                                        </h3>

                                        <p class="text-sm text-ink-600 dark:text-ink-300 mb-4">
                                            Choose an itinerary to apply:
                                            <strong>{{ $reward->title }}</strong>
                                        </p>

                                        <form method="POST"
                                              action="{{ route('traveler.rewards.apply', $reward) }}">
                                            @csrf

                                            <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-2">
                                                Select Itinerary
                                            </label>

                                            <select name="itinerary_id"
                                                    required
                                                    class="w-full border border-sand-300 dark:border-ink-700 rounded-xl
                                                           px-3 py-2 text-sm dark:bg-sand-800 focus:ring-copper focus:border-copper">
                                                @foreach($itineraries as $itinerary)
                                                    <option value="{{ $itinerary->id }}">
                                                        {{ $itinerary->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button"
                                                        @click="showModal = false"
                                                        class="px-4 py-2 rounded-full text-sm border border-sand-300
                                                               dark:border-ink-700 text-ink-600 dark:text-ink-300 hover:bg-sand-100
                                                               dark:hover:bg-ink-800 transition">
                                                    Cancel
                                                </button>

                                                <button type="submit"
                                                        class="px-5 py-2 rounded-full bg-gradient-copper text-white text-sm font-semibold
                                                               hover:shadow-glow hover:scale-[1.03] transition">
                                                    Apply Reward
                                                </button>
                                            </div>
                                        </form>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
