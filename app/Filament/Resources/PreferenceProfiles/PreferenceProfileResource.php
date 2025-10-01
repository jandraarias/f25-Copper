<?php

namespace App\Filament\Resources\PreferenceProfiles;

use App\Models\PreferenceProfile;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Hidden;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\PreferenceProfiles\Pages as PreferenceProfilesPages;

class PreferenceProfileResource extends Resource
{
    protected static ?string $model = PreferenceProfile::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            // Auto-attach to the signed-in user's traveler record (if present)
            Hidden::make('traveler_id')
                ->default(fn () => Auth::user()?->traveler?->id)
                ->dehydrated(),

            TextInput::make('name')
                ->label('Profile Name')
                ->required()
                ->maxLength(255),

            // Keep these flexible to avoid enum mismatches; you can switch to Select later if desired.
            TextInput::make('budget')
                ->placeholder('e.g., budget / moderate / luxury')
                ->maxLength(50),

            TextInput::make('preferred_climate')
                ->placeholder('e.g., temperate, tropical, arid')
                ->maxLength(50),

            // Store as JSON string safely even if the model doesn’t define a cast.
            TagsInput::make('interests')
                ->placeholder('Add interests…')
                ->suggestions(['food', 'museums', 'hiking', 'beach', 'nightlife', 'history'])
                ->separator(',')
                ->nullable()
                ->dehydrateStateUsing(function ($state) {
                    if (is_string($state)) {
                        return $state; // already a JSON/string (or empty)
                    }
                    return $state ? json_encode(array_values((array) $state)) : null;
                }),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('budget')->toggleable()->sortable(),
                TextColumn::make('preferred_climate')->label('Climate')->toggleable()->sortable(),
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
            // You can add a RelationManager here for Preferences if you want inline management.
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => PreferenceProfilesPages\ListPreferenceProfiles::route('/'),
            'create' => PreferenceProfilesPages\CreatePreferenceProfile::route('/create'),
            'edit'   => PreferenceProfilesPages\EditPreferenceProfile::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $travelerId = Auth::user()?->traveler?->id;

        return parent::getEloquentQuery()
            ->when($travelerId, fn (Builder $q) =>
                $q->where('traveler_id', $travelerId)
            );
    }
}
