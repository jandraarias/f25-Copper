@foreach ($reviews as $review)
    @include('reviews._review-card', ['review' => $review])
@endforeach
