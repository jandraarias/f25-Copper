<?php

namespace App\Filament\Resources\Itineraries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItineraryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                TextInput::make('country')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                Select::make('traveler_id')
                    ->relationship('traveler', 'name')
                    ->required(),
            ]);
    }
}
