<?php

namespace App\Filament\Resources\Itineraries\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
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
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        // v4: Schema + ->components([...]) pattern. :contentReference[oaicite:2]{index=2}
        return $schema->components([
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
                ->label('Title')
                ->required()
                ->maxLength(255),

            DateTimePicker::make('start_time')
                ->label('Start Time'),

            DateTimePicker::make('end_time')
                ->label('End Time')
                ->afterOrEqual('start_time'),

            TextInput::make('location')
                ->label('Location')
                ->maxLength(255),

            Textarea::make('details')
                ->label('Details')
                ->rows(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->badge(),
                TextColumn::make('title')->limit(40)->wrap(),
                TextColumn::make('start_time')->dateTime(),
                TextColumn::make('end_time')->dateTime(),
                TextColumn::make('location')->limit(30),
            ])
            ->recordUrl(null)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // v4
                ]),
            ]);
    }
}
