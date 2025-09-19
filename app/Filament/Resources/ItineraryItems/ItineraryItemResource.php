<?php

namespace App\Filament\Resources\ItineraryItems;

use App\Filament\Resources\ItineraryItems\Pages;
use App\Models\ItineraryItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ItineraryItemResource extends Resource
{
    protected static ?string $model = ItineraryItem::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('itinerary_id')
                ->relationship('itinerary', 'name')
                ->required()
                ->label('Itinerary'),

            TextInput::make('type')
                ->maxLength(100)
                ->label('Type (e.g. Flight, Hotel, Activity)'),

            TextInput::make('title')
                ->required()
                ->maxLength(255),

            DateTimePicker::make('start_time')
                ->label('Start Time')
                ->required(),

            DateTimePicker::make('end_time')
                ->label('End Time'),

            TextInput::make('location')
                ->maxLength(255),

            Textarea::make('details')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('start_time')->dateTime()->label('Start'),
                TextColumn::make('end_time')->dateTime()->label('End'),
                TextColumn::make('location')->sortable(),
                TextColumn::make('itinerary.name')->label('Itinerary'),
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
