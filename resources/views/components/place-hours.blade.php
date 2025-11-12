@props(['hours' => null])

@php
    // Normalize hours into a consistent keyed array:
    $daysOfWeek = [
        'monday', 'tuesday', 'wednesday', 'thursday',
        'friday', 'saturday', 'sunday'
    ];

    $normalized = [];

    if (is_array($hours)) {
        // Case 1: Already keyed by day
        foreach ($daysOfWeek as $day) {
            if (isset($hours[$day])) {
                $normalized[$day] = $hours[$day];
            }
        }

        // Case 2: Google Places style: "weekday_text" => ["Monday: ...", ...]
        if (isset($hours['weekday_text']) && is_array($hours['weekday_text'])) {
            foreach ($hours['weekday_text'] as $entry) {
                // Format example: "Monday: 8 AM–5 PM"
                [$day, $time] = explode(':', $entry, 2);
                $normalized[strtolower($day)] = trim($time);
            }
        }
    }
@endphp

@if (!empty($normalized))
    <div class="mt-8">
        <h3 class="text-sm uppercase font-semibold text-copper-700 dark:text-copper-300 mb-2">
            Hours
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-1 text-sm">
            @foreach ($normalized as $day => $time)
                <div class="flex justify-between pr-4">
                    <span class="font-medium text-ink-800 dark:text-sand-200">
                        {{ ucfirst($day) }}
                    </span>

                    <span class="text-ink-700 dark:text-sand-300">
                        {{ $time ?: '—' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endif
