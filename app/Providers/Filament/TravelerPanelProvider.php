<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;

// resources (you likely already have these)
use App\Filament\Resources\Itineraries\ItineraryResource;
use App\Filament\Resources\ItineraryItems\ItineraryItemResource;
use App\Filament\Resources\PreferenceProfiles\PreferenceProfileResource;
use App\Filament\Resources\Preferences\PreferenceResource;

// NEW: pages & widgets
use App\Filament\Pages\TravelerDashboard;
use App\Filament\Widgets\TravelerStats;
use App\Filament\Widgets\UpcomingItemsTable;

class TravelerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('traveler')
            ->path('traveler')
            ->login()
            ->pages([
                TravelerDashboard::class, // <-- makes /traveler the dashboard
            ])
            ->widgets([
                TravelerStats::class,
                UpcomingItemsTable::class,
            ])
            ->resources([
                ItineraryResource::class,
                ItineraryItemResource::class,
                PreferenceProfileResource::class,
                PreferenceResource::class,
            ])
            ->middleware([
                'web',
                'auth',
                'role:traveler',
            ]);
    }
}
