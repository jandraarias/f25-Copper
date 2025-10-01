{{-- resources/views/traveler/itineraries/edit.blade.php --}}
<x-app-layout x-data="{ showNewItem: false }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Itinerary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
            {{-- Itinerary form --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-flash-messages />

                    <form method="POST" action="{{ route('traveler.itineraries.update', $itinerary) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Name (required) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">Name</label>
                                <input
                                    name="name"
                                    type="text"
                                    value="{{ old('name', $itinerary->name) }}"
                                    required
                                    class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                >
                                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Description (optional) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">
                                    Description <span class="text-xs text-gray-500">(optional)</span>
                                </label>
                                <textarea
                                    name="description"
                                    rows="5"
                                    class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                >{{ old('description', $itinerary->description) }}</textarea>
                                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Countries (multi-select, required ≥1) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">Countries</label>
                                @php
                                    // Preselect previously chosen countries (or old input on validation error)
                                    $selectedCountries = old('countries', $itinerary->countries->pluck('country')->toArray());
                                @endphp
                                <select name="countries[]" multiple required
                                        class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700 h-40">
                                    @foreach(config('countries.list') as $code => $name)
                                        <option value="{{ $code }}" @selected(collect($selectedCountries)->contains($code))>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    Hold Ctrl (Windows) or Command (Mac) to select multiple.
                                </p>
                                @error('countries')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Destination (optional) --}}
                            <div>
                                <label class="block text-sm font-medium">
                                    Destination <span class="text-xs text-gray-500">(optional)</span>
                                </label>
                                <input
                                    name="destination"
                                    type="text"
                                    value="{{ old('destination', $itinerary->destination ?? '') }}"
                                    placeholder="City / Region (optional)"
                                    class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                >
                                @error('destination')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Start Date (required) --}}
                            <div>
                                <label class="block text-sm font-medium">Start Date</label>
                                <input
                                    name="start_date"
                                    type="date"
                                    value="{{ old('start_date', optional($itinerary->start_date)->format('Y-m-d')) }}"
                                    required
                                    class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                >
                                @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- End Date (required) --}}
                            <div>
                                <label class="block text-sm font-medium">End Date</label>
                                <input
                                    name="end_date"
                                    type="date"
                                    value="{{ old('end_date', optional($itinerary->end_date)->format('Y-m-d')) }}"
                                    required
                                    class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                >
                                @error('end_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-2">
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                                Save
                            </button>
                            <a href="{{ route('traveler.itineraries.index') }}"
                               class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600">
                                Back to My Itineraries
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Items manager --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Items</h3>
                        <button type="button" @click="showNewItem = !showNewItem"
                                class="px-3 py-2 rounded bg-green-600 text-white">
                            <span x-show="!showNewItem">+ Add Item</span>
                            <span x-show="showNewItem">Cancel</span>
                        </button>
                    </div>

                    {{-- New Item form --}}
                    <div x-show="showNewItem" x-cloak class="mb-6">
                        <form method="POST" action="{{ route('traveler.itineraries.items.store', $itinerary) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Type</label>
                                    <select name="type" required
                                            class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                        @php $t = old('type'); @endphp
                                        <option value="">Select…</option>
                                        <option value="flight"   @selected($t==='flight')>Flight</option>
                                        <option value="hotel"    @selected($t==='hotel')>Hotel</option>
                                        <option value="activity" @selected($t==='activity')>Activity</option>
                                        <option value="transfer" @selected($t==='transfer')>Transfer</option>
                                        <option value="note"     @selected($t==='note')>Note</option>
                                    </select>
                                    @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Title</label>
                                    <input name="title" type="text" value="{{ old('title') }}" required
                                           class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Start Time</label>
                                    <input name="start_time" type="datetime-local" value="{{ old('start_time') }}" required
                                           class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                    @error('start_time')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">End Time</label>
                                    <input name="end_time" type="datetime-local" value="{{ old('end_time') }}"
                                           class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                    @error('end_time')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Location</label>
                                    <input name="location" type="text" value="{{ old('location') }}"
                                           class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                    @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Details</label>
                                    <textarea name="details" rows="4"
                                              class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">{{ old('details') }}</textarea>
                                    @error('details')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                                    Add Item
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Items table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Start</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">End</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($itinerary->items as $item)
                                    @php
                                        $st = $item->start_time ? \Illuminate\Support\Carbon::parse($item->start_time) : null;
                                        $et = $item->end_time ? \Illuminate\Support\Carbon::parse($item->end_time) : null;
                                    @endphp
                                    <tr x-data="{ open: false }">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ ucfirst($item->type) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $item->title }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $st ? $st->format('M j, Y g:ia') : '—' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $et ? $et->format('M j, Y g:ia') : '—' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center gap-2 justify-end">
                                                <button type="button" @click="open = !open"
                                                        class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm">
                                                    Edit
                                                </button>

                                                <form method="POST" action="{{ route('traveler.items.destroy', $item) }}"
                                                      onsubmit="return confirm('Delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded bg-red-600 text-white text-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr x-show="open" x-cloak>
                                        <td colspan="5" class="px-4 py-4 bg-gray-50 dark:bg-gray-900">
                                            <form method="POST" action="{{ route('traveler.items.update', $item) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium">Type</label>
                                                        <select name="type" required
                                                                class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                                            @php $t = old('type', $item->type); @endphp
                                                            <option value="flight"   @selected($t==='flight')>Flight</option>
                                                            <option value="hotel"    @selected($t==='hotel')>Hotel</option>
                                                            <option value="activity" @selected($t==='activity')>Activity</option>
                                                            <option value="transfer" @selected($t==='transfer')>Transfer</option>
                                                            <option value="note"     @selected($t==='note')>Note</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium">Title</label>
                                                        <input name="title" type="text" value="{{ old('title', $item->title) }}" required
                                                               class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium">Start Time</label>
                                                        <input name="start_time" type="datetime-local"
                                                               value="{{ old('start_time', $st ? $st->format('Y-m-d\TH:i') : '') }}" required
                                                               class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium">End Time</label>
                                                        <input name="end_time" type="datetime-local"
                                                               value="{{ old('end_time', $et ? $et->format('Y-m-d\TH:i') : '') }}"
                                                               class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                                    </div>

                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm font-medium">Location</label>
                                                        <input name="location" type="text" value="{{ old('location', $item->location) }}"
                                                               class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                                    </div>

                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm font-medium">Details</label>
                                                        <textarea name="details" rows="4"
                                                                  class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">{{ old('details', $item->details) }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                                                        Save Item
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
                                            No items yet. Click “Add Item” to get started.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
