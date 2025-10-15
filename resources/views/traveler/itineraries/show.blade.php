<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ $itinerary->name }}
            </h2>

            <a href="{{ route('traveler.itineraries.index') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper 
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03] 
                      transition-all duration-200 ease-out font-medium shadow-soft">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-10">

            {{-- Overview Card --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <x-flash-messages />

                    <h3 class="text-xl font-semibold mb-6">Overview</h3>

                    <div class="space-y-3 text-ink-800 dark:text-ink-200">
                        <p>
                            <span class="font-semibold text-ink-900 dark:text-ink-100">Description:</span>
                            {{ $itinerary->description ?? '—' }}
                        </p>
                        <p>
                            <span class="font-semibold text-ink-900 dark:text-ink-100">Countries:</span>
                            {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}
                        </p>
                        <p>
                            <span class="font-semibold text-ink-900 dark:text-ink-100">Destination:</span>
                            {{ $itinerary->destination ?? '—' }}
                        </p>
                        <p>
                            <span class="font-semibold text-ink-900 dark:text-ink-100">Dates:</span>
                            {{ $itinerary->start_date?->format('M j, Y') ?? '—' }}
                            —
                            {{ $itinerary->end_date?->format('M j, Y') ?? '—' }}
                        </p>
                    </div>

                    <div class="mt-10 flex gap-4 justify-end">
                        <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                           class="group flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft 
                                  hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h2m2 0h.01M15 9h.01M13 9h.01M11 9h.01M9 9h.01M7 9h.01M5 9h.01M3 9h.01M21 9h.01M12 19v2M12 3v2" />
                            </svg>
                            Edit Itinerary
                        </a>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    <h3 class="text-xl font-semibold mb-6">Items</h3>

                    @if ($itinerary->items->isEmpty())
                        <div class="text-center py-10 text-ink-600 dark:text-ink-300">
                            <p class="text-lg">No items yet for this itinerary.</p>
                            <p class="text-sm mt-2">Add some via the “Edit” page to start planning!</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-sand-200 dark:divide-ink-700">
                                <thead class="bg-sand dark:bg-sand-900/40">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-ink-700 dark:text-ink-300 uppercase">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-ink-700 dark:text-ink-300 uppercase">Title</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-ink-700 dark:text-ink-300 uppercase">Start</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-ink-700 dark:text-ink-300 uppercase">End</th>
                                        <th class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-sand-800 divide-y divide-sand-200 dark:divide-ink-700">
                                    @foreach ($itinerary->items as $item)
                                        @php
                                            $st = $item->start_time ? \Illuminate\Support\Carbon::parse($item->start_time) : null;
                                            $et = $item->end_time ? \Illuminate\Support\Carbon::parse($item->end_time) : null;
                                        @endphp
                                        <tr class="hover:bg-sand-50 dark:hover:bg-sand-900/50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-200">{{ ucfirst($item->type) }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-200">{{ $item->title }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-ink-700 dark:text-ink-300">{{ $st ? $st->format('M j, Y g:ia') : '—' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-ink-700 dark:text-ink-300">{{ $et ? $et->format('M j, Y g:ia') : '—' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                                                   class="px-3 py-1.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200 
                                                          hover:text-copper hover:border-copper hover:shadow-glow hover:scale-[1.03] 
                                                          transition-all duration-200 ease-out text-sm">
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
