<?php

namespace App\Filament\Resources\ItineraryItems\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ItineraryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('itinerary_id')
                    ->relationship('itinerary', 'name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                DateTimePicker::make('start_time'),
                DateTimePicker::make('end_time'),
                TextInput::make('location'),
                Textarea::make('details')
                    ->columnSpanFull(),
            ]);
    }
}
