<?php

namespace App\Filament\Resources\Preferenceprofiles;

use App\Filament\Resources\Preferenceprofiles\Pages\CreatePreferenceprofile;
use App\Filament\Resources\Preferenceprofiles\Pages\EditPreferenceprofile;
use App\Filament\Resources\Preferenceprofiles\Pages\ListPreferenceprofiles;
use App\Filament\Resources\Preferenceprofiles\Schemas\PreferenceprofileForm;
use App\Filament\Resources\Preferenceprofiles\Tables\PreferenceprofilesTable;
use App\Models\Preferenceprofile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PreferenceprofileResource extends Resource
{
    protected static ?string $model = Preferenceprofile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PreferenceprofileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreferenceprofilesTable::configure($table);
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
            'index' => ListPreferenceprofiles::route('/'),
            'create' => CreatePreferenceprofile::route('/create'),
            'edit' => EditPreferenceprofile::route('/{record}/edit'),
        ];
    }
}
