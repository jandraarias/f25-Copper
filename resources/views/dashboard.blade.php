<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700 
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <p class="text-lg text-ink-800 dark:text-sand-100 font-medium">
                    {{ __("You're logged in!") }}
                </p>
                <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">
                    {{ __("This is a placeholder dashboard. You’ll be redirected to your role-specific dashboard once it’s set up.") }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
