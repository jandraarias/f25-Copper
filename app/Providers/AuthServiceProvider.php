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
        \App\Models\Itinerary::class         => \App\Policies\ItineraryPolicy::class,
        \App\Models\ItineraryItem::class     => \App\Policies\ItineraryItemPolicy::class,
        \App\Models\PreferenceProfile::class => \App\Policies\PreferenceProfilePolicy::class,
        \App\Models\Preference::class        => \App\Policies\PreferencePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
