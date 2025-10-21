{{-- resources/views/traveler/preferences/profiles/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Preference Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <x-flash-messages />

                <form method="POST" action="{{ route('traveler.preference-profiles.update', $preferenceProfile) }}">
                    @csrf
                    @method('PUT')

                    @include('traveler.preferences.profiles.form', [
                        'preferenceProfile' => $preferenceProfile,
                        'mainOptions' => $mainOptions,
                        'subMap' => $subMap,
                        'existingPreferences' => $existingPreferences,
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
