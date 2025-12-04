<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ __('Edit Itinerary') }}
            </h2>

            <a href="{{ route('traveler.itineraries.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform duration-200"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen overflow-x-hidden">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-10">

            {{-- ================== Itinerary Form ================== --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <x-flash-messages />

                    <form method="POST" action="{{ route('traveler.itineraries.update', $itinerary) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Itinerary Name</label>
                                <input name="name" type="text" value="{{ old('name', $itinerary->name) }}" required
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper focus:border-copper px-4 py-2.5 dark:bg-sand-900
                                              overflow-hidden text-ellipsis">
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-2 text-ink-700 dark:text-sand-100">Description</label>
                                <textarea name="description" rows="4"
                                          class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                                 focus:ring-copper focus:border-copper px-4 py-2.5 dark:bg-sand-900
                                                 overflow-wrap break-words">{{ old('description', $itinerary->description) }}</textarea>
                            </div>

                            {{-- Dates --}}
                            <div>
                                <label class="block text-sm font-semibold mb-2">Start Date</label>
                                <input name="start_date" type="date"
                                       value="{{ old('start_date', optional($itinerary->start_date)->format('Y-m-d')) }}"
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper px-4 py-2.5 dark:bg-sand-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">End Date</label>
                                <input name="end_date" type="date"
                                       value="{{ old('end_date', optional($itinerary->end_date)->format('Y-m-d')) }}"
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper px-4 py-2.5 dark:bg-sand-900">
                            </div>

                            {{-- Location --}}
                            <div>
                                <label class="block text-sm font-semibold mb-2">Location</label>
                                <input name="location" type="text" value="{{ old('location', $itinerary->location) }}"
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper px-4 py-2.5 dark:bg-sand-900">
                            </div>

                            {{-- Preference Profile --}}
                            <div>
                                <label class="block text-sm font-semibold mb-2">Preference Profile</label>
                                <select name="preference_profile_id" required
                                        class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                               focus:ring-copper focus:border-copper px-4 py-2.5 dark:bg-sand-900">
                                    <option value="">-- Select --</option>
                                    @foreach(Auth::user()->traveler->preferenceProfiles as $profile)
                                        <option value="{{ $profile->id }}" {{ old('preference_profile_id', $itinerary->preference_profile_id) == $profile->id ? 'selected' : '' }}>
                                            {{ $profile->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Preserve existing countries so validation passes --}}
                        @foreach ($itinerary->countries as $country)
                            <input type="hidden" name="countries[]" value="{{ $country->id }}">
                        @endforeach

                        <div class="mt-8 flex justify-end gap-4 flex-wrap">
                            <a href="{{ route('traveler.itineraries.show', $itinerary) }}"
                               class="px-6 py-2.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200
                                      hover:text-copper hover:border-copper hover:shadow-glow transition-all">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="px-8 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                           hover:shadow-glow hover:scale-[1.03] transition-all">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================== Grouped Itinerary Items ================== --}}
            @php
                $grouped = $itinerary->items->sortBy('start_time')->groupBy(fn($item) =>
                    \Illuminate\Support\Carbon::parse($item->start_time)->format('Y-m-d')
                );
            @endphp

            <div class="space-y-8">
                @forelse ($grouped as $date => $items)
                    <div x-data="{ open: false }" class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-2xl shadow-soft transition-all">
                        {{-- Day Header --}}
                        <button @click="open = !open"
                                class="w-full flex justify-between items-center px-6 py-4 text-left
                                       text-lg font-semibold text-ink-900 dark:text-ink-100 hover:bg-sand-50 dark:hover:bg-sand-900/40 rounded-t-2xl">
                            <span>{{ \Illuminate\Support\Carbon::parse($date)->format('l, F j, Y') }}</span>
                            <svg x-bind:class="open ? 'rotate-180' : ''"
                                 class="w-5 h-5 transition-transform duration-300 ease-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Collapsible Day Items --}}
                        <div x-show="open" x-collapse x-transition.opacity.duration.300ms class="px-6 pb-6 space-y-4">
                            @foreach ($items as $item)
                                <div x-data="{ editing: false }" class="itinerary-card border border-sand-200 dark:border-ink-700 rounded-xl p-4 shadow-sm hover:shadow-glow hover:scale-[1.01] transition-all duration-200 ease-out">
                                    {{-- Summary View --}}
                                    <div x-show="!editing" class="flex justify-between items-start flex-wrap gap-3">
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-semibold text-ink-900 dark:text-ink-100 break-words">{{ $item->title }}</h4>
                                            <p class="text-sm text-ink-700 dark:text-sand-100 break-words">
                                                {{ ucfirst($item->type) }} —
                                                {{ \Illuminate\Support\Carbon::parse($item->start_time)->format('g:ia') }}
                                                -
                                                {{ \Illuminate\Support\Carbon::parse($item->end_time)->format('g:ia') }}
                                            </p>
                                            @if ($item->details)
                                                <p class="text-sm text-ink-600 dark:text-sand-300 mt-1 break-words leading-relaxed">{{ $item->details }}</p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <button @click="editing = true"
                                                    class="px-3 py-1.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200
                                                           hover:text-copper hover:border-copper hover:shadow-glow text-sm">
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('traveler.items.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-full border border-red-400 text-red-500 text-sm
                                                               hover:bg-red-500 hover:text-white hover:shadow-glow transition-all">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Edit Form --}}
                                    <div x-show="editing" x-collapse x-transition class="mt-4">
                                        <form method="POST" action="{{ route('traveler.items.update', $item) }}" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <x-form.item-fields :old="[
                                                    'type' => $item->type,
                                                    'title' => $item->title,
                                                    'start_time' => optional($item->start_time)->format('Y-m-d\TH:i'),
                                                    'end_time' => optional($item->end_time)->format('Y-m-d\TH:i'),
                                                    'location' => $item->location,
                                                    'details' => $item->details,
                                                ]" />
                                            </div>
                                            <div class="flex justify-end gap-4 flex-wrap">
                                                <button type="button" @click="editing = false"
                                                        class="px-5 py-2.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200 text-sm
                                                               hover:text-copper hover:border-copper hover:shadow-glow transition-all">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                                                               hover:shadow-glow hover:scale-[1.03] transition-all">
                                                    Save Item
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center text-ink-600 dark:text-sand-100 py-12">
                        No itinerary items yet. Try generating or adding items manually.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        /* Contain text overflow globally */
        .itinerary-card, .itinerary-item {
            overflow-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
        }
        .text-ellipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        body, .min-h-screen, .max-w-6xl {
            overflow-x: hidden;
        }
    </style>
</x-app-layout>
