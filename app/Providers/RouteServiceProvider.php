<?php

namespace App\Providers;

use App\Models\Traveler;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        parent::boot();
        Route::model('traveler', Traveler::class);
    }

    public static function redirectTo(): string
    {
        $user = Auth::user();

        if (! $user) {
            return self::HOME;
        }

        return match ($user->role) {
            'admin'    => Route::has('admin.dashboard') ? route('admin.dashboard') : self::HOME,
            'expert'   => Route::has('expert.dashboard') ? route('expert.dashboard') : self::HOME,
            'business' => Route::has('business.dashboard') ? route('business.dashboard') : self::HOME,
            'traveler' => Route::has('traveler.dashboard') ? route('traveler.dashboard') : self::HOME,
            default    => self::HOME,
        };
    }
}
