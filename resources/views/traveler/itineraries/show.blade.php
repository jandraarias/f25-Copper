{{-- resources/views/traveler/itineraries/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $itinerary->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-8">
            {{-- Itinerary details --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-flash-messages />

                    <h3 class="text-lg font-semibold mb-4">Overview</h3>
                    <p><span class="font-medium">Description:</span> {{ $itinerary->description ?? '—' }}</p>
                    <p><span class="font-medium">Country:</span> {{ $itinerary->country }}</p>
                    <p><span class="font-medium">Destination:</span> {{ $itinerary->destination ?? '—' }}</p>
                    <p><span class="font-medium">Dates:</span>
                        {{ $itinerary->start_date?->format('M j, Y') ?? '—' }}
                        —
                        {{ $itinerary->end_date?->format('M j, Y') ?? '—' }}
                    </p>

                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                           class="px-4 py-2 rounded bg-blue-600 text-white">
                            Edit Itinerary
                        </a>
                        <a href="{{ route('traveler.itineraries.index') }}"
                           class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600">
                            Back to My Itineraries
                        </a>
                    </div>
                </div>
            </div>

            {{-- Items list --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Items</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Start</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">End</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($itinerary->items as $item)
                                    @php
                                        $st = $item->start_time ? \Illuminate\Support\Carbon::parse($item->start_time) : null;
                                        $et = $item->end_time ? \Illuminate\Support\Carbon::parse($item->end_time) : null;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ ucfirst($item->type) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $item->title }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $st ? $st->format('M j, Y g:ia') : '—' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $et ? $et->format('M j, Y g:ia') : '—' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                                               class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
                                            No items yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
