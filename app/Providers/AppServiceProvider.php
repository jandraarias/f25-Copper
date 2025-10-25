<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use App\Services\Linkify;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('linkify', function ($expression) {
            return "<?php echo \App\Services\Linkify::linkify($expression); ?>";
        });
    }
}
