<x-guest-layout>
    <div class="w-full max-w-md mx-auto px-8 py-10 bg-white dark:bg-sand-800 rounded-3xl shadow-soft 
                border border-sand-200 dark:border-ink-700 transition-all duration-300 ease-out 
                hover:shadow-glow hover:scale-[1.01]">

        {{-- Header --}}
        <h2 class="text-2xl font-semibold text-center text-ink-900 dark:text-ink-100 mb-2">
            Reset Your Password
        </h2>
        <p class="text-center text-sm text-ink-600 dark:text-ink-300 mb-8">
            Choose a new password for your account.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            {{-- Hidden Token --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-ink-800 dark:text-ink-200" />
                <x-text-input id="email"
                              class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 
                                     bg-sand-50 dark:bg-ink-700 focus:ring-copper focus:border-copper"
                              type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div>
                <x-input-label for="password" :value="__('New Password')" class="text-ink-800 dark:text-ink-200" />
                <x-text-input id="password"
                              class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 
                                     bg-sand-50 dark:bg-ink-700 focus:ring-copper focus:border-copper"
                              type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Confirm Password --}}
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-ink-800 dark:text-ink-200" />
                <x-text-input id="password_confirmation"
                              class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 
                                     bg-sand-50 dark:bg-ink-700 focus:ring-copper focus:border-copper"
                              type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            {{-- Submit --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-8">
                <a href="{{ route('login') }}"
                   class="text-sm text-ink-600 dark:text-sand-100 hover:text-copper dark:hover:text-copper transition-all duration-200">
                    {{ __('Back to Login') }}
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft 
                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
