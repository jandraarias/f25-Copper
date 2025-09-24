<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\BusinessController;

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\PublicItineraryController;
use App\Http\Controllers\ItineraryPdfController;

use App\Http\Controllers\Traveler\DashboardController as TravelerDashboardController;
use App\Http\Controllers\Traveler\ItineraryController as TravelerItineraryController;
use App\Http\Controllers\Traveler\ItineraryItemController as TravelerItineraryItemController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
// Throttled short link for public itineraries
Route::middleware(['throttle:20,1'])->get('/i/{uuid}', [PublicItineraryController::class, 'show'])
    ->name('public.itinerary.short');

// (Optional) keep original name but apply throttling too
Route::middleware(['throttle:20,1'])->get('/public/itineraries/{uuid}', [PublicItineraryController::class, 'show'])
    ->name('public.itinerary.show');

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'expert':
                return redirect()->route('expert.dashboard');
            case 'business':
                return redirect()->route('business.dashboard');
            case 'traveler':
            default:
                return redirect()->route('traveler.dashboard');
        }
    }

    // Guest users still see welcome page
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Profile (all authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard (controller-based, not a closure)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/export', [UserManagementController::class, 'export'])->name('users.export');
        Route::get('/users/search', [UserManagementController::class, 'search'])->name('users.search');
    });

/*
|--------------------------------------------------------------------------
| Expert
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:expert'])->group(function () {
    Route::get('/expert/dashboard', [ExpertController::class, 'index'])->name('expert.dashboard');
});

/*
|--------------------------------------------------------------------------
| Business
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:business'])->group(function () {
    Route::get('/business/dashboard', [BusinessController::class, 'index'])->name('business.dashboard');
});

/*
|--------------------------------------------------------------------------
| Traveler (Breeze pages)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:traveler'])
    ->prefix('traveler')
    ->name('traveler.')
    ->group(function () {
        // Traveler Dashboard is now its own page (no redirect)
        Route::get('/dashboard', [TravelerDashboardController::class, 'index'])->name('dashboard');

        // Itineraries CRUD
        Route::resource('itineraries', TravelerItineraryController::class);

        // Items nested under itineraries; shallow routes for update/destroy
        Route::resource('itineraries.items', TravelerItineraryItemController::class)
            ->shallow()
            ->only(['store', 'update', 'destroy']);
    });

/*
|--------------------------------------------------------------------------
| Itinerary PDF (any authenticated user)
|--------------------------------------------------------------------------
*/
Route::get('/itineraries/{itinerary}/pdf', ItineraryPdfController::class)
    ->middleware(['web', 'auth'])
    ->name('itineraries.pdf');

/*
|--------------------------------------------------------------------------
| Auth scaffolding
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
