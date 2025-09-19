<?php

namespace App\Filament\Resources\Travelers\RelationManagers;

use App\Filament\Resources\Itineraries\ItineraryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ItinerariesRelationManager extends RelationManager
{
    protected static string $relationship = 'Itineraries';

    protected static ?string $relatedResource = ItineraryResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
