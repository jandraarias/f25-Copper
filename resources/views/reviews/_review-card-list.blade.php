@if ($reviews->count() === 0)
    <p class="text-ink-600 dark:text-sand-300 text-sm py-4">
        No reviews match your filters.
    </p>
@else
    @foreach ($reviews as $review)
        @include('reviews._review-card', ['review' => $review])
    @endforeach
@endif
