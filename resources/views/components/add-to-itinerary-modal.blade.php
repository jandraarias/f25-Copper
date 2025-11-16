@props(['place', 'itineraries'])

<div x-data="{ open: false }">

    {{-- BUTTON --}}
    <button @click="open = true"
        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
        Add to Itinerary
    </button>

    {{-- OVERLAY --}}
    <div x-show="open"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-40"
         @click="open = false">
    </div>

    {{-- MODAL BOX --}}
    <div x-show="open"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div @click.stop
             class="w-full max-w-md bg-white dark:bg-sand-800 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700 p-8">

            <h2 class="text-xl font-bold text-ink-900 dark:text-sand-100 mb-6">
                Add <span class="text-copper">{{ $place->name }}</span> to an Itinerary
            </h2>

            <form method="POST"
                  action="{{ route('traveler.places.add-to-itinerary', $place) }}"
                  class="space-y-6">
                @csrf

                {{-- SELECT ITINERARY --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 dark:text-sand-200 mb-1">
                        Choose an itinerary
                    </label>

                    <select name="itinerary_id"
                        class="w-full rounded-xl border border-sand-300 dark:border-ink-700
                               bg-sand-50 dark:bg-ink-800 text-ink-900 dark:text-sand-100
                               px-4 py-2.5 shadow-inner focus:ring-copper focus:border-copper">
                        @foreach ($itineraries as $it)
                            <option value="{{ $it->id }}">
                                {{ $it->name }} ({{ $it->start_date?->format('M j') }} – {{ $it->end_date?->format('M j') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3">
                    <button type="button"
                            @click="open = false"
                            class="px-4 py-2 text-ink-700 dark:text-sand-200 hover:underline">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                   hover:shadow-glow hover:scale-[1.03] transition-all">
                        Add
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
