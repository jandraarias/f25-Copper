<?php

namespace App\Filament\Resources\Preferenceprofiles\Pages;

use App\Filament\Resources\Preferenceprofiles\PreferenceprofileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreferenceprofiles extends ListRecords
{
    protected static string $resource = PreferenceprofileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
