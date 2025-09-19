<?php

namespace App\Filament\Resources\Travelers\RelationManagers;

use App\Filament\Resources\Itineraries\ItineraryResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ItinerariesRelationManager extends RelationManager
{
    // This MUST match the Traveler model method name: itineraries()
    protected static string $relationship = 'itineraries';

    // Link to your existing ItineraryResource so links open the resource pages
    protected static ?string $relatedResource = ItineraryResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),

            DatePicker::make('start_date')
                ->required(),

            DatePicker::make('end_date')
                ->required(),

            TextInput::make('country')
                ->maxLength(255),

            TextInput::make('location')
                ->maxLength(255),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('start_date')->date(),
                TextColumn::make('end_date')->date(),
                TextColumn::make('country')->sortable(),
                TextColumn::make('location')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
