<?php

namespace App\Filament\Resources\Preferenceprofiles\Pages;

use App\Filament\Resources\Preferenceprofiles\PreferenceprofileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreferenceprofile extends EditRecord
{
    protected static string $resource = PreferenceprofileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
