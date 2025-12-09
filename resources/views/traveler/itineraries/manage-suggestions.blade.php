<!-- resources/views/traveler/itineraries/manage-suggestions.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ $itinerary->name }} — Expert Suggestions
            </h2>

            <a href="{{ route('traveler.itineraries.show', $itinerary) }}"
               class="px-4 py-2 rounded-full bg-sand-200 dark:bg-ink-700 text-ink-800 dark:text-sand-100 text-sm
                      hover:bg-sand-300 dark:hover:bg-ink-600 shadow-soft transition">
                ← Back to Itinerary
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <x-flash-messages />

            {{-- Overview --}}
            <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                        rounded-3xl shadow-soft p-8">

                <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100 mb-4">
                    Suggestions Overview
                </h3>

                @php
                    $allSuggestions = $itinerary->items->flatMap(fn($item) => $item->expertSuggestions);
                    $pendingCount = $allSuggestions->filter(fn($s) => $s->status === 'pending')->count();
                    $approvedCount = $allSuggestions->filter(fn($s) => $s->status === 'approved')->count();
                    $rejectedCount = $allSuggestions->filter(fn($s) => $s->status === 'rejected')->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">Pending Review</p>
                        <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ $pendingCount }}</p>
                    </div>

                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <p class="text-sm text-green-800 dark:text-green-200">Approved</p>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $approvedCount }}</p>
                    </div>

                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                        <p class="text-sm text-red-800 dark:text-red-200">Rejected</p>
                        <p class="text-3xl font-bold text-red-900 dark:text-red-100">{{ $rejectedCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Suggestions by Item --}}
            @forelse($itinerary->items->groupBy('type') as $type => $items)
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                            rounded-3xl shadow-soft p-8">

                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100 mb-6">
                        {{ ucfirst($type) }}s
                    </h3>

                    @foreach($items as $item)
                        @if($item->expertSuggestions->count() > 0)
                            <div class="mb-8 pb-8 border-b border-sand-200 dark:border-ink-700 last:border-0 last:mb-0 last:pb-0">

                                {{-- Item Header --}}
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                                            {{ $item->title }}
                                        </h4>

                                        @if($item->location)
                                            <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                                📍 {{ $item->location }}
                                            </p>
                                        @endif

                                        @if($item->start_time)
                                            <p class="text-xs text-ink-500 dark:text-ink-400 mt-1">
                                                🕐 {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}
                                                @if($item->end_time)
                                                    - {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Suggestions for this Item --}}
                                <div class="space-y-4">
                                    @foreach($item->expertSuggestions as $suggestion)
                                        <div x-data="{ showDetails: false }"
                                             :class="{
                                                 'bg-yellow-50 dark:bg-yellow-900/10 border-yellow-200 dark:border-yellow-800': suggestion.status === 'pending',
                                                 'bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-800': suggestion.status === 'approved',
                                                 'bg-red-50 dark:bg-red-900/10 border-red-200 dark:border-red-800': suggestion.status === 'rejected'
                                             }"
                                             class="p-4 border rounded-xl">

                                            {{-- Suggestion Content --}}
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    {{-- Suggested Activity --}}
                                                    <div>
                                                        @if($suggestion->type === 'replacement' && $suggestion->place)
                                                            <h5 class="font-semibold text-ink-900 dark:text-ink-100">
                                                                {{ $suggestion->place->name }}
                                                            </h5>
                                                            <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                                                {{ $suggestion->place->address ?? $suggestion->place->location }}
                                                            </p>
                                                            @if($suggestion->place->rating)
                                                                <p class="text-xs text-copper mt-1">
                                                                    ⭐ {{ $suggestion->place->rating }} ({{ $suggestion->place->num_reviews ?? 0 }} reviews)
                                                                </p>
                                                            @endif
                                                        @elseif($suggestion->type === 'new_place' && $suggestion->placeSuggestion)
                                                            <h5 class="font-semibold text-ink-900 dark:text-ink-100">
                                                                {{ $suggestion->placeSuggestion->name }} <span class="text-xs bg-copper/20 text-copper px-2 py-1 rounded ml-2">New</span>
                                                            </h5>
                                                            <p class="text-sm text-ink-600 dark:text-ink-300 mt-1">
                                                                {{ $suggestion->placeSuggestion->location ?? $suggestion->placeSuggestion->description }}
                                                            </p>
                                                            @if($suggestion->placeSuggestion->rating)
                                                                <p class="text-xs text-copper mt-1">
                                                                    ⭐ {{ $suggestion->placeSuggestion->rating }}
                                                                </p>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    {{-- Reason --}}
                                                    @if($suggestion->reason)
                                                        <div class="mt-3 p-3 bg-white dark:bg-ink-800 rounded-lg border border-sand-200 dark:border-ink-700">
                                                            <p class="text-xs text-ink-600 dark:text-ink-400 font-semibold uppercase tracking-wide">
                                                                Expert's Reason
                                                            </p>
                                                            <p class="text-sm text-ink-900 dark:text-sand-100 mt-1 italic">
                                                                "{{ $suggestion->reason }}"
                                                            </p>
                                                        </div>
                                                    @endif

                                                    {{-- Expert Info --}}
                                                    <div class="mt-3 text-xs text-ink-600 dark:text-ink-400">
                                                        <p>
                                                            Suggested by <strong>{{ $suggestion->expert->user->name }}</strong>
                                                            on {{ $suggestion->created_at->format('M d, Y') }}
                                                        </p>
                                                    </div>

                                                    {{-- Status Badge --}}
                                                    <div class="mt-3">
                                                        @if($suggestion->status === 'pending')
                                                            <span class="inline-block px-3 py-1 bg-yellow-200 dark:bg-yellow-700 text-yellow-900 dark:text-yellow-100 text-xs font-semibold rounded-full">
                                                                Awaiting Your Review
                                                            </span>
                                                        @elseif($suggestion->status === 'approved')
                                                            <span class="inline-block px-3 py-1 bg-green-200 dark:bg-green-700 text-green-900 dark:text-green-100 text-xs font-semibold rounded-full">
                                                                ✓ Approved
                                                            </span>
                                                        @else
                                                            <span class="inline-block px-3 py-1 bg-red-200 dark:bg-red-700 text-red-900 dark:text-red-100 text-xs font-semibold rounded-full">
                                                                ✗ Rejected
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Actions (only for pending) --}}
                                                @if($suggestion->status === 'pending')
                                                    <div class="ml-4 flex gap-2">
                                                        <form method="POST" action="{{ route('traveler.suggestions.approve', $suggestion) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium
                                                                           transition duration-200 shadow-soft">
                                                                Approve
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('traveler.suggestions.reject', $suggestion) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium
                                                                           transition duration-200 shadow-soft">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @empty
                <div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700 
                            rounded-3xl shadow-soft p-8 text-center">
                    <p class="text-ink-600 dark:text-ink-300 text-lg">
                        No expert suggestions yet. Once your assigned expert reviews the itinerary, their suggestions will appear here.
                    </p>
                </div>
            @endforelse

            {{-- Info Card --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-3xl p-8">
                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">How Suggestions Work</h4>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                    <li>✓ Experts can suggest replacements from existing places in our database</li>
                    <li>✓ Experts can submit new place suggestions that you approve</li>
                    <li>✓ You can approve or reject each suggestion individually</li>
                    <li>✓ Approved suggestions automatically update your itinerary</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
