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
        \App\Models\Itinerary::class     => \App\Policies\ItineraryPolicy::class,
        \App\Models\ItineraryItem::class => \App\Policies\ItineraryItemPolicy::class,
        // Add more mappings here as needed.
    ];

    public function boot(): void
    {
        // This registers the policies above. No extra Gate wiring needed
        // because each policy includes its own admin override via before().
        $this->registerPolicies();
    }
}
