<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Profile Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{--  Intro Card --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Manage Your Profile
                </p>
                <p class="text-sm text-ink-600 dark:text-sand-100 mt-1 leading-relaxed">
                    Update your personal information, change your password, or remove your account.
                    Make sure your details are always up to date.
                </p>
            </div>

            {{--  Update Profile Information --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.005]">

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-copper" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 21a8.25 8.25 0 1115 0" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
                        Profile Information
                    </h3>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{--  Update Password --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.005]">

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-forest/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-forest" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 10.5V6a4.5 4.5 0 10-9 0v4.5m9 0H7.5m9 0v8.25a2.25 2.25 0 01-2.25 2.25h-6A2.25 2.25 0 016 18.75V10.5" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
                        Update Password
                    </h3>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{--  Delete Account --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.005]">

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600 dark:text-red-400"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 7h12m-9 4h6m-7 4h8m1-14H7l-1 1H3v2h18V4h-3l-1-1z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-semibold text-red-700 dark:text-red-400">
                        Delete Account
                    </h3>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
