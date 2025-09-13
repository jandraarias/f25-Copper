<?php

namespace App\Filament\Resources\Preferences\Pages;

use App\Filament\Resources\Preferences\PreferenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreferences extends ListRecords
{
    protected static string $resource = PreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
