<?php

namespace App\Filament\Resources\ItineraryItems;

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

use App\Filament\Resources\ItineraryItems\Pages as ItineraryItemsPages;

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

    public static function getRelations(): array
    {
        return [
            // No RelationManagers for items by default
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ItineraryItemsPages\ListItineraryItems::route('/'),
            'create' => ItineraryItemsPages\CreateItineraryItem::route('/create'),
            'edit'   => ItineraryItemsPages\EditItineraryItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $travelerId = Auth::user()?->traveler?->id;

        // Only show items whose parent itinerary belongs to the signed-in traveler
        return parent::getEloquentQuery()
            ->when($travelerId, fn (Builder $q) =>
                $q->whereHas('itinerary', fn (Builder $i) =>
                    $i->where('traveler_id', $travelerId)
                )
            );
    }
}
