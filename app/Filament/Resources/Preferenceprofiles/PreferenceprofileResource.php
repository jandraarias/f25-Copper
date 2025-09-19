<?php

namespace App\Filament\Resources\PreferenceProfiles;

use App\Filament\Resources\PreferenceProfiles\Pages;
use App\Models\PreferenceProfile;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            Textarea::make('preferences')
                ->label('Preferences (JSON or text)')
                ->rows(4),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreferenceProfiles::route('/'),
            // Creation is handled in Traveler relation manager
            'edit'  => Pages\EditPreferenceProfile::route('/{record}/edit'),
        ];
    }
}
