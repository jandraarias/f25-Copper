<?php

namespace App\Filament\Resources\Preferenceprofiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PreferenceprofileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('traveler_id')
                    ->relationship('traveler', 'name')
                    ->required(),
            ]);
    }
}
