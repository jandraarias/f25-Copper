<?php

namespace App\Filament\Resources\Travelers\RelationManagers;

use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PreferenceProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'PreferenceProfiles';

    protected static ?string $relatedResource = PreferenceProfileResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
