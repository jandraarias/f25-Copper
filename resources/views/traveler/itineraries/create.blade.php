<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Itinerary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Flash messages --}}
                    <x-flash-messages />

                    <form method="POST" action="{{ route('traveler.itineraries.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">Name</label>
                                <input name="name" type="text" value="{{ old('name') }}" required
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">Description</label>
                                <textarea name="description" rows="5" required
                                          class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">{{ old('description') }}</textarea>
                                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Country</label>
                                <input name="country" type="text" value="{{ old('country') }}" required
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       placeholder="e.g., United States or FR">
                                @error('country')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Destination (optional)</label>
                                <input name="destination" type="text" value="{{ old('destination') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       placeholder="City / Region (optional)">
                                @error('destination')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Start Date</label>
                                <input name="start_date" type="date" value="{{ old('start_date') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">End Date</label>
                                <input name="end_date" type="date" value="{{ old('end_date') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('end_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                                Create
                            </button>
                            <a href="{{ route('traveler.itineraries.index') }}"
                               class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 ml-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
