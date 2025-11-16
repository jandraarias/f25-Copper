@php
    use Illuminate\Support\Str;

    $textIsLong = Str::length(strip_tags($review->text)) > 220;

    $ownerResponse =
        $review->owner_response
        ?? $review->owner_response_translated
        ?? null;

    // Normalize experience details
    $details = collect($review->meta['experience_details'] ?? [])
        ->map(function ($d) {
            if (is_array($d) && isset($d['name'], $d['value'])) {
                return ['name' => $d['name'], 'value' => $d['value']];
            }
            return null;
        })
        ->filter()
        ->values();

    // Normalize photos
    $photos = $review->review_photos;

    if (is_string($photos)) {
        $photos = json_decode($photos, true);
    }

    $photos = collect($photos ?? [])
        ->filter(fn ($p) => is_array($p) && !empty($p['url']))
        ->take(6)
        ->values();
@endphp

<div
    x-data="{ expanded: false, textIsLong: {{ $textIsLong ? 'true' : 'false' }} }"
    class="rounded-3xl border border-sand-200 dark:border-ink-700
           bg-white dark:bg-sand-800 shadow-soft p-6
           transition-all duration-200 hover:shadow-glow">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mb-4">

        <div class="flex flex-col">
            <h4 class="text-lg font-semibold text-ink-900 dark:text-sand-100">
                {{ $review->author ?? 'Anonymous' }}
            </h4>

            @if ($review->published_at_date)
                <span class="text-xs text-ink-600 dark:text-sand-300">
                    {{ $review->published_at_date->diffForHumans() }}
                </span>
            @endif
        </div>

        @if ($review->rating)
            <div class="flex items-center gap-1 text-amber-500 font-semibold shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-amber-400" viewBox="0 0 24 24">
                    <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.853 1.516 8.276L12 18.897l-7.452 4.538L6.064 15.16 0 9.306l8.332-1.151z"/>
                </svg>
                {{ number_format($review->rating, 1) }}
            </div>
        @endif
    </div>

    {{-- REVIEW TEXT --}}
    @if ($review->text)
        <p class="text-ink-800 dark:text-sand-100 leading-relaxed text-sm mb-3"
           :class="{
                'line-clamp-none': expanded,
                'line-clamp-3': !expanded && textIsLong
           }">
            @linkify($review->text)
        </p>

        @if ($textIsLong)
            <button
                @click="expanded = !expanded"
                class="text-xs text-copper-700 dark:text-copper-300 hover:underline focus:outline-none mb-2">
                <span x-show="!expanded">Read more</span>
                <span x-show="expanded">Show less</span>
            </button>
        @endif
    @endif

    {{-- EXPERIENCE DETAILS --}}
    @if ($details->isNotEmpty())
        <div class="mt-4">
            <h5 class="text-xs uppercase font-semibold text-copper-700 dark:text-copper-300 mb-2">
                Experience Details
            </h5>

            <div class="flex flex-wrap gap-2">
                @foreach ($details as $d)
                    <span class="px-2 py-1 rounded-full bg-sand-100 dark:bg-ink-700
                                 text-xs text-ink-700 dark:text-sand-200">
                        {{ $d['name'] }}:
                        @if (is_numeric($d['value']))
                            {{ number_format($d['value'], 1) }}/5
                        @else
                            {{ $d['value'] }}
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- PHOTOS --}}
    @if ($photos->isNotEmpty())
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach ($photos as $photo)
                <a href="{{ $photo['url'] }}"
                   target="_blank"
                   class="block rounded-xl overflow-hidden shadow-soft
                          hover:shadow-glow transition-transform hover:scale-[1.02]">
                    <img src="{{ $photo['url'] }}"
                         alt="Review photo"
                         class="object-cover w-full h-32 sm:h-40">
                </a>
            @endforeach
        </div>
    @endif

    {{-- OWNER RESPONSE --}}
    @if ($ownerResponse)
        <div class="mt-8 border border-sand-200 dark:border-ink-700 rounded-2xl
                    bg-sand-50 dark:bg-sand-900 p-4">

            <h5 class="text-sm font-semibold text-copper-700 dark:text-copper-300 mb-2">
                Response from the owner
            </h5>

            <p class="text-xs text-ink-800 dark:text-sand-200 leading-relaxed">
                @linkify($ownerResponse)
            </p>

            @if ($review->owner_response_publish_date)
                <p class="text-[0.7rem] mt-2 text-ink-500 dark:text-sand-400">
                    {{ $review->owner_response_publish_date->diffForHumans() }}
                </p>
            @endif
        </div>
    @endif

</div>
