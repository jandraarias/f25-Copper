<?php

namespace App\Filament\Resources\Travelers\RelationManagers;

use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PreferenceProfilesRelationManager extends RelationManager
{
    // Must match Traveler model method name: preferenceProfiles()
    protected static string $relationship = 'preferenceProfiles';

    // Connects to your PreferenceProfileResource
    protected static ?string $relatedResource = PreferenceProfileResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('budget')
                ->numeric()
                ->label('Budget'),

            Textarea::make('interests')
                ->rows(3)
                ->label('Interests'),

            TextInput::make('preferred_climate')
                ->maxLength(255)
                ->label('Preferred Climate'),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('budget')->sortable(),
                TextColumn::make('preferred_climate')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
