{{-- resources/views/traveler/preference-profiles/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Preference Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <x-flash-messages />

                <form method="POST" action="{{ route('traveler.preference-profiles.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Profile Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Save Profile
                        </button>
                        <a href="{{ route('traveler.preference-profiles.index') }}"
                           class="ml-2 px-4 py-2 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
