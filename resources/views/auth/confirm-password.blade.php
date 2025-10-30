<x-guest-layout>
    <div class="w-full max-w-md mx-auto px-8 py-10 bg-white dark:bg-sand-800 rounded-3xl shadow-soft 
                border border-sand-200 dark:border-ink-700 transition-all duration-300 ease-out 
                hover:shadow-glow hover:scale-[1.01]">

        {{-- Header --}}
        <h2 class="text-2xl font-semibold text-center text-ink-900 dark:text-ink-100 mb-2">
            Confirm Your Password
        </h2>
        <p class="text-center text-sm text-ink-600 dark:text-ink-300 mb-8">
            This is a secure area of the app — please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            {{-- Password --}}
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-ink-800 dark:text-ink-200" />
                <x-text-input id="password"
                              class="block mt-1 w-full rounded-full border-sand-300 dark:border-ink-600 
                                     bg-sand-50 dark:bg-ink-800 focus:ring-copper focus:border-copper"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Submit --}}
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 mt-8">
                <button type="submit"
                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
