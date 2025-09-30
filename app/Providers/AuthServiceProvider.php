<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Itinerary;
use App\Policies\ItineraryPolicy;
use App\Models\ItineraryItem;
use App\Policies\ItineraryItemPolicy;
use App\Models\PreferenceProfile;
use App\Policies\PreferenceProfilePolicy;
use App\Models\Preference;
use App\Policies\PreferencePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Itinerary::class => ItineraryPolicy::class,
        ItineraryItem::class => ItineraryItemPolicy::class,
        PreferenceProfile::class => PreferenceProfilePolicy::class,
        Preference::class => PreferencePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // You can define custom gates here if needed
    }
}
