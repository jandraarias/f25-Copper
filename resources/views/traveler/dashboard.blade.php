<!-- resources/views/traveler/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Traveler Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-semibold">Welcome, {{ $traveler->name }}!</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Here’s your travel info.</p>
                </div>
            </div>

            <!-- Itineraries -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Your Itineraries</h3>
                    @forelse ($traveler->itineraries as $itinerary)
                        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <p class="font-semibold">{{ $itinerary->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $itinerary->start_date->format('M d, Y') }}
                                →
                                {{ $itinerary->end_date->format('M d, Y') }}
                            </p>
                            <ul class="list-disc ml-6 mt-2 text-sm">
                                @foreach ($itinerary->items as $item)
                                    <li>
                                        <span class="font-medium">{{ $item->type }}</span>:
                                        {{ $item->title }} — {{ $item->location }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">You don’t have any itineraries yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Preferences -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Your Preferences</h3>
                    @forelse ($traveler->preferenceProfiles as $profile)
                        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <p class="font-semibold">{{ $profile->name }}</p>
                            <ul class="list-disc ml-6 mt-2 text-sm">
                                @foreach ($profile->preferences as $preference)
                                    <li>{{ $preference->key }}: {{ $preference->value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">You don’t have any preferences yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
