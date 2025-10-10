{{-- resources/views/traveler/preferences/profiles/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900">
                {{ $preferenceProfile->name }}
            </h2>
            <a href="{{ route('traveler.preference-profiles.edit', $preferenceProfile) }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft 
                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-200 group-hover:rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232a3 3 0 114.243 4.243L7.5 21H3v-4.5l12.232-11.268z"/>
                </svg>
                <span>Edit Profile</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <x-flash-messages />

            {{-- Overview --}}
            <div class="bg-white shadow-soft rounded-3xl p-8 border border-sand-200 hover:shadow-glow hover:scale-[1.01] transition-all duration-200 ease-out">
                <h3 class="text-lg font-semibold text-copper mb-2">Overview</h3>
                <p class="text-ink-700 leading-relaxed">
                    Your personalized travel profile brings together your interests, budgets, dietary needs,
                    and accommodation preferences to help us tailor every trip to you.
                </p>
            </div>

            @php
                $activities = $preferences->where('key', 'activity');
                $budgetMin = $preferences->where('key', 'budget_min')->first()?->value;
                $budgetMax = $preferences->where('key', 'budget_max')->first()?->value;
                $dietary = $preferences->where('key', 'dietary')->pluck('value');
                $accommodation = $preferences->where('key', 'accommodation')->pluck('value');
            @endphp

            {{-- Section Card Component --}}
            @php
                $sections = [
                    [
                        'title' => 'Activities',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m0 12v3m9-9h-3M6 12H3m12.364-6.364l-2.121 2.121m0 8.486 2.121 2.121M6.343 6.343l2.121 2.121m0 8.486-2.121 2.121"/>',
                        'items' => $activities,
                        'empty' => 'No activities selected yet.',
                        'render' => fn($a) => ($activityLookup[$a->value]['main'] ?? '') ? ($activityLookup[$a->value]['main'].' → '.$activityLookup[$a->value]['sub']) : $a->value,
                        'color' => 'bg-copper-light text-copper-dark',
                    ],
                    [
                        'title' => 'Budget',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 100 18 9 9 0 000-18zM8 12h8" />',
                        'items' => collect([['min' => $budgetMin, 'max' => $budgetMax]]),
                        'empty' => 'No budget preferences set.',
                        'render' => null,
                        'color' => 'text-ink-800',
                    ],
                    [
                        'title' => 'Dietary Preferences',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3" />',
                        'items' => $dietary,
                        'empty' => 'No dietary preferences yet.',
                        'render' => fn($diet) => $diet,
                        'color' => 'bg-forest-100 text-forest',
                    ],
                    [
                        'title' => 'Accommodation',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9v9a3 3 0 01-3 3H6a3 3 0 01-3-3v-9z" />',
                        'items' => $accommodation,
                        'empty' => 'No accommodation preferences yet.',
                        'render' => fn($a) => $a,
                        'color' => 'bg-sky-100 text-sky',
                    ],
                ];
            @endphp

            {{-- Loop through each section --}}
            @foreach($sections as $section)
                <div class="bg-white shadow-soft rounded-3xl p-8 border border-sand-200 hover:shadow-glow hover:scale-[1.01] transition-all duration-200 ease-out">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3 group transition-transform duration-200 group-hover:rotate-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-copper">
                                {!! $section['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ink-900">{{ $section['title'] }}</h3>
                    </div>

                    @if($section['title'] === 'Budget')
                        @php
                            $budget = $section['items']->first();
                        @endphp
                        @if(!$budget['min'] && !$budget['max'])
                            <p class="text-sm text-ink-500 italic">{{ $section['empty'] }}</p>
                        @else
                            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6 mt-2">
                                <p class="text-ink-800 text-base">
                                    <span class="font-medium">Minimum Budget:</span>
                                    <span class="text-copper font-semibold">${{ $budget['min'] ?: '—' }}</span>
                                </p>
                                <p class="text-ink-800 text-base">
                                    <span class="font-medium">Maximum Budget:</span>
                                    <span class="text-copper font-semibold">${{ $budget['max'] ?: '—' }}</span>
                                </p>
                            </div>
                        @endif
                    @else
                        @if($section['items']->isEmpty())
                            <p class="text-sm text-ink-500 italic">{{ $section['empty'] }}</p>
                        @else
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($section['items'] as $item)
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full {{ $section['color'] }} text-sm font-medium hover:scale-[1.05] transition-all duration-200 ease-out">
                                        {{ is_callable($section['render']) ? $section['render']($item) : $item }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
