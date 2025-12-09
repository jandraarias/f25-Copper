{{-- 
    Rewards Sidebar Partial
    Variables passed from parent:
        $itinerary
        $closeVar  — Alpine variable name used to close the sidebar
--}}

<div class="flex flex-col h-full" x-data="{ 
        selecting: null, 
        selectedItemId: null 
    }">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-sand-200 dark:border-ink-700">
        <h2 class="text-xl font-semibold text-ink-900 dark:text-sand-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11.7 2.3a1 1 0 011.6 0l2.1 3.8 4.3.6a1 1 0 01.6 1.7l-3.1 3 .7 4.2a1 1 0 01-1.5 1l-3.8-2-3.8 2a1 1 0 01-1.5-1l.7-4.2-3.1-3a1 1 0 01.6-1.7l4.3-.6 2.1-3.8z" />
            </svg>
            Rewards
        </h2>

        <button
            @click="{{ $closeVar }} = false"
            class="text-ink-400 hover:text-ink-700 dark:hover:text-sand-200 transition"
            aria-label="Close rewards panel">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-6">

        {{-- Intro --}}
        <div class="bg-sand-50 dark:bg-sand-800/40 rounded-2xl p-4 border border-sand-200 dark:border-ink-700">
            <p class="text-sm text-ink-700 dark:text-sand-200">
                Choose a reward to apply to your itinerary.  
                You'll be able to replace one of your food-related items with the reward’s associated place.
            </p>
        </div>

        {{-- List of Rewards --}}
        @php
            // For now: show ALL rewards, like your RewardsController does
            $rewards = \App\Models\Reward::with('place')->get();
        @endphp

        @forelse ($rewards as $reward)
            <div class="rounded-2xl border border-sand-200 dark:border-ink-700 bg-white dark:bg-sand-800 p-5 shadow-soft
                        hover:shadow-glow hover:scale-[1.01] transition-all duration-200">

                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-ink-900 dark:text-sand-100">
                            {{ $reward->title }}
                        </h3>
                        <p class="text-sm text-ink-600 dark:text-sand-300 mt-1">
                            {{ $reward->description }}
                        </p>

                        @if ($reward->place)
                            <p class="text-xs text-ink-500 dark:text-sand-400 mt-2 italic">
                                Associated with:
                                <span class="font-medium">{{ $reward->place->name }}</span>
                            </p>
                        @endif
                    </div>
                </div>

                {{-- ACTION BUTTON --}}
                @if ($reward->place)
                    <button
                        @click="selecting = {{ $reward->id }}; selectedItemId = null;"
                        class="w-full mt-4 px-4 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                               hover:shadow-glow hover:scale-[1.03] transition-all">
                        Apply Reward
                    </button>
                @else
                    <p class="text-xs mt-3 text-red-500">
                        ⚠ This reward has no associated place and cannot be applied.
                    </p>
                @endif

                {{-- Replacement UI (appears when reward selected) --}}
                <div
                    class="mt-4 space-y-4"
                    x-show="selecting === {{ $reward->id }}"
                    x-collapse
                >
                    @php
                        $foodItems = $itinerary->items->where('type', 'food')->values();
                    @endphp

                    @if ($foodItems->isEmpty())
                        <p class="text-sm text-amber-600 dark:text-amber-300">
                            No food items in this itinerary can be replaced.
                        </p>
                    @else
                        <div>
                            <label for="replace-item-{{ $reward->id }}"
                                   class="text-sm font-medium text-ink-700 dark:text-sand-200">
                                Replace which food activity?
                            </label>

                            <select
                                x-model="selectedItemId"
                                id="replace-item-{{ $reward->id }}"
                                class="mt-1 w-full px-3 py-2 rounded-xl border border-sand-300 dark:border-ink-700
                                       bg-white dark:bg-sand-900 text-sm focus:ring-copper focus:border-copper transition"
                            >
                                <option value="">Select an item…</option>
                                @foreach ($foodItems as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->title }} 
                                        ({{ $item->start_time ? \Carbon\Carbon::parse($item->start_time)->format('g:i A') : 'No time' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Preview of selected item --}}
                        <div x-show="selectedItemId" x-collapse class="rounded-xl border border-sand-200 dark:border-ink-700 bg-sand-50 dark:bg-sand-800 p-4 text-sm">
                            <template x-for="item in {{ $foodItems->toJson() }}">
                                <div x-show="item.id == selectedItemId">
                                    <p class="font-semibold text-ink-800 dark:text-sand-100" x-text="item.title"></p>
                                    <p class="text-xs text-ink-500 dark:text-sand-300 mt-1">
                                        Scheduled:
                                        <span x-text="item.start_time ? new Date(item.start_time).toLocaleString() : 'Not scheduled'"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        {{-- Replace Button --}}
                        <form
                            x-show="selectedItemId"
                            x-collapse
                            method="POST"
                            action="{{ route('traveler.places.add-to-itinerary-reward') }}"
                            class="space-y-2"
                        >
                            @csrf
                            <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                            <input type="hidden" name="itinerary_id" value="{{ $itinerary->id }}">
                            <input type="hidden" name="replace_item_id" :value="selectedItemId">

                            <button
                                type="submit"
                                class="w-full mt-2 px-4 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                       hover:shadow-glow hover:scale-[1.03] transition-all">
                                Replace with {{ $reward->place->name }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-ink-500 dark:text-sand-300 italic">
                No rewards available yet.
            </p>
        @endforelse

    </div>
</div>
