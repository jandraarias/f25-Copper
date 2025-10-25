{{-- resources/views/traveler/itineraries/partials/item-row.blade.php --}}
@php
    // This partial acts as a smart router:
    // - When $isPublicView is true, it renders the display-only "card" layout
    // - Otherwise, it loads the editable table-row version for the edit view
@endphp

@if (!empty($isPublicView))
    {{-- Display-only version (used in show itinerary view) --}}
    @include('traveler.itineraries.partials.item-row-display', ['item' => $item])
@else
    {{-- Editable table-row version (used in edit itinerary view) --}}
    @include('traveler.itineraries.partials.item-row-edit', ['item' => $item])
@endif
