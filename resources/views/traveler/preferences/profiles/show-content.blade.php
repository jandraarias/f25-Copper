{{-- resources/views/traveler/preferences/profiles/show-content.blade.php --}}

@php
    $preferences = $preferences ?? $preferenceProfile->preferences;

    $activities = $preferences->where('key', 'activity');
    $budget = $preferences->where('key', 'budget')->first()?->value;
    $dietary = $preferences->where('key', 'dietary')->pluck('value');
    $cuisine = $preferences->where('key', 'cuisine')->pluck('value');

    $sections = [
        [
            'title' => 'Activities',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round"
                         d="M12 3v3m0 12v3m9-9h-3M6 12H3m12.364-6.364l-2.121 2.121m0 8.486
                            2.121 2.121M6.343 6.343l2.121 2.121m0 8.486-2.121 2.121"/>',
            'items' => $activities,
            'empty' => 'No activities selected yet.',
            'render' => fn($a) => $a->value,
            'color' => 'bg-copper-light text-copper-dark dark:bg-copper-dark/30 dark:text-copper-light',
        ],
        [
            'title' => 'Budget',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round"
                         d="M12 3a9 9 0 100 18 9 9 0 000-18zM8 12h8" />',
            'items' => collect([$budget])->filter(),
            'empty' => 'No budget preference set.',
            'render' => fn($b) => ucfirst(str_replace("_", " ", $b)),
            'color' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-100',
        ],
        [
            'title' => 'Dietary Preferences',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round"
                         d="M12 3v18m9-9H3" />',
            'items' => $dietary,
            'empty' => 'No dietary preferences yet.',
            'render' => fn($d) => $d,
            'color' => 'bg-forest-100 text-forest dark:bg-forest/20 dark:text-forest-100',
        ],
        [
            'title' => 'Cuisine Preferences',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round"
                         d="M4 6h16M4 12h16M4 18h16" />',
            'items' => $cuisine,
            'empty' => 'No cuisine preferences set.',
            'render' => fn($c) => $c,
            'color' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-100',
        ],
    ];
@endphp

@foreach ($sections as $section)
    <div class="bg-white dark:bg-sand-800 text-ink-900 dark:text-ink-200
                shadow-soft dark:shadow-glow-dark rounded-3xl p-8
                border border-sand-200 dark:border-ink-700 mb-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 flex items-center justify-center rounded-full
                        bg-copper/10 dark:bg-copper/20 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-copper">
                    {!! $section['icon'] !!}
                </svg>
            </div>
            <h3 class="text-lg font-semibold">{{ $section['title'] }}</h3>
        </div>

        @if($section['items']->isEmpty())
            <p class="text-sm text-ink-500 dark:text-sand-100 italic">{{ $section['empty'] }}</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($section['items'] as $item)
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full
                                 {{ $section['color'] }} text-sm font-medium">
                        {{ ($section['render'])($item) }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>
@endforeach
