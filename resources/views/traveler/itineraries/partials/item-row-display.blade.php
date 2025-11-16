@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

    $st = $item->start_time ? Carbon::parse($item->start_time) : null;
    $et = $item->end_time ? Carbon::parse($item->end_time) : null;
    $isLong = !empty($item->details) && Str::length(strip_tags($item->details)) > 200;
    $place = $item->place;
    $photo = $place?->photo_url;
@endphp

<div x-data="{ expanded: false }"
     class="relative flex flex-col border border-sand-200 dark:border-ink-700 rounded-2xl bg-white dark:bg-sand-800 shadow-soft hover:shadow-glow hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

    {{-- Photo Section --}}
    @if ($place)
        <a href="{{ route('places.show', $place) }}" class="block w-full h-40 sm:h-48 overflow-hidden">
            <img src="{{ $photo }}" alt="{{ $place->name }} photo"
                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
        </a>
    @endif

    <div class="p-6 flex flex-col justify-between flex-1">

        {{-- Header Row --}}
        <div class="flex justify-between items-start mb-3">
            <div class="flex-1 min-w-0">
                {{-- Title --}}
                <h4 class="text-lg font-semibold text-ink-900 dark:text-ink-100 leading-tight break-words">
                    {{ $item->title ?? 'Untitled' }}
                </h4>

                {{-- Type --}}
                @if (!empty($item->type))
                    <span class="inline-block mt-1 text-xs uppercase tracking-wide text-copper-700 dark:text-copper-300 font-semibold">
                        {{ ucfirst($item->type) }}
                    </span>
                @endif
            </div>

            {{-- Time Badge --}}
            <div class="text-right shrink-0">
                <div class="px-3 py-1 text-xs font-semibold rounded-full bg-sand-100 text-ink-800 shadow-inner
                            dark:bg-ink-700 dark:text-sand-100 dark:shadow-[inset_0_0_6px_rgba(255,255,255,0.05)]">
                    {{ $st ? $st->format('g:ia') : '—' }} – {{ $et ? $et->format('g:ia') : '—' }}
                </div>
            </div>
        </div>

        {{-- Rating Section --}}
        @if (!empty($item->rating))
            <div class="mt-3 pt-3 border-t border-sand-200 dark:border-ink-700">
                <h5 class="text-xs uppercase tracking-wide font-semibold text-copper-700 dark:text-copper-300 mb-1">
                    Rating
                </h5>
                <div class="flex items-center gap-1 text-sm text-amber-500 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24">
                        <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.853 1.516 8.276L12 18.897l-7.452 4.538L6.064 15.16 0 9.306l8.332-1.151z"/>
                    </svg>
                    {{ number_format($item->rating, 1) }}
                </div>
            </div>
        @endif

        {{-- Address / Map Section --}}
        @if (!empty($item->location) || !empty($item->google_maps_url))
            <div class="mt-4 pt-3 border-t border-sand-200 dark:border-ink-700">
                <h5 class="text-xs uppercase tracking-wide font-semibold text-copper-700 dark:text-copper-300 mb-1">
                    Address
                </h5>
                @if (!empty($item->location))
                    <p class="text-sm text-ink-800 dark:text-sand-100 mb-1 break-words">
                        @linkify($item->location)
                    </p>
                @endif

                @if (!empty($item->google_maps_url))
                    <a href="{{ $item->google_maps_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        View on Google Maps
                    </a>
                @endif
            </div>
        @endif

        {{-- Details Section --}}
        @if (!empty($item->details))
            <div class="mt-4 pt-3 border-t border-sand-200 dark:border-ink-700">
                <h5 class="text-xs uppercase tracking-wide font-semibold text-copper-700 dark:text-copper-300 mb-1">Details</h5>
                <p class="text-sm text-ink-700 dark:text-sand-100 leading-relaxed transition-all duration-200 break-words"
                   :class="{ 'line-clamp-none': expanded, 'line-clamp-3': !expanded && {{ $isLong ? 'true' : 'false' }} }">
                    @linkify($item->details)
                </p>
                @if ($isLong)
                    <button @click="expanded = !expanded" class="mt-1 text-xs text-copper hover:underline">
                        <span x-show="!expanded">Read more</span>
                        <span x-show="expanded">Show less</span>
                    </button>
                @endif
            </div>
        @endif

        {{-- Preference Tags --}}
        @if (!empty($place) && $place->preferences && $place->preferences->isNotEmpty())
            <div class="mt-4 pt-3 border-t border-sand-200 dark:border-ink-700">
                <h5 class="text-xs uppercase tracking-wide font-semibold text-copper-700 dark:text-copper-300 mb-2">
                    Why This Was Recommended
                </h5>
                <div class="flex flex-wrap gap-2">
                    @foreach ($place->preferences as $pref)
                        <span class="px-2 py-0.5 text-xs rounded-full bg-copper-100 text-copper-800 dark:bg-copper-800 dark:text-copper-100">
                            {{ $pref->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- View Details Button --}}
        @if ($place)
            <div class="mt-5">
                <a href="{{ route('places.show', $place) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-full border border-copper text-copper hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200">
                    <span>View Details</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

    </div>
</div>