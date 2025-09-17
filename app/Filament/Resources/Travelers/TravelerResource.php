<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TravelerResource\Pages;
use App\Models\Traveler;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class TravelerResource extends Resource
{
    protected static ?string $model = Traveler::class;

    // Note: property type matches Filament v4 base class.
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255),
            DatePicker::make('date_of_birth')->label('Date of Birth'),
            TextInput::make('phone_number')->label('Phone')->tel()->maxLength(20),
            Textarea::make('bio')->label('Biography')->columnSpanFull(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('date_of_birth')->date(),
                TextColumn::make('phone_number')->label('Phone'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // prebuilt bulk delete action
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTravelers::route('/'),
            'create' => Pages\CreateTraveler::route('/create'),
            'edit'   => Pages\EditTraveler::route('/{record}/edit'),
        ];
    }
}
