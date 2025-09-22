<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\PreferenceProfile;
use App\Models\Preference;

use App\Policies\ItineraryPolicy;
use App\Policies\ItineraryItemPolicy;
use App\Policies\PreferenceProfilePolicy;
use App\Policies\PreferencePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Itinerary::class         => ItineraryPolicy::class,
        ItineraryItem::class     => ItineraryItemPolicy::class,
        PreferenceProfile::class => PreferenceProfilePolicy::class,
        Preference::class        => PreferencePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
