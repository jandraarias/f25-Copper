@props(['class' => ''])

<span {{ $attributes->merge([
    'class' => 'font-extrabold tracking-tight ' . $class
]) }}>
    {{ config('app.name', 'ItinerEase') }}
</span>
