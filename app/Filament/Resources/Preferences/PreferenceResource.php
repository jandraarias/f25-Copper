<?php

namespace App\Filament\Resources\Preferences;

use App\Filament\Resources\Preferences\Pages\CreatePreference;
use App\Filament\Resources\Preferences\Pages\EditPreference;
use App\Filament\Resources\Preferences\Pages\ListPreferences;
use App\Filament\Resources\Preferences\Schemas\PreferenceForm;
use App\Filament\Resources\Preferences\Tables\PreferencesTable;
use App\Models\Preference;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PreferenceResource extends Resource
{
    protected static ?string $model = Preference::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PreferenceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreferencesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPreferences::route('/'),
            'create' => CreatePreference::route('/create'),
            'edit' => EditPreference::route('/{record}/edit'),
        ];
    }
}
