<?php

namespace App\Filament\Resources\Travelers;

use App\Filament\Resources\Travelers\Pages\CreateTraveler;
use App\Filament\Resources\Travelers\Pages\EditTraveler;
use App\Filament\Resources\Travelers\Pages\ListTravelers;
use App\Filament\Resources\Travelers\Schemas\TravelerForm;
use App\Filament\Resources\Travelers\Tables\TravelersTable;
use App\Models\Traveler;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TravelerResource extends Resource
{
    protected static ?string $model = Traveler::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TravelerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TravelersTable::configure($table);
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
            'index' => ListTravelers::route('/'),
            'create' => CreateTraveler::route('/create'),
            'edit' => EditTraveler::route('/{record}/edit'),
        ];
    }
}
