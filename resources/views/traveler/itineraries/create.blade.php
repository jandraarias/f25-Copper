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

                            {{-- Location (City) --}}
                            @php
                                // Try to read supported cities from meta->city; fallback to Williamsburg, VA for prototype.
                                $cities = \App\Models\Place::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$.city')) as city")
                                    ->whereNotNull(\Illuminate\Support\Facades\DB::raw("JSON_EXTRACT(meta, '$.city')"))
                                    ->distinct()
                                    ->pluck('city')
                                    ->filter()
                                    ->sort()
                                    ->values();

                                if ($cities->isEmpty()) {
                                    $cities = collect(['Williamsburg, VA']);
                                }
                            @endphp
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">City</label>
                                <select name="location" required
                                        class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                               focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                               px-4 py-2.5 dark:bg-sand-900">
                                    <option value="">-- Select a city --</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('location') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Preference Profile --}}
                            <div>
                                <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">
                                    Preference Profile
                                </label>
                                <select name="preference_profile_id" required
                                        class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                               focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                               px-4 py-2.5 dark:bg-sand-900">
                                    <option value="">-- Select a preference profile --</option>
                                    @foreach(Auth::user()->traveler->preferenceProfiles as $profile)
                                        <option value="{{ $profile->id }}" {{ old('preference_profile_id') == $profile->id ? 'selected' : '' }}>
                                            {{ $profile->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('preference_profile_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
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

                            {{-- ===== Collaboration (inlined; no missing partial) ===== --}}
                            <div class="md:col-span-2"
                                 x-data="collabForm({
                                    enabled: {{ old('is_collaborative') ? 'true' : 'false' }},
                                    initialInvites: @json(old('invite_emails', []))
                                 })">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-1">
                                            Collaboration
                                        </label>
                                        <p class="text-xs text-ink-500 dark:text-ink-300">
                                            Enable to invite other Copper users by email to co-edit this itinerary. Only you (the creator) can delete it.
                                        </p>
                                    </div>

                                    {{-- Pretty toggle bound to a hidden checkbox --}}
                                    <div class="flex items-center gap-3">
                                        <input type="hidden" name="is_collaborative" :value="enabled ? 1 : 0">
                                        <button type="button"
                                                @click="enabled = !enabled"
                                                :class="enabled ? 'bg-gradient-copper text-white shadow-glow' : 'border border-copper text-copper'"
                                                class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 ease-out">
                                            <span x-text="enabled ? 'Enabled' : 'Disabled'"></span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Invite UI (visible only when enabled) --}}
                                <div x-show="enabled" x-transition.opacity.duration.200ms class="mt-4">
                                    <label class="block text-sm font-semibold text-ink-700 dark:text-sand-100 mb-2">
                                        Invite Collaborators
                                    </label>

                                    {{-- Chips --}}
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="(email, idx) in invites" :key="email">
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-copper text-white text-sm shadow-soft">
                                                <span x-text="email"></span>
                                                <button type="button" @click="removeInvite(email)" class="text-white/90 hover:text-white">&times;</button>
                                                <input type="hidden" name="invite_emails[]" :value="email">
                                            </span>
                                        </template>
                                    </div>

                                    {{-- Input + Add --}}
                                    <div class="flex items-center gap-2">
                                        <input x-model="input"
                                               @keydown.enter.prevent="addFromInput()"
                                               @paste.prevent="pasteHandler($event)"
                                               type="email"
                                               placeholder="friend@example.com"
                                               class="flex-1 border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                                                      focus:ring-copper focus:border-copper focus:shadow-glow transition-all duration-200
                                                      px-4 py-2.5 dark:bg-sand-900" />
                                        <button type="button"
                                                @click="addFromInput()"
                                                class="px-4 py-2 rounded-full border border-copper text-copper
                                                       hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                                                       transition-all duration-200 ease-out text-sm font-medium">
                                            Add
                                        </button>
                                    </div>

                                    {{-- Inline helper & errors --}}
                                    <p class="mt-2 text-xs text-ink-500 dark:text-ink-300">
                                        Tip: Paste a list of emails (comma, space, or newline separated).
                                    </p>
                                    @error('invite_emails')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    @error('invite_emails.*')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- AI Generation Notice --}}
                        <div class="mt-8 p-4 rounded-2xl bg-sand-100 dark:bg-sand-700 text-sm text-ink-700 dark:text-sand-100 border border-sand-300 dark:border-ink-600">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M12 18a9 9 0 100-18 9 9 0 000 18z" />
                                </svg>
                                <p>
                                    Once you create your itinerary, Copper will automatically generate a personalized travel plan
                                    based on your <strong>selected city</strong> and <strong>preference profile</strong>.
                                    You can adjust or regenerate it later from the itinerary page.
                                </p>
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

    {{-- Inject countries + collaboration Alpine store --}}
    <script>
        window.allCountries = @json(\App\Models\Country::select('id','name')->orderBy('name')->get());

        function collabForm({ enabled = false, initialInvites = [] }) {
            return {
                enabled,
                input: '',
                invites: Array.from(new Set(initialInvites.filter(Boolean))).sort(),
                emailRegex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,

                add(email) {
                    const e = (email || '').trim();
                    if (!e) return;
                    if (!this.emailRegex.test(e)) return;
                    if (!this.invites.includes(e)) this.invites.push(e);
                },
                addFromInput() {
                    const parts = this.input.split(/[\s,]+/).filter(Boolean);
                    parts.forEach(p => this.add(p));
                    this.input = '';
                },
                pasteHandler(evt) {
                    const text = (evt.clipboardData || window.clipboardData).getData('text');
                    const parts = (text || '').split(/[\s,]+/).filter(Boolean);
                    parts.forEach(p => this.add(p));
                },
                removeInvite(email) {
                    this.invites = this.invites.filter(i => i !== email);
                }
            }
        }
    </script>
</x-app-layout>
