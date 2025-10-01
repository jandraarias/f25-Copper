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
                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">Name</label>
                                <input name="name" type="text" value="{{ old('name') }}" required
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium">Description</label>
                                <textarea name="description" rows="5" required
                                          class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">{{ old('description') }}</textarea>
                                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Countries with chips --}}
                            <div class="md:col-span-2" 
                                 x-data="countrySelect(window.allCountries, @json(old('countries', [])))">
                                <label class="block text-sm font-medium">Countries</label>

                                {{-- Selected chips --}}
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="country in selectedCountries" :key="country.id">
                                        <span class="flex items-center gap-1 px-3 py-1 rounded-full bg-blue-600 text-white text-sm">
                                            <span x-text="country.name"></span>
                                            <button type="button" @click="removeCountry(country.id)" class="ml-1 text-white">&times;</button>
                                        </span>
                                    </template>
                                </div>

                                {{-- Hidden inputs for submission --}}
                                <template x-for="country in selectedCountries" :key="'input-' + country.id">
                                    <input type="hidden" name="countries[]" :value="country.id">
                                </template>

                                {{-- Dropdown --}}
                                <select x-model="newCountry" @change="addCountry($event)" 
                                        class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                    <option value="">-- Select a country --</option>
                                    @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>

                                @error('countries')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Destination --}}
                            <div>
                                <label class="block text-sm font-medium">Destination (optional)</label>
                                <input name="destination" type="text" value="{{ old('destination') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       placeholder="City / Region (optional)">
                                @error('destination')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label class="block text-sm font-medium">Start Date</label>
                                <input name="start_date" type="date" value="{{ old('start_date') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- End Date --}}
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

    {{-- inject countries into window scope --}}
    <script>
        // @ts-nocheck
        window.allCountries = @json(\App\Models\Country::select('id','name')->orderBy('name')->get());
    </script>
</x-app-layout>
