<?php

namespace App\Filament\Widgets;

use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Preference;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TravelerStats extends BaseWidget
{
    protected function getStats(): array
    {
        $travelerId = Auth::user()?->traveler?->id;

        $itineraries = Itinerary::query()
            ->when($travelerId, fn ($q) => $q->where('traveler_id', $travelerId))
            ->count();

        $items = ItineraryItem::query()
            ->whereHas('itinerary', fn ($q) =>
                $q->when($travelerId, fn ($qq) => $qq->where('traveler_id', $travelerId))
            )
            ->count();

        $preferences = Preference::query()
            ->whereHas('preferenceProfile', fn ($q) =>
                $q->when($travelerId, fn ($qq) => $qq->where('traveler_id', $travelerId))
            )
            ->count();

        return [
            Stat::make('Itineraries', (string) $itineraries),
            Stat::make('Items', (string) $items),
            Stat::make('Preferences', (string) $preferences),
        ];
    }
}
