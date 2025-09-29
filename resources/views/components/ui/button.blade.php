@props(['variant' => 'primary', 'as' => 'button', 'type' => 'button', 'href' => null])
@php
$base = 'inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-medium transition';
$map = [
  'primary' => $base.' bg-brand text-white hover:bg-brand-700',
  'ghost'   => $base.' border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-900',
  'danger'  => $base.' bg-red-600 text-white hover:bg-red-700',
];
$classes = $map[$variant] ?? $map['primary'];
@endphp
@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
  <{{ $as }} type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</{{ $as }}>
@endif
