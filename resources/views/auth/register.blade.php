<x-guest-layout>
    <div class="w-full max-w-md mx-auto px-8 py-10 bg-white dark:bg-sand-800 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700
                transition-all duration-300 ease-out hover:shadow-glow hover:scale-[1.01]">
            
            {{-- Header --}}
            <h2 class="text-2xl font-semibold text-center text-ink-900 dark:text-ink-100 mb-2">
                Create Your Account
            </h2>
            <p class="text-center text-sm text-ink-600 dark:text-ink-300 mb-8">
                Join ItinerEase to start planning and sharing your journeys.
            </p>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                {{-- Role --}}
                <div x-data="{ role: '{{ old('role','') }}' }" class="space-y-6">
                    <div>
                        <x-input-label for="role" :value="__('Role')" class="text-ink-800 dark:text-ink-200" />
                        <select id="role" name="role" x-model="role"
                                class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                       text-ink-900 dark:text-sand-100 focus:ring-copper focus:border-copper" required>
                            <option value="">-- Select Role --</option>
                            <option value="traveler" {{ old('role') === 'traveler' ? 'selected' : '' }}>Traveler</option>
                            <option value="expert" {{ old('role') === 'expert' ? 'selected' : '' }}>Expert</option>
                            <option value="business" {{ old('role') === 'business' ? 'selected' : '' }}>Business</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="text-ink-800 dark:text-ink-200" />
                        <x-text-input id="name" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                                    focus:ring-copper focus:border-copper" 
                                      type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Date of Birth --}}
                    <div x-show="['traveler','expert'].includes(role)" x-cloak>
                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="text-ink-800 dark:text-ink-200" />
                        <x-text-input id="date_of_birth" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                                            focus:ring-copper focus:border-copper" 
                                      type="date" name="date_of_birth" :value="old('date_of_birth')" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                    {{-- Phone Number --}}
                    <div x-show="['traveler','expert','business'].includes(role)" x-cloak>
                        <x-input-label for="phone_number" :value="__('Phone Number')" class="text-ink-800 dark:text-ink-200" />
                        <x-text-input id="phone_number" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                                            focus:ring-copper focus:border-copper" 
                                      type="text" name="phone_number" :value="old('phone_number')" />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-ink-800 dark:text-ink-200" />
                        <x-text-input id="email" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                                    focus:ring-copper focus:border-copper" 
                                      type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-ink-800 dark:text-ink-200" />
                        <x-text-input id="password" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                                        focus:ring-copper focus:border-copper" 
                                      type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-ink-800 dark:text-ink-200" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 bg-sand-50 dark:bg-ink-700
                                                        focus:ring-copper focus:border-copper" 
                                      type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-8">
                    <a href="{{ route('login') }}" 
                       class="text-sm text-ink-600 dark:text-sand-100 hover:text-copper dark:hover:text-copper transition-all duration-200">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" 
                            class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                                   hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                        {{ __('Create Account') }}
                    </button>
                </div>
            </form>
        </div>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-guest-layout>
