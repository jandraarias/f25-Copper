<?php

namespace App\Filament\Resources\ItineraryItems;

use App\Filament\Resources\ItineraryItems\Pages\CreateItineraryItem;
use App\Filament\Resources\ItineraryItems\Pages\EditItineraryItem;
use App\Filament\Resources\ItineraryItems\Pages\ListItineraryItems;
use App\Filament\Resources\ItineraryItems\Schemas\ItineraryItemForm;
use App\Filament\Resources\ItineraryItems\Tables\ItineraryItemsTable;
use App\Models\ItineraryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ItineraryItemResource extends Resource
{
    protected static ?string $model = ItineraryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ItineraryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItineraryItemsTable::configure($table);
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
            'index' => ListItineraryItems::route('/'),
            'create' => CreateItineraryItem::route('/create'),
            'edit' => EditItineraryItem::route('/{record}/edit'),
        ];
    }
}
