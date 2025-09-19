<?php

namespace App\Filament\Resources\Preferences;

use App\Filament\Resources\Preferences\Pages;
use App\Models\Preference;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PreferenceResource extends Resource
{
    protected static ?string $model = Preference::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        // Filament v4 uses Schema + ->components([...])
        return $schema->components([
            TextInput::make('key')
                ->required()
                ->maxLength(255),

            Textarea::make('value')
                ->label('Value')
                ->rows(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        // Filament v4 keeps Table; actions are in Filament\Actions\*
        return $table
            ->columns([
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('value')->limit(50),
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
            'index' => Pages\ListPreferences::route('/'),
            // creation handled from relation managers
            'edit'  => Pages\EditPreference::route('/{record}/edit'),
        ];
    }
}
