{{-- resources/views/traveler/itineraries/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Itinerary Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Name</div>
                            <div class="font-medium text-lg">{{ $itinerary->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Destination</div>
                            <div class="font-medium">{{ $itinerary->destination ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Start</div>
                            <div class="font-medium">
                                {{ optional($itinerary->start_date)->format('M j, Y') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">End</div>
                            <div class="font-medium">
                                {{ optional($itinerary->end_date)->format('M j, Y') }}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Description</div>
                            <div class="whitespace-pre-wrap">{{ $itinerary->description ?: '—' }}</div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                           class="px-3 py-2 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                            Edit
                        </a>
                        <a href="{{ route('traveler.itineraries.index') }}"
                           class="px-3 py-2 rounded border border-gray-300 dark:border-gray-600 text-sm">
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">Items</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">When</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($itinerary->items as $item)
                                    @php
                                        $st = optional($item->start_time);
                                        $ed = optional($item->end_time);
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3">{{ ucfirst($item->type) }}</td>
                                        <td class="px-4 py-3">{{ $item->title }}</td>
                                        <td class="px-4 py-3">
                                            {{ $st ? $st->format('M j, Y g:i A') : '—' }}
                                            @if($st || $ed) — @endif
                                            {{ $ed ? $ed->format('M j, Y g:i A') : '—' }}
                                        </td>
                                        <td class="px-4 py-3">{{ $item->location ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
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
