<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItineraryItems\Pages;
use App\Models\ItineraryItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ItineraryItemResource extends Resource
{
    protected static ?string $model = ItineraryItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->columnSpanFull(),

            DatePicker::make('date')
                ->label('Date'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('date')->date(),
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
            'index'  => Pages\ListItineraryItems::route('/'),
            'create' => Pages\CreateItineraryItem::route('/create'),
            'edit'   => Pages\EditItineraryItem::route('/{record}/edit'),
        ];
    }
}
