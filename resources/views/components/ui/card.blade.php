@props(['as' => 'div'])
<{{ $as }} {{ $attributes->merge([
  'class' => 'bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100/60 dark:border-gray-700'
]) }}>
  {{ $slot }}
</{{ $as }}>
