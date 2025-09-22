<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use App\Filament\Resources\ItineraryResource;
use App\Filament\Resources\PreferenceResource;

class TravelerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('traveler')
            ->path('traveler') // URL prefix
            ->login()          // travelers use same login
            ->resources([
                ItineraryResource::class,
                PreferenceResource::class,
            ])
            ->middleware([
                'web',
                'auth',
                'role:traveler',
            ]);
    }
}
