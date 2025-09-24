{{-- resources/views/traveler/preference-profiles/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Preference Profiles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <x-flash-messages />

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Preference Profiles</h3>
                        <a href="{{ route('traveler.preference-profiles.create') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + New Profile
                        </a>
                    </div>

                    @forelse ($profiles as $profile)
                        <div class="border-b border-gray-200 dark:border-gray-700 py-3 flex justify-between items-center">
                            <div>
                                <p class="font-semibold">{{ $profile->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $profile->preferences->count() }} preferences
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('traveler.preference-profiles.show', $profile) }}"
                                   class="px-3 py-1.5 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                                    View
                                </a>
                                <a href="{{ route('traveler.preference-profiles.edit', $profile) }}"
                                   class="px-3 py-1.5 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('traveler.preference-profiles.destroy', $profile) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 border rounded text-red-600 hover:bg-red-50 dark:hover:bg-red-900"
                                            onclick="return confirm('Delete this profile?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No profiles yet.</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $profiles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
