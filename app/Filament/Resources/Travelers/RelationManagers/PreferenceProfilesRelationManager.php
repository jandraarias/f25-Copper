<?php

namespace App\Filament\Resources\Travelers\RelationManagers;

use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PreferenceProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'preferenceProfiles';

    protected static ?string $relatedResource = PreferenceProfileResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
