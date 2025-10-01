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
                    <x-flash-messages />

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Countries</th>
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
                                            <div class="font-medium">{{ $itinerary->name }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-gray-700 dark:text-gray-300">
                                                {{ $itinerary->countries->pluck('name')->join(', ') }}
                                            </div>
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
                                            <!-- actions -->
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
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
