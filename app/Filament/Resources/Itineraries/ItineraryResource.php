<?php

namespace App\Filament\Resources\Itineraries;

use App\Models\Itinerary;
use App\Filament\Resources\Itineraries\Pages as ItinerariesPages;
use App\Filament\Resources\Itineraries\RelationManagers\ItineraryItemsRelationManager;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ItineraryResource extends Resource
{
    protected static ?string $model = Itinerary::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            // Optional: auto-attach to the signed-in traveler
            Hidden::make('traveler_id')
                ->default(fn () => Auth::user()?->traveler?->id)
                ->dehydrated(),

            TextInput::make('name')
                ->label('Itinerary Name')
                ->required()
                ->maxLength(255),

            // Make description required to match DB constraints
            Textarea::make('description')
                ->rows(3)
                ->required(),

            DatePicker::make('start_date')->required(),

            // Enforce required + ordering on end_date
            DatePicker::make('end_date')->required()->afterOrEqual('start_date'),

            // Enforce required on country & location to match NOT NULL columns
            TextInput::make('country')->required()->maxLength(100),

            TextInput::make('location')->label('City / Location')->required()->maxLength(100),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->limit(40),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('country')->sortable(),
                TextColumn::make('location')->limit(30)->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
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
            ItineraryItemsRelationManager::class,
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
            ->when(
                $travelerId,
                fn (Builder $q) => $q->where('traveler_id', $travelerId)
            );
    }
}
