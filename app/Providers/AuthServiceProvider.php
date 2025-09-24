<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Itinerary::class         => \App\Policies\ItineraryPolicy::class,
        \App\Models\ItineraryItem::class     => \App\Policies\ItineraryItemPolicy::class,
        // Explicit mappings to avoid relying on auto-discovery:
        \App\Models\Preference::class        => \App\Policies\PreferencePolicy::class,
        \App\Models\PreferenceProfile::class => \App\Policies\PreferenceProfilePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
