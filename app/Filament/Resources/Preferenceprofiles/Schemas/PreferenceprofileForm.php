<?php

namespace App\Filament\Resources\PreferenceProfiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PreferenceProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('traveler_id')
                    ->relationship('traveler', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('budget'),
                Textarea::make('interests')
                    ->columnSpanFull(),
                TextInput::make('preferred_climate'),
            ]);
    }
}
