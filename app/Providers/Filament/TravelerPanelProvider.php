<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;

use App\Filament\Resources\Itineraries\ItineraryResource;
use App\Filament\Resources\ItineraryItems\ItineraryItemResource;
use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use App\Filament\Resources\Preferences\PreferenceResource;

class TravelerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('traveler')
            ->path('traveler')
            ->login()
            ->resources([
                ItineraryResource::class,
                ItineraryItemResource::class,
                PreferenceProfileResource::class,
                PreferenceResource::class,
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
