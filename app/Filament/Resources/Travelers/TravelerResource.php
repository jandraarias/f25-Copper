<?php

namespace App\Filament\Resources\Travelers;

use App\Filament\Resources\Travelers\Pages;
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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),

            // Make DOB required to match DB constraints
            DatePicker::make('date_of_birth')
                ->label('Date of Birth')
                ->required(),

            // Make phone required to match DB constraints
            TextInput::make('phone_number')
                ->label('Phone Number')
                ->required()
                ->maxLength(50),

            Textarea::make('bio')
                ->label('Bio')
                ->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('date_of_birth')->date()->sortable(),
                TextColumn::make('phone_number')->sortable(),
                TextColumn::make('created_at')->dateTime()->toggleable()->sortable(),
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
