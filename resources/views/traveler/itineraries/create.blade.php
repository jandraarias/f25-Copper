<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ __('Create Itinerary') }}
            </h2>

            <a href="{{ route('traveler.itineraries.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">

                    <form method="POST" action="{{ route('traveler.itineraries.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Name</label>
                                <input name="name" type="text" value="{{ old('name') }}" required
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                              px-4 py-2.5 dark:bg-sand-900" />
                                @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Description</label>
                                <textarea name="description" rows="4" required
                                          class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                                 focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                                 px-4 py-2.5 dark:bg-sand-900">{{ old('description') }}</textarea>
                                @error('description')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Countries --}}
                            <div class="md:col-span-2"
                                 x-data="countrySelect(window.allCountries, @json(old('countries', [])))">
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Countries</label>

                                {{-- Selected Chips --}}
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="country in selectedCountries" :key="country.id">
                                        <span class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-copper text-white text-sm shadow-soft">
                                            <span x-text="country.name"></span>
                                            <button type="button" @click="removeCountry(country.id)" class="ml-1 text-white/80 hover:text-white">&times;</button>
                                        </span>
                                    </template>
                                </div>

                                {{-- Hidden Inputs --}}
                                <template x-for="country in selectedCountries" :key="'input-' + country.id">
                                    <input type="hidden" name="countries[]" :value="country.id">
                                </template>

                                {{-- Dropdown --}}
                                <select x-model="newCountry" @change="addCountry($event)"
                                        class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                               focus:ring-copper focus:border-copper transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
                                    <option value="">-- Select a country --</option>
                                    @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('countries')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Destination --}}
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Destination (optional)</label>
                                <input name="destination" type="text" value="{{ old('destination') }}"
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                              px-4 py-2.5 dark:bg-sand-900" placeholder="City / Region" />
                                @error('destination')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Start Date</label>
                                <input name="start_date" type="date" value="{{ old('start_date') }}"
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                              px-4 py-2.5 dark:bg-sand-900" />
                                @error('start_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">End Date</label>
                                <input name="end_date" type="date" value="{{ old('end_date') }}"
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                              focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                              px-4 py-2.5 dark:bg-sand-900" />
                                @error('end_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="mt-10 flex justify-end gap-4">
                            <a href="{{ route('traveler.itineraries.index') }}"
                               class="px-6 py-2.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200
                                      hover:text-copper hover:border-copper hover:shadow-glow hover:scale-[1.03]
                                      transition-all duration-200 ease-out font-medium shadow-soft">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-8 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                                           hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                Create Itinerary
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Inject countries --}}
    <script>
        window.allCountries = @json(\App\Models\Country::select('id','name')->orderBy('name')->get());
    </script>
</x-app-layout>
