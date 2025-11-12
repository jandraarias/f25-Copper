@props(['reviews'])

<div class="space-y-6">

    {{-- EMPTY STATE --}}
    @if ($reviews->total() === 0)
        <p class="text-ink-600 dark:text-sand-300 text-sm">
            No reviews match your filters.
        </p>
    @else

        {{-- TOP SUMMARY --}}
        <div class="flex items-center justify-between text-sm text-ink-600 dark:text-sand-300">
            <span>
                Showing 
                {{ $reviews->firstItem() ?? 0 }}–{{ $reviews->lastItem() ?? 0 }}
                of {{ $reviews->total() }}
            </span>
        </div>

        {{-- REVIEW CARDS --}}
        <div class="space-y-6">
            @foreach ($reviews as $review)
                @include('reviews._review-card', ['review' => $review])
            @endforeach
        </div>

        {{-- BOTTOM SUMMARY --}}
        <div class="flex items-center justify-between text-sm text-ink-600 dark:text-sand-300 pt-4 border-t border-sand-200 dark:border-ink-700">

            <span>
                Showing 
                {{ $reviews->firstItem() ?? 0 }}–{{ $reviews->lastItem() ?? 0 }}
                of {{ $reviews->total() }}
            </span>

            {{-- PAGINATION --}}
            {{ $reviews->withQueryString()->links() }}
        </div>

    @endif

</div>
