<?php

namespace App\Filament\Resources\ItineraryItems\Pages;

use App\Filament\Resources\ItineraryItems\ItineraryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItineraryItems extends ListRecords
{
    protected static string $resource = ItineraryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
