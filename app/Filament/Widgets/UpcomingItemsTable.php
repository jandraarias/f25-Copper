<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Itineraries\ItineraryResource;
use App\Models\Itinerary;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class UpcomingItemsTable extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Itineraries';
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $travelerId = Auth::user()?->traveler?->id;
        $today = Carbon::today();
        $horizon = Carbon::today()->addDays(14);

        return Itinerary::query()
            ->when($travelerId, fn (Builder $q) => $q->where('traveler_id', $travelerId))
            ->whereNotNull('start_date')
            ->whereBetween('start_date', [$today, $horizon])
            ->withCount('items')
            ->orderBy('start_date', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Itinerary')
                ->searchable()
                ->sortable(),

            TextColumn::make('location')
                ->label('City')
                ->toggleable(),

            TextColumn::make('country')
                ->toggleable(),

            TextColumn::make('start_date')
                ->label('Starts')
                ->date()
                ->sortable(),

            TextColumn::make('end_date')
                ->label('Ends')
                ->date()
                ->sortable(),

            TextColumn::make('items_count')
                ->label('Items')
                ->toggleable(),
        ];
    }

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return fn (Itinerary $record) =>
            ItineraryResource::getUrl('edit', ['record' => $record]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false; // keep it compact; it's a dashboard widget
    }
}
