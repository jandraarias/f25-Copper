{{-- resources/views/traveler/preferences/preferences/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Preferences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Flash messages --}}
                <x-flash-messages />

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Preferences</h3>
                    <a href="{{ route('traveler.preferences.create') }}"
                       class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        + New Preference
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Key</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Value</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($preferences as $preference)
                                <tr>
                                    <td class="px-4 py-3">{{ $preference->key }}</td>
                                    <td class="px-4 py-3">{{ $preference->value }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center gap-2 justify-end">
                                            <a href="{{ route('traveler.preferences.edit', $preference) }}"
                                               class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('traveler.preferences.destroy', $preference) }}"
                                                  onsubmit="return confirm('Delete this preference?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded bg-red-600 text-white text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
                                        No preferences yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $preferences->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
