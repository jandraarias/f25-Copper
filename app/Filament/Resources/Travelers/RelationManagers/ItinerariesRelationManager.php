<?php

namespace App\Filament\Resources\Travelers\RelationManagers;

use App\Filament\Resources\Itineraries\ItineraryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItinerariesRelationManager extends RelationManager
{
    protected static string $relationship = 'itineraries';

    protected static ?string $relatedResource = ItineraryResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('start_date')->date(),
                TextColumn::make('end_date')->date(),
                TextColumn::make('country'),
                TextColumn::make('location'),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
