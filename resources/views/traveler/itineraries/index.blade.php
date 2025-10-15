<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-100">
                {{ __('My Itineraries') }}
            </h2>

            <a href="{{ route('traveler.itineraries.create') }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold
                      shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:rotate-6"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Create Itinerary
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <x-flash-messages />

            {{-- Table Card --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 rounded-3xl shadow-soft
                        hover:shadow-glow hover:scale-[1.005] transition-all duration-200 ease-out">
                <div class="p-8 text-ink-900 dark:text-ink-100">
                    @if($itineraries->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-sand-200 dark:divide-ink-700">
                                <thead class="bg-sand dark:bg-sand-900/40">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase">Countries</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase">Destination</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase">Dates</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-ink-800 dark:text-ink-100 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-sand-800 divide-y divide-sand-200 dark:divide-ink-700">
                                    @foreach($itineraries as $itinerary)
                                        <tr class="hover:bg-sand-50 dark:hover:bg-sand-900/50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-ink-700 dark:text-ink-200 font-medium">
                                                #{{ $itinerary->id }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-ink-900 dark:text-ink-100">{{ $itinerary->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-ink-700 dark:text-ink-200">
                                                {{ $itinerary->countries->pluck('name')->join(', ') ?: '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-ink-700 dark:text-ink-200">
                                                {{ $itinerary->destination ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-ink-700 dark:text-ink-200">
                                                @php
                                                    $sd = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M j, Y') : null;
                                                    $ed = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M j, Y') : null;
                                                @endphp
                                                {{ $sd ?? '—' }} @if($sd || $ed) — @endif {{ $ed ?? '—' }}
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <div class="flex items-center gap-2 justify-end">
                                                    {{-- View --}}
                                                    <a href="{{ route('traveler.itineraries.show', $itinerary) }}"
                                                       class="px-3 py-1.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200
                                                              hover:text-copper hover:border-copper hover:shadow-glow hover:scale-[1.03]
                                                              transition-all duration-200 ease-out text-sm">
                                                        View
                                                    </a>

                                                    {{-- Edit --}}
                                                    <a href="{{ route('traveler.itineraries.edit', $itinerary) }}"
                                                       class="px-3 py-1.5 rounded-full border border-copper text-copper text-sm
                                                              hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                                                              transition-all duration-200 ease-out">
                                                        Edit
                                                    </a>

                                                    {{-- Delete --}}
                                                    <form method="POST" action="{{ route('traveler.itineraries.destroy', $itinerary) }}"
                                                          onsubmit="return confirm('Delete this itinerary?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-3 py-1.5 rounded-full border border-red-400 text-red-500 text-sm
                                                                       hover:bg-red-500 hover:text-white hover:shadow-glow hover:scale-[1.03]
                                                                       transition-all duration-200 ease-out">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8">
                            {{ $itineraries->links() }}
                        </div>
                    @else
                        <div class="py-12 text-center text-ink-600 dark:text-ink-300">
                            <p class="text-lg font-medium mb-4">You don’t have any itineraries yet.</p>
                            <a href="{{ route('traveler.itineraries.create') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                                + Create Your First Itinerary
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
