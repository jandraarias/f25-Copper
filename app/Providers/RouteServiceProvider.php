<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Default home route (used if no valid role is found)
     */
    public const HOME = '/';

    /**
     * Determine where to redirect users after login or verification.
     */
    public static function redirectTo(): string
    {
        $user = Auth::user();

        if (! $user) {
            return self::HOME;
        }

        // Make sure role routes exist before redirecting
        return match ($user->role) {
            'admin'    => Route::has('admin.dashboard') ? route('admin.dashboard') : self::HOME,
            'expert'   => Route::has('expert.dashboard') ? route('expert.dashboard') : self::HOME,
            'business' => Route::has('business.dashboard') ? route('business.dashboard') : self::HOME,
            'traveler' => Route::has('traveler.dashboard') ? route('traveler.dashboard') : self::HOME,
            default    => self::HOME,
        };
    }
}
