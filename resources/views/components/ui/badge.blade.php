@props(['tone' => 'gray'])
@php
$base = 'inline-flex items-center rounded-full px-2 py-0.5 text-xs border';
$tones = [
  'blue' => $base.' bg-blue-50 text-blue-700 border-blue-200',
  'gray' => $base.' bg-gray-50 text-gray-700 border-gray-200',
  'green'=> $base.' bg-green-50 text-green-700 border-green-200',
];
@endphp
<span {{ $attributes->merge(['class' => $tones[$tone] ?? $tones['gray']]) }}>
  {{ $slot }}
</span>
