<x-app-layout x-data="{ showNewItem: false }">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ __('Edit Itinerary') }}
            </h2>

            <a href="{{ route('traveler.itineraries.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-10">
            {{-- ================== Itinerary Form ================== --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
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
                                       class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
                                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Description</label>
                                <textarea name="description" rows="4" class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">{{ old('description', $itinerary->description) }}</textarea>
                                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Countries --}}
                            <div class="md:col-span-2" 
                                 x-data="countrySelect(window.allCountries, @json(old('countries', $itinerary->countries->pluck('id')->toArray())))">
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Countries</label>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <template x-for="country in selectedCountries" :key="country.id">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-copper text-white text-sm font-medium shadow-soft hover:scale-[1.05] transition-all duration-200 ease-out">
                                            <span x-text="country.name"></span>
                                            <button type="button" @click="removeCountry(country.id)" class="ml-1 text-white/80 hover:text-white transition">&times;</button>
                                        </span>
                                    </template>
                                </div>
                                <template x-for="country in selectedCountries" :key="'input-' + country.id">
                                    <input type="hidden" name="countries[]" :value="country.id">
                                </template>
                                <select x-model="newCountry" @change="addCountry($event)" class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
                                    <option value="">-- Select a country --</option>
                                    @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('countries')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Destination --}}
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Destination (Optional)</label>
                                <input name="destination" type="text" value="{{ old('destination', $itinerary->destination ?? '') }}" placeholder="City / Region (optional)" class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
                                @error('destination')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Dates --}}
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">Start Date</label>
                                <input name="start_date" type="date" value="{{ old('start_date', optional($itinerary->start_date)->format('Y-m-d')) }}" class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
                                @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">End Date</label>
                                <input name="end_date" type="date" value="{{ old('end_date', optional($itinerary->end_date)->format('Y-m-d')) }}" class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
                                @error('end_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- ===== Invite Collaborators ===== --}}
                        <div class="mt-10 bg-sand-50 dark:bg-sand-900/50 border border-sand-200 dark:border-ink-700 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-copper mb-4">Invite Collaborators</h3>

                            <form method="POST" action="{{ route('traveler.itineraries.invite', $itinerary) }}" class="flex flex-wrap gap-3">
                                @csrf
                                <input type="email" name="email" placeholder="Enter collaborator's email"
                                       class="flex-grow border border-sand-200 dark:border-ink-700 rounded-xl px-4 py-2.5 dark:bg-sand-900
                                              focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200"
                                       required>
                                <button type="submit"
                                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                    Send Invite
                                </button>
                            </form>

                            @if ($itinerary->invitations->isNotEmpty())
                                <div class="mt-6 space-y-2">
                                    <h4 class="text-sm font-semibold text-ink-700 dark:text-ink-200">Pending Invitations</h4>
                                    <ul class="text-sm text-ink-600 dark:text-ink-300">
                                        @foreach ($itinerary->invitations as $invite)
                                            <li>
                                                {{ $invite->email }}
                                                <span class="text-xs text-ink-400">({{ ucfirst($invite->status) }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        {{-- Buttons --}}
                        <div class="mt-10 flex justify-end gap-4">
                            <a href="{{ route('traveler.itineraries.index') }}" class="group px-6 py-2.5 rounded-full border border-ink-500 text-ink-700 dark:text-sand-100 hover:text-copper hover:border-copper hover:scale-[1.03] hover:shadow-glow transition-all duration-200 ease-out font-medium shadow-soft">
                                Back to My Itineraries
                            </a>
                            <button type="submit" class="group flex items-center justify-center gap-2 px-8 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-200 group-hover:rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================== Items Manager ================== --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Items</h3>
                        <button type="button" @click="showNewItem = !showNewItem"
                                class="px-4 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            <span x-show="!showNewItem">+ Add Item</span>
                            <span x-show="showNewItem">Cancel</span>
                        </button>
                    </div>

                    <div x-show="showNewItem" x-cloak class="mb-8">
                        <form method="POST" action="{{ route('traveler.itineraries.items.store', $itinerary) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-form.item-fields :old="$old ?? []" />
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                    Add Item
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-sand-200 dark:divide-ink-700">
                            <thead class="bg-sand dark:bg-sand-900/40">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Start</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">End</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-sand-800 divide-y divide-sand-200 dark:divide-ink-700">
                                @forelse ($itinerary->items as $item)
                                    @include('traveler.itineraries.partials.item-row', ['item' => $item])
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-ink-500 dark:text-sand-100">
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

    {{-- Inject Countries --}}
    <script>
        window.allCountries = @json(\App\Models\Country::select('id','name')->orderBy('name')->get());
    </script>
</x-app-layout>
