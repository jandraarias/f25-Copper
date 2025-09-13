<?php

namespace App\Filament\Resources\Preferences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PreferenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('category')
                    ->required(),
            ]);
    }
}
