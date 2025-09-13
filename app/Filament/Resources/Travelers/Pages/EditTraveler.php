<?php

namespace App\Filament\Resources\Travelers\Pages;

use App\Filament\Resources\Travelers\TravelerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTraveler extends EditRecord
{
    protected static string $resource = TravelerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
