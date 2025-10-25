{{-- resources/views/traveler/itineraries/partials/item-row-display.blade.php --}}
@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

    $st = $item->start_time ? Carbon::parse($item->start_time) : null;
    $et = $item->end_time ? Carbon::parse($item->end_time) : null;
@endphp

<div x-data="{ expanded: false }"
     class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 
            border border-sand-200 dark:border-ink-700 rounded-xl px-4 py-4 shadow-sm 
            hover:shadow-glow hover:scale-[1.01] transition-all duration-200 
            overflow-hidden break-words bg-white/70 dark:bg-sand-800/60">

    {{-- Left side: item info --}}
    <div class="flex-1 min-w-0">

        {{-- Row 1: Title --}}
        <p class="font-semibold text-lg text-ink-900 dark:text-ink-100 break-words">
            {{ $item->title ?? 'Untitled' }}
        </p>

        {{-- Row 2: Rating --}}
        @if (!empty($item->rating))
            <p class="mt-1 text-sm text-ink-800 dark:text-ink-200">
                <span class="font-semibold text-copper-700 dark:text-copper-300">Rating:</span>
                <span class="text-amber-500 font-semibold">★ {{ number_format($item->rating, 1) }}</span>
            </p>
        @endif

        {{-- Row 3: Address --}}
        @if (!empty($item->address))
            <p class="mt-1 text-sm text-ink-800 dark:text-ink-200 break-words">
                <span class="font-semibold text-copper-700 dark:text-copper-300">Address:</span>
                @linkify($item->address)
            </p>
        @endif

        {{-- Row 4: Google Maps Link --}}
        @if (!empty($item->google_maps_url))
            <div class="mt-1">
                <a href="{{ $item->google_maps_url }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-1 text-sm text-blue-600 dark:text-blue-400 hover:underline break-words">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    View on Google Maps
                </a>
            </div>
        @endif

        {{-- Row 5: Type (Activity/Food) --}}
        @if (!empty($item->type))
            <p class="mt-2 text-xs uppercase tracking-wide text-copper-700 dark:text-copper-300 font-semibold">
                {{ ucfirst($item->type) }}
            </p>
        @endif

        {{-- Row 6: Details (collapsible if long) --}}
        @if (!empty($item->details))
            @php
                $isLong = Str::length(strip_tags($item->details)) > 200;
            @endphp

            <p class="text-sm text-ink-600 dark:text-sand-200 mt-3 break-words transition-all duration-200 ease-in-out"
               :class="{ 'line-clamp-none': expanded, 'line-clamp-3': !expanded && {{ $isLong ? 'true' : 'false' }} }">
                @linkify($item->details)
            </p>

            @if ($isLong)
                <button type="button"
                        @click="expanded = !expanded"
                        class="mt-1 text-xs text-copper hover:underline focus:outline-none">
                    <span x-show="!expanded">Read more</span>
                    <span x-show="expanded">Show less</span>
                </button>
            @endif
        @endif

        {{-- Row 7: Preference tags --}}
        @if (!empty($item->place) && $item->place->preferences && $item->place->preferences->isNotEmpty())
            <div class="mt-4">
                <h4 class="text-xs font-semibold text-ink-800 dark:text-ink-200 uppercase tracking-wide">
                    Why this was recommended
                </h4>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($item->place->preferences as $pref)
                        <span class="px-2 py-1 text-xs rounded-full bg-copper-100 text-copper-800 dark:bg-copper-800 dark:text-copper-100">
                            {{ $pref->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Right side: times --}}
    <div class="text-right text-sm text-ink-700 dark:text-sand-100 shrink-0">
        <p>
            {{ $st ? $st->format('g:ia') : '—' }} – {{ $et ? $et->format('g:ia') : '—' }}
        </p>
    </div>
</div>
