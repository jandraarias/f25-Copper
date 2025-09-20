<?php

namespace App\Filament\Resources;

use App\Models\Preference;
use App\Models\PreferenceProfile;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

// ✅ point to the existing pages namespace (plural)
use App\Filament\Resources\Preferences\Pages as PreferencesPages;

class PreferenceResource extends Resource
{
    protected static ?string $model = Preference::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('preference_profile_id')
                ->label('Profile')
                ->required()
                ->searchable()
                ->options(fn () => PreferenceProfile::query()
                    ->where('traveler_id', Auth::user()?->traveler?->id)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all()
                ),

            TextInput::make('key')
                ->required()
                ->maxLength(255),

            Textarea::make('value')
                ->label('Value')
                ->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('value')->limit(50),
                TextColumn::make('preferenceProfile.name')->label('Profile')->sortable(),
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
        // ✅ now using PreferencesPages\* (plural) which already exist in your project
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
