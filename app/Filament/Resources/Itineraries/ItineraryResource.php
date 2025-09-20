<?php

namespace App\Filament\Resources;

use App\Models\Itinerary;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

// ✅ Point to plural namespace
use App\Filament\Resources\Itineraries\Pages as ItinerariesPages;

class ItineraryResource extends Resource
{
    protected static ?string $model = Itinerary::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->rows(3),

            TextInput::make('destination')
                ->label('Destination')
                ->maxLength(255),

            TextInput::make('start_date')
                ->label('Start Date')
                ->type('date'),

            TextInput::make('end_date')
                ->label('End Date')
                ->type('date'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('destination')->sortable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
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
        return [
            // You could add ItineraryItems relation manager here if desired
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ItinerariesPages\ListItineraries::route('/'),
            'create' => ItinerariesPages\CreateItinerary::route('/create'),
            'edit'   => ItinerariesPages\EditItinerary::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $travelerId = Auth::user()?->traveler?->id;

        return parent::getEloquentQuery()
            ->when($travelerId, fn (Builder $q) =>
                $q->where('traveler_id', $travelerId)
            );
    }
}
