<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Itineraries\ItineraryResource;
use App\Models\ItineraryItem;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class UpcomingItemsTable extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Items';
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $travelerId = Auth::user()?->traveler?->id;
        $now = Carbon::now();
        $horizon = Carbon::now()->addDays(14);

        return ItineraryItem::query()
            ->with(['itinerary'])
            ->whereHas('itinerary', fn ($q) =>
                $q->when($travelerId, fn ($qq) => $qq->where('traveler_id', $travelerId))
            )
            ->whereBetween('start_time', [$now, $horizon])
            ->orderBy('start_time', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->label('Item')->searchable()->sortable(),
            TextColumn::make('itinerary.name')->label('Itinerary')->searchable()->sortable(),
            TextColumn::make('start_time')->dateTime()->label('Starts')->sortable(),
            TextColumn::make('location')->toggleable(),
        ];
    }

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return fn (ItineraryItem $record) =>
            ItineraryResource::getUrl('edit', ['record' => $record->itinerary_id]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false; // keep it compact; it’s a dashboard widget
    }
}
