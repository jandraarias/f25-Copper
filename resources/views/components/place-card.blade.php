@props(['place'])

<a href="{{ route('places.show', $place) }}"
   class="group block overflow-hidden rounded-3xl border border-sand-200 dark:border-ink-700 
          bg-white dark:bg-sand-800 shadow-soft hover:shadow-glow hover:-translate-y-0.5 
          transition-all duration-300">

    {{-- IMAGE --}}
    <div class="relative h-48 w-full overflow-hidden">
        <img src="{{ $place->photo_url ?? asset('img/placeholder.jpg') }}"
             alt="{{ $place->name }}"
             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
    </div>

    {{-- CONTENT --}}
    <div class="p-5 space-y-3">

        {{-- NAME --}}
        <h3 class="text-lg font-semibold text-ink-900 dark:text-sand-100 truncate">
            {{ $place->name }}
        </h3>

        {{-- RATING + PRICE + CATEGORY --}}
        <div class="flex flex-wrap items-center gap-3 text-sm">

            {{-- Rating --}}
            @if ($place->rating)
                <div class="flex items-center gap-1 text-amber-500 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24">
                        <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.853 1.516 8.276L12 18.897l-7.452 4.538L6.064 15.16 0 9.306l8.332-1.151z"/>
                    </svg>
                    {{ number_format($place->rating, 1) }}
                </div>
            @endif

            {{-- Price --}}
            @if ($place->price_level)
                <span class="px-2 py-0.5 rounded-full bg-copper-100 text-copper-800 
                             dark:bg-copper-800 dark:text-copper-100 text-xs font-semibold">
                    {{ str_repeat('$', $place->price_level) }}
                </span>
            @endif

            {{-- Category --}}
            @if ($place->main_category)
                <span class="px-2 py-0.5 rounded-full bg-sand-200 dark:bg-ink-700 text-xs font-semibold">
                    {{ $place->main_category }}
                </span>
            @endif
        </div>

        {{-- DESCRIPTION SNIPPET --}}
        @if ($place->description)
            <p class="text-sm text-ink-700 dark:text-sand-200 line-clamp-2">
                {{ strip_tags($place->description) }}
            </p>
        @endif

    </div>
</a>
