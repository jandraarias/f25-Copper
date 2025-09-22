<?php

namespace App\Filament\Resources\Itineraries;

use App\Models\Itinerary;
use App\Filament\Resources\Itineraries\RelationManagers\ItineraryItemsRelationManager;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\Itineraries\Pages as ItinerariesPages;

class ItineraryResource extends Resource
{
    protected static ?string $model = Itinerary::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            // Optional: auto-attach to the signed-in traveler
            Hidden::make('traveler_id')
                ->default(fn () => Auth::user()?->traveler?->id)
                ->dehydrated(),

            TextInput::make('name')
                ->label('Itinerary Name')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->rows(3),

            DatePicker::make('start_date')->required(),
            DatePicker::make('end_date')->afterOrEqual('start_date'),

            TextInput::make('country')->maxLength(100),
            TextInput::make('location')->label('City / Location')->maxLength(100),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('location')->toggleable(),
                TextColumn::make('country')->toggleable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                // (keep Duplicate from step 1)
                Action::make('share')
                    ->label('Share link')
                    ->icon('heroicon-m-link')
                    ->action(function (Itinerary $record) {
                        if (!$record->public_uuid) {
                            $record->public_uuid = (string) Str::uuid();
                            $record->save();
                        }
                        $url = route('public.itinerary.show', ['uuid' => $record->public_uuid]);
                        Notification::make()->title('Share link ready')->body($url)->success()->send();
                    }),
                Action::make('unshare')
                    ->label('Disable link')
                    ->icon('heroicon-m-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Itinerary $record) => filled($record->public_uuid))
                    ->action(function (Itinerary $record) {
                        $record->public_uuid = null;
                        $record->save();
                        Notification::make()->title('Share link disabled')->success()->send();
                    }),
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
            ItineraryItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ItinerariesPages\ListItineraries::route('/'),
            'create' => ItinerariesPages\CreateItinerary::route('/create'),
            'edit'   => ItinerariesPages\EditItinerary::route('/{record}/edit'),
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
