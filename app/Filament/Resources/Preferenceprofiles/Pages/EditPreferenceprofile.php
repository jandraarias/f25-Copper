<?php

namespace App\Filament\Resources\PreferenceProfiles\Pages;

use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreferenceProfile extends EditRecord
{
    protected static string $resource = PreferenceProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
