{{-- resources/views/traveler/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Traveler Dashboard') }}
        </h2>
    </x-slot>

    @php
        // Graceful fallback in case the controller didn't pass $traveler
        $traveler = $traveler ?? optional(auth()->user())->traveler;
        $itineraries = optional($traveler)->itineraries ?? collect();
        $preferenceProfiles = optional($traveler)->preferenceProfiles ?? collect();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-semibold">
                        Welcome, {{ $traveler->name ?? auth()->user()->name }}!
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Here’s your travel summary:
                    </p>
                </div>
            </div>

            <!-- Itineraries Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Recent Itineraries</h3>

                    @forelse ($itineraries as $itinerary)
                        @php
                            $sd = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M d, Y') : null;
                            $ed = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M d, Y') : null;
                        @endphp

                        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $itinerary->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $sd ?? '—' }} → {{ $ed ?? '—' }}
                                    </p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('traveler.itineraries.show', $itinerary) }}"
                                       class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                                        View
                                    </a>
                                </div>
                            </div>

                            @if($itinerary->items && $itinerary->items->count())
                                <ul class="list-disc ml-6 mt-2 text-sm text-gray-700 dark:text-gray-300">
                                    @foreach ($itinerary->items->take(3) as $item)
                                        <li>
                                            <span class="font-medium">{{ ucfirst($item->type) }}</span>:
                                            {{ $item->title }}
                                            @if(!empty($item->location))
                                                — {{ $item->location }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @if($itinerary->items->count() > 3)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">…and more</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    No items yet.
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">You don’t have any itineraries yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Preference Profiles Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Preference Profiles</h3>

                    @forelse ($preferenceProfiles as $profile)
                        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $profile->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $profile->preferences->count() }} Preferences
                                    </p>
                                </div>
                                <div class="shrink-0 flex gap-2">
                                    <a href="{{ route('traveler.preference-profiles.show', $profile) }}"
                                       class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">You don’t have any preferences yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
