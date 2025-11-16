<x-guest-layout>
    <div class="w-full max-w-md mx-auto px-8 py-10 bg-white dark:bg-sand-800 rounded-3xl shadow-soft border border-sand-200 dark:border-ink-700
                transition-all duration-300 ease-out hover:shadow-glow hover:scale-[1.01]">

        {{-- Header --}}
        <h2 class="text-2xl font-semibold text-center text-ink-900 dark:text-ink-100 mb-2">
            Welcome Back
        </h2>
        <p class="text-center text-sm text-ink-600 dark:text-ink-300 mb-8">
            Sign in to continue exploring and planning your journeys.
        </p>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-ink-800 dark:text-ink-200" />
                <x-text-input id="email" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 
                                            bg-sand-50 dark:bg-ink-700 focus:ring-copper focus:border-copper"
                              type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-ink-800 dark:text-ink-200" />
                <x-text-input id="password" class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 
                                                bg-sand-50 dark:bg-ink-700 focus:ring-copper focus:border-copper"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-sand-300 dark:border-ink-600 text-copper focus:ring-copper 
                                  dark:bg-ink-700 dark:focus:ring-offset-ink-800">
                    <span class="ms-2 text-sm text-ink-700 dark:text-sand-100">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" 
                       class="text-sm text-ink-600 dark:text-sand-100 hover:text-copper dark:hover:text-copper transition-all duration-200">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6">
                <a href="{{ route('register') }}" 
                   class="text-sm text-ink-600 dark:text-sand-100 hover:text-copper dark:hover:text-copper transition-all duration-200">
                    {{ __('Need an account?') }}
                </a>

                <button type="submit" 
                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-guest-layout>
