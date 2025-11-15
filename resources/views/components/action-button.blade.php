@props([
    'type' => 'view',  // view | edit | delete
    'href' => null,    // For links
    'method' => null,  // For delete forms (DELETE)
])

@php
    // Map types to styles
    $styles = [
        'view' => [
            'label' => 'View',
            'border' => 'border-ink-500',
            'text' => 'text-ink-700 dark:text-sand-100',
            'hover' => 'hover:border-copper hover:text-copper',
            'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943...'
        ],
        'edit' => [
            'label' => 'Edit',
            'border' => 'border-copper',
            'text' => 'text-copper',
            'hover' => 'hover:bg-copper hover:text-white',
            'icon' => 'M15.232 5.232a3 3 0 114.243 4.243L7.5...'
        ],
        'delete' => [
            'label' => 'Delete',
            'border' => 'border-red-400',
            'text' => 'text-red-500',
            'hover' => 'hover:bg-red-500 hover:text-white',
            'icon' => 'M6 18L18 6M6 6l12 12',
        ],
    ];

    $style = $styles[$type] ?? $styles['view'];
@endphp

@if($type === 'delete')
    <form method="POST" action="{{ $href }}" onsubmit="return confirm('Are you sure?')">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="group flex items-center gap-2 px-3 py-1.5 rounded-full border text-sm
                   {{ $style['border'] }} {{ $style['text'] }}
                   hover:shadow-glow hover:scale-[1.03]
                   transition-all duration-200 ease-out {{ $style['hover'] }}">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['icon'] }}" />
            </svg>

            <span>{{ $style['label'] }}</span>
        </button>
    </form>
@else
    <a href="{{ $href }}"
       class="group flex items-center gap-2 px-3 py-1.5 rounded-full border text-sm
              {{ $style['border'] }} {{ $style['text'] }}
              hover:shadow-glow hover:scale-[1.03]
              transition-all duration-200 ease-out {{ $style['hover'] }}">
        
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['icon'] }}" />
        </svg>

        <span>{{ $style['label'] }}</span>
    </a>
@endif
