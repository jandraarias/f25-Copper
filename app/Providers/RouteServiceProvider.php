<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This is the default path after login/verification if no role check is used.
     */
    public const HOME = '/dashboard';

    /**
     * Get the path users should be redirected to after login/verification.
     */
    public static function redirectTo(): string
    {
        $user = Auth::user();

        if (!$user) {
            return '/';
        }

        switch ($user->role) {
            case 'admin':
                return '/admin'; // Filament panel home
            case 'expert':
                return route('expert.dashboard');
            case 'business':
                return route('business.dashboard');
            case 'traveler':
            default:
                return route('traveler.dashboard');
        }
    }
}
