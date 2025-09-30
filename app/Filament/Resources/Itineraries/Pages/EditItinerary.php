<?php

namespace App\Filament\Resources\Itineraries\Pages;

use App\Filament\Resources\Itineraries\ItineraryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditItinerary extends EditRecord
{
    protected static string $resource = ItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
