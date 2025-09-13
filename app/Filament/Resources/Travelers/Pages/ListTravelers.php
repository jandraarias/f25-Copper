<?php

namespace App\Filament\Resources\Travelers\Pages;

use App\Filament\Resources\Travelers\TravelerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTravelers extends ListRecords
{
    protected static string $resource = TravelerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
