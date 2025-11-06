<section class="space-y-6">

    {{-- Section Header --}}
    <header>
        <h2 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-ink-600 dark:text-sand-200 leading-relaxed">
            {{ __('This action is permanent. Once your account is deleted, all associated data will be permanently removed. Please download any information you want to keep before proceeding.') }}
        </p>
    </header>

    {{-- Delete Button --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-full shadow-soft
               hover:bg-red-700 hover:shadow-glow hover:scale-[1.03]
               transition-all duration-200 ease-out"
    >
        {{ __('Delete Account') }}
    </button>

    {{-- Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
              class="p-8 space-y-6 bg-white dark:bg-sand-800 rounded-3xl">

            @csrf
            @method('delete')

            {{-- Modal Title --}}
            <h2 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            {{-- Modal Text --}}
            <p class="text-sm text-ink-600 dark:text-sand-200 leading-relaxed">
                {{ __('This action cannot be undone. Please enter your password to confirm account deletion.') }}
            </p>

            {{-- Password Field --}}
            <div class="space-y-2">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-600
                           bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                           focus:ring focus:ring-red-300/50 focus:border-red-400 transition"
                    placeholder="{{ __('Enter your password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="text-red-500" />
            </div>

            {{-- Modal Buttons --}}
            <div class="flex justify-end gap-3 pt-4">

                {{-- Cancel --}}
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2 rounded-full border border-sand-300 dark:border-ink-600
                               text-ink-700 dark:text-sand-100 bg-white dark:bg-sand-900
                               hover:bg-sand-100 dark:hover:bg-ink-700 hover:shadow-soft
                               transition-all duration-200 ease-out">
                    {{ __('Cancel') }}
                </button>

                {{-- Delete --}}
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-red-600 text-white font-semibold shadow-soft
                               hover:bg-red-700 hover:shadow-glow hover:scale-[1.03]
                               transition-all duration-200 ease-out">
                    {{ __('Delete Account') }}
                </button>

            </div>
        </form>
    </x-modal>
</section>
