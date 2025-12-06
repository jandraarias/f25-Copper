<!-- resources/views/expert/profile/edit.blade.php -->

<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-4 px-2 bg-gradient-to-r 
                    from-copper-100/60 to-transparent dark:from-copper-900/20
                    rounded-2xl shadow-soft">
            <h2 class="text-3xl font-bold tracking-tight text-ink-900 dark:text-sand-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-copper" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4s-3 1.567-3 3.5S10.343 11 12 11z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 20v-1c0-3.314 3.582-6 8-6s8 2.686 8 6v1"/>
                </svg>
                Edit Expert Profile
            </h2>

            <a href="{{ route('expert.profile.show') }}"
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-sand-800 p-8 rounded-3xl shadow-soft 
                        border border-sand-200 dark:border-ink-700">

                <form method="POST" action="{{ route('expert.profile.update') }}"
                      enctype="multipart/form-data"
                      class="space-y-10">
                    @csrf
                    @method('PATCH')

                    {{-- HEADSHOT --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200">
                            Headshot
                        </label>

                        <div class="flex items-center gap-6">
                            <img src="{{ $expert->profile_photo_url ?? asset('storage/images/defaults/expert.png') }}"
                                class="w-28 h-28 rounded-2xl object-cover shadow
                                        border border-sand-300 dark:border-ink-700" />

                            <input type="file" name="photo"
                                accept="image/*"
                                class="block w-full text-sm text-ink-700 dark:text-ink-200
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-full file:border-0
                                        file:bg-copper file:text-white
                                        hover:file:bg-copper-600
                                        transition-all duration-200 shadow-soft" />
                        </div>

                        @error('photo')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BIO --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Bio
                        </label>

                        <textarea name="bio"
                                rows="4"
                                class="w-full rounded-xl border-sand-300 dark:border-ink-700
                                        dark:bg-sand-900 text-ink-900 dark:text-ink-100 focus:ring-copper">{{ old('bio', $expert->bio) }}</textarea>

                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EXPERTISE --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Areas of Expertise
                        </label>
                        <input type="text" name="expertise"
                               class="w-full rounded-xl border-sand-300 dark:border-ink-700 
                                      dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                               value="{{ old('expertise', $expert->expertise) }}">
                    </div>

                    {{-- LANGUAGES --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Languages Spoken
                        </label>
                        <input type="text" name="languages"
                               class="w-full rounded-xl border-sand-300 dark:border-ink-700 
                                      dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                               value="{{ old('languages', $expert->languages) }}">
                    </div>

                    {{-- EXPERIENCE --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Years of Experience
                        </label>
                        <input type="number" name="experience_years" min="0" max="60"
                               class="w-full rounded-xl border-sand-300 dark:border-ink-700 
                                      dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                               value="{{ old('experience_years', $expert->experience_years) }}">
                    </div>

                     {{-- HOURLY RATE --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Hourly Rate (USD)
                        </label>
                        <input type="number" 
                            name="hourly_rate" 
                            step="0.01" min="0"
                            class="w-full rounded-xl border-sand-300 dark:border-ink-700 
                                    dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                            value="{{ old('hourly_rate', $expert->hourly_rate) }}">
                    </div>

                    {{-- AVAILABILITY --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Availability (ex: Weekdays 9-5, Weekends Only, Flexible)
                        </label>
                        <input type="text" 
                            name="availability"
                            class="w-full rounded-xl border-sand-300 dark:border-ink-700 
                                    dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                            value="{{ old('availability', $expert->availability) }}">
                    </div>

                    {{-- CITY SELECT --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Expertise Location
                        </label>

                        <select name="city"
                                class="w-full rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-900 
                                       text-ink-900 dark:text-ink-100">
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" 
                                        {{ old('city', $expert->city) === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SAVE --}}
                    <div class="pt-4">
                        <button type="submit"
                                class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                                       hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            Save Profile
                        </button>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-red-100 text-red-700">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
