<section class="space-y-6">

    {{-- Header --}}
    <header>
        <h2 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-ink-600 dark:text-sand-200 leading-relaxed">
            {{ __('Keep your account secure by choosing a long, random, and unique password.') }}
        </p>
    </header>

    {{-- Form --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div class="space-y-2">
            <x-input-label for="update_password_current_password"
                           :value="__('Current Password')" />

            <x-text-input id="update_password_current_password"
                          name="current_password"
                          type="password"
                          autocomplete="current-password"
                          class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                                 bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                                 focus:ring focus:ring-copper/30 focus:border-copper transition" />

            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        {{-- New Password --}}
        <div class="space-y-2">
            <x-input-label for="update_password_password"
                           :value="__('New Password')" />

            <x-text-input id="update_password_password"
                          name="password"
                          type="password"
                          autocomplete="new-password"
                          class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                                 bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                                 focus:ring focus:ring-copper/30 focus:border-copper transition" />

            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        {{-- Confirm --}}
        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation"
                           :value="__('Confirm Password')" />

            <x-text-input id="update_password_password_confirmation"
                          name="password_confirmation"
                          type="password"
                          autocomplete="new-password"
                          class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                                 bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                                 focus:ring focus:ring-copper/30 focus:border-copper transition" />

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-4">

            {{-- Save --}}
            <button type="submit"
                    class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                           hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                {{ __('Save') }}
            </button>

            {{-- Success Message --}}
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition.opacity.duration.500ms
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-forest font-medium dark:text-forest/80">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>

</section>
