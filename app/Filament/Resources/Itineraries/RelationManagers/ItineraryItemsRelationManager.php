<?php

namespace App\Filament\Resources\Itineraries\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ItineraryItemsRelationManager extends RelationManager
{
    /**
     * IMPORTANT: Must match the relation name on your Itinerary model.
     * e.g., if the model method is items(): HasMany { ... }, use 'items'.
     */
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
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

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('location')->toggleable(),
                TextColumn::make('start_time')->dateTime()->sortable(),
                TextColumn::make('end_time')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
            ])
            ->headerActions([
                CreateAction::make(), // v4
            ])
            ->recordActions([
                EditAction::make(),   // v4
                DeleteAction::make(), // v4
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // v4
                ]),
            ]);
    }
}
