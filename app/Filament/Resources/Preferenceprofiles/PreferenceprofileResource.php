<?php

namespace App\Filament\Resources\PreferenceProfiles;

use App\Filament\Resources\PreferenceProfiles\Pages\CreatePreferenceProfile;
use App\Filament\Resources\PreferenceProfiles\Pages\EditPreferenceProfile;
use App\Filament\Resources\PreferenceProfiles\Pages\ListPreferenceProfiles;
use App\Filament\Resources\PreferenceProfiles\Schemas\PreferenceProfileForm;
use App\Filament\Resources\PreferenceProfiles\Tables\PreferenceProfilesTable;
use App\Models\PreferenceProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PreferenceProfileResource extends Resource
{
    protected static ?string $model = PreferenceProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PreferenceProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreferenceProfilesTable::configure($table);
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
            'index' => ListPreferenceProfiles::route('/'),
            'create' => CreatePreferenceProfile::route('/create'),
            'edit' => EditPreferenceProfile::route('/{record}/edit'),
        ];
    }
}
