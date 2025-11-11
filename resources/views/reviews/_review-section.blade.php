@props(['reviews'])

<div class="bg-white dark:bg-sand-800 border border-sand-200 dark:border-ink-700
            rounded-3xl shadow-soft p-8 space-y-10">

    {{-- Title --}}
    <h3 class="text-2xl font-bold text-ink-900 dark:text-sand-100">
        Reviews
    </h3>

    {{-- Top Controls --}}
    <div class="space-y-6">

        {{-- Row 1: Per page + sort --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

            {{-- Per-page --}}
            <form method="GET" class="flex items-center gap-2">
                <label for="per_page" class="text-sm text-ink-700 dark:text-sand-200 whitespace-nowrap">
                    Per page:
                </label>

                <select name="per_page"
                        id="per_page"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-sand-300 dark:border-ink-600
                               bg-white dark:bg-ink-700 text-sm px-3 py-2
                               text-ink-800 dark:text-sand-100 shadow-sm">
                    @foreach([5,10,25,50,100] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>
                            {{ $n }}
                        </option>
                    @endforeach
                </select>

                {{-- preserve existing filters --}}
                @foreach(request()->except('per_page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
            </form>


            {{-- Sort --}}
            <form method="GET" class="flex items-center gap-2">
                <label for="sort" class="text-sm text-ink-700 dark:text-sand-200 whitespace-nowrap">
                    Sort:
                </label>

                <select name="sort"
                        id="sort"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-sand-300 dark:border-ink-600
                               bg-white dark:bg-ink-700 text-sm px-3 py-2
                               text-ink-800 dark:text-sand-100 shadow-sm">
                    <option value="newest"  {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest"  {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="highest" {{ request('sort') === 'highest' ? 'selected' : '' }}>Highest Rated</option>
                    <option value="lowest"  {{ request('sort') === 'lowest' ? 'selected' : '' }}>Lowest Rated</option>
                </select>

                {{-- preserve filters --}}
                @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
            </form>

        </div>


        {{-- Row 2: Filter Chips --}}
        <div class="flex flex-wrap gap-2">

            @php
                $chip = function($label, $value) {
                    $active = request('rating') == $value;
                    return [
                        'label'  => $label,
                        'active' => $active,
                        'url'    => request()->fullUrlWithQuery(['rating' => $value]),
                        'class'  => $active
                            ? 'bg-copper-700 text-white dark:bg-copper-500'
                            : 'bg-copper-100 text-copper-800 dark:bg-copper-800 dark:text-copper-100'
                    ];
                };

                $chips = [
                    $chip('5★ Only', 5),
                    $chip('4★+',   4),
                    $chip('3★+',   3),
                ];
            @endphp

            {{-- Rating Chips --}}
            @foreach ($chips as $c)
                <a href="{{ $c['url'] }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm
                          hover:opacity-80 transition {{ $c['class'] }}">
                    {{ $c['label'] }}
                </a>
            @endforeach

            {{-- Photos Only Chip --}}
            @php
                $withPhotos = request('photos') == '1';
                $photosUrl = request()->fullUrlWithQuery([
                    'photos' => $withPhotos ? null : 1
                ]);
            @endphp

            <a href="{{ $photosUrl }}"
               class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm transition
                      {{ $withPhotos
                          ? 'bg-copper-700 text-white dark:bg-copper-500'
                          : 'bg-copper-100 text-copper-800 dark:bg-copper-800 dark:text-copper-100' }}">
                With Photos
            </a>

        </div>

    </div>


    {{-- REVIEW LIST (includes summary + pagination) --}}
    <div class="pt-6 border-t border-sand-200 dark:border-ink-700">
        @include('reviews._review-list', ['reviews' => $reviews])
    </div>

</div>
