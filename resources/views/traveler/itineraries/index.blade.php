{{-- resources/views/traveler/itineraries/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Itineraries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('traveler.itineraries.create') }}"
                   class="inline-flex items-center justify-center rounded bg-green-600 text-white px-4 py-2">
                    + Create Itinerary
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Destination</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dates</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($itineraries as $itinerary)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                            #{{ $itinerary->id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $itinerary->title }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-gray-700 dark:text-gray-300">{{ $itinerary->destination ?? '—' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $sd = $itinerary->start_date ? \Illuminate\Support\Carbon::parse($itinerary->start_date)->format('M j, Y') : null;
                                                $ed = $itinerary->end_date ? \Illuminate\Support\Carbon::parse($itinerary->end_date)->format('M j, Y') : null;
                                            @endphp
                                            <div class="text-gray-700 dark:text-gray-300">
                                                {{ $sd ?? '—' }} @if($sd || $ed) — @endif {{ $ed ?? '—' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center gap-2 justify-end">
                                                <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                                                   class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('traveler.itineraries.destroy', $itinerary) }}"
                                                      onsubmit="return confirm('Delete this itinerary?');">
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
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
                                            No itineraries yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $itineraries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
