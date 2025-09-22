<?php

namespace App\Filament\Resources\Preferences;

use App\Models\Preference;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\Preferences\Pages as PreferencesPages;

class PreferenceResource extends Resource
{
    protected static ?string $model = Preference::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    // Display the key as the record title
    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('preference_profile_id')
                ->label('Profile')
                ->relationship(
                    'preferenceProfile',
                    'name',
                    fn (Builder $query) => $query->when(
                        Auth::user()?->traveler?->id,
                        fn ($q, $travelerId) => $q->where('traveler_id', $travelerId)
                    )
                )
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('key')
                ->required()
                ->maxLength(255),

            TextInput::make('value')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('value')->searchable(),
                TextColumn::make('preferenceProfile.name')->label('Profile')->sortable()->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
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
        return [
            // Add RelationManagers here if you want inline management from profiles
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => PreferencesPages\ListPreferences::route('/'),
            'create' => PreferencesPages\CreatePreference::route('/create'),
            'edit'   => PreferencesPages\EditPreference::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $travelerId = Auth::user()?->traveler?->id;

        return parent::getEloquentQuery()
            ->when($travelerId, fn (Builder $q) =>
                $q->whereHas('preferenceProfile', fn (Builder $p) =>
                    $p->where('traveler_id', $travelerId)
                )
            );
    }
}
