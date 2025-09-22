<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;

class TravelerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('traveler')
            ->path('traveler') // => base URL /traveler
            ->login()
            ->resources([
                \App\Filament\Resources\Itineraries\ItineraryResource::class,
                \App\Filament\Resources\ItineraryItems\ItineraryItemResource::class,
                \App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource::class,
                \App\Filament\Resources\Preferences\PreferenceResource::class,
            ])
            ->brandName('Itinerease')
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => '#2563EB', // tailwind blue-600
            ])
            ->globalSearch(false) // travelers don’t need global search
            ->navigationGroups([
                'Trips',
                'Preferences',
            ])
            ->middleware([
                'web',
                'auth',
                'role:traveler',
            ]);
    }
}
