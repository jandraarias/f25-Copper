{{-- resources/views/expert/itineraries/item-row.blade.php --}}
@php
    /**
     * This file mirrors the Traveler version:
     * - If $isPublicView is true → show a read-only display card
     * - Otherwise → show editable collapse card (item-row-edit)
     */
@endphp

@if (!empty($isPublicView))
    {{-- Display-only version for expert.show page --}}
    @include('expert.itineraries.item-row-display', ['item' => $item])
@else
    {{-- Editable, collapsible version for expert.edit page --}}
    @include('expert.itineraries.item-row-edit', ['item' => $item])
@endif
