<!-- resources/views/expert/profile/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
            {{ __('Edit Expert Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-sand-800 p-8 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700">

                <form method="POST" action="{{ route('expert.profile.update') }}" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    {{-- Bio --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Bio
                        </label>
                        <textarea name="bio"
                                  rows="4"
                                  class="w-full rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-900 text-ink-900 dark:text-ink-100">
                            {{ old('bio', $expert->bio) }}
                        </textarea>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Expertise --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Areas of Expertise
                        </label>
                        <input type="text" name="expertise"
                            class="w-full rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                            value="{{ old('expertise', $expert->expertise) }}">
                    </div>

                    {{-- Languages --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Languages Spoken
                        </label>
                        <input type="text" name="languages"
                            class="w-full rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                            value="{{ old('languages', $expert->languages) }}">
                    </div>

                    {{-- Experience --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Years of Experience
                        </label>
                        <input type="number" name="experience_years" min="0" max="60"
                            class="w-full rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-900 text-ink-900 dark:text-ink-100"
                            value="{{ old('experience_years', $expert->experience_years) }}">
                    </div>

                    {{-- Location --}}
                    @php
                        // Extract distinct city names from Place.meta JSON
                        $cities = \App\Models\Place::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$.city')) as city")
                            ->whereNotNull(\Illuminate\Support\Facades\DB::raw("JSON_EXTRACT(meta, '$.city')"))
                            ->distinct()
                            ->orderBy('city')
                            ->pluck('city')
                            ->filter()
                            ->values();

                        if ($cities->isEmpty()) {
                            $cities = collect(['Williamsburg, VA']);
                        }
                    @endphp

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

                    {{-- Availability --}}
                    <div>
                        <label class="block text-sm font-medium text-ink-700 dark:text-ink-200 mb-1">
                            Availability Notes
                        </label>
                        <textarea name="availability" rows="3"
                            class="w-full rounded-xl border-sand-300 dark:border-ink-700 dark:bg-sand-900 text-ink-900 dark:text-ink-100">
                            {{ old('availability', $expert->availability) }}
                        </textarea>
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-4">
                        <button type="submit"
                                class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                                       hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            Save Profile
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
