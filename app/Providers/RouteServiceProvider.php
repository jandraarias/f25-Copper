<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Fallback path if no role matches.
     * Since you have no generic /dashboard, default to traveler dashboard.
     */
    public const HOME = '/traveler/dashboard';

    /**
     * Get the path users should be redirected to after login/verification.
     */
    public static function redirectTo(): string
    {
        $user = Auth::user();

        if (! $user) {
            return '/';
        }

        return match ($user->role) {
            'admin'    => route('admin.dashboard'),    // Blade admin dashboard
            'expert'   => route('expert.dashboard'),
            'business' => route('business.dashboard'),
            'traveler' => route('traveler.dashboard'),
            default    => self::HOME, // fallback
        };
    }
}
