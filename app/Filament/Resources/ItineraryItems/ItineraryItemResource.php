<?php

namespace App\Filament\Resources\ItineraryItems;

use App\Filament\Resources\ItineraryItems\Pages;
use App\Models\ItineraryItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ItineraryItemResource extends Resource
{
    protected static ?string $model = ItineraryItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            // Limit selectable itineraries to the current traveler's records
            Select::make('itinerary_id')
                ->label('Itinerary')
                ->relationship(
                    'itinerary',
                    'name',
                    fn (Builder $query) => $query->when(
                        Auth::user()?->traveler?->id,
                        fn ($q, $travelerId) => $q->where('traveler_id', $travelerId)
                    )
                )
                ->searchable()
                ->preload()
                ->required(),

            // Add missing required 'type' field to match NOT NULL column
            Select::make('type')
                ->label('Type')
                ->options([
                    'flight'   => 'Flight',
                    'hotel'    => 'Hotel',
                    'activity' => 'Activity',
                    'transfer' => 'Transfer',
                    'note'     => 'Note',
                ])
                ->required()
                ->native(false),

            TextInput::make('title')
                ->required()
                ->maxLength(255),

            TextInput::make('location')
                ->maxLength(255),

            DateTimePicker::make('start_time')
                ->label('Start Time')
                ->seconds(false)
                ->required(),

            DateTimePicker::make('end_time')
                ->label('End Time')
                ->seconds(false)
                ->afterOrEqual('start_time'),

            Textarea::make('details')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('itinerary.name')->label('Itinerary')->sortable()->toggleable(),
                TextColumn::make('location')->toggleable(),
                TextColumn::make('start_time')->dateTime()->sortable(),
                TextColumn::make('end_time')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListItineraryItems::route('/'),
            'create' => Pages\CreateItineraryItem::route('/create'),
            'edit'   => Pages\EditItineraryItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $travelerId = Auth::user()?->traveler?->id;

        return parent::getEloquentQuery()
            ->when(
                $travelerId,
                fn (Builder $q) => $q->whereHas('itinerary', fn ($iq) => $iq->where('traveler_id', $travelerId))
            );
    }
}
