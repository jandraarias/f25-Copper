<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Select Your Preferences') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('preferences.update') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($preferences as $preference)
                    <label class="flex items-center space-x-2">
                        <input
                            type="checkbox"
                            name="preferences[]"
                            value="{{ $preference->id }}"
                            {{ in_array($preference->id, $selectedPreferences) ? 'checked' : '' }}
                            class="form-checkbox"
                        >
                        <span>{{ $preference->name }} ({{ $preference->category }})</span>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Save Preferences
            </button>
        </form>
    </div>
</x-app-layout>