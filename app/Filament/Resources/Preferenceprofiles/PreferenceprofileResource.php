<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreferenceProfiles\Pages;
use App\Models\PreferenceProfile;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PreferenceProfileResource extends Resource
{
    protected static ?string $model = PreferenceProfile::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('budget')->numeric()->suffix('$'),
            Textarea::make('interests')->label('Interests (JSON or comma separated)')->columnSpanFull(),
            Select::make('preferred_climate')
                ->options([
                    'warm' => 'Warm',
                    'cold' => 'Cold',
                    'temperate' => 'Temperate',
                ])
                ->label('Preferred Climate')
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('budget')->sortable(),
                TextColumn::make('preferred_climate')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index'  => Pages\ListPreferenceProfiles::route('/'),
            'create' => Pages\CreatePreferenceProfile::route('/create'),
            'edit'   => Pages\EditPreferenceProfile::route('/{record}/edit'),
        ];
    }
}
