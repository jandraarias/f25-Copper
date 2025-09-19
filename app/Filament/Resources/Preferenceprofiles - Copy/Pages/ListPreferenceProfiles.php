<?php

namespace App\Filament\Resources\PreferenceProfiles\Pages;

use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreferenceProfiles extends ListRecords
{
    protected static string $resource = PreferenceProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
