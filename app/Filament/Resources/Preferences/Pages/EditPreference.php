<?php

namespace App\Filament\Resources\Preferences\Pages;

use App\Filament\Resources\Preferences\PreferenceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreference extends EditRecord
{
    protected static string $resource = PreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
