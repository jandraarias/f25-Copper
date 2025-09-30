<?php

namespace App\Filament\Resources\ItineraryItems\Pages;

use App\Filament\Resources\ItineraryItems\ItineraryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditItineraryItem extends EditRecord
{
    protected static string $resource = ItineraryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
