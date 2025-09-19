<?php

namespace App\Filament\Resources\PreferenceProfiles;

use App\Filament\Resources\PreferenceProfiles\Pages;
use App\Models\PreferenceProfile;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PreferenceProfileResource extends Resource
{
    protected static ?string $model = PreferenceProfile::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-vertical';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('traveler_id')
                ->relationship('traveler', 'name')
                ->required()
                ->label('Traveler'),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('budget')
                ->numeric()
                ->nullable(),

            Textarea::make('interests')
                ->label('Interests')
                ->rows(4),

            TextInput::make('preferred_climate')
                ->maxLength(255)
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('traveler.name')->label('Traveler'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('budget'),
                TextColumn::make('preferred_climate'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Later: add PreferencesRelationManager if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreferenceProfiles::route('/'),
            // creation/edit via Traveler relation manager or directly
            'edit'  => Pages\EditPreferenceProfile::route('/{record}/edit'),
        ];
    }
}
