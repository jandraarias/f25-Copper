<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ExpertController;
use App\Http\Controllers\BusinessController;

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\PublicItineraryController;
use App\Http\Controllers\ItineraryPdfController;

use App\Http\Controllers\Traveler\DashboardController as TravelerDashboardController;
use App\Http\Controllers\Traveler\ItineraryController;
use App\Http\Controllers\Traveler\ItineraryItemController;
use App\Http\Controllers\Traveler\PreferenceProfileController;
use App\Http\Controllers\Traveler\PreferenceController;
use App\Http\Controllers\Traveler\ItineraryInvitationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Publicly viewable itineraries (short & long URLs)
Route::middleware(['throttle:20,1'])->get('/i/{uuid}', [PublicItineraryController::class, 'show'])
    ->name('public.itinerary.short');

Route::middleware(['throttle:20,1'])->get('/public/itineraries/{uuid}', [PublicItineraryController::class, 'show'])
    ->name('public.itinerary.show');

// Landing page redirect by role
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'expert'    => redirect()->route('expert.dashboard'),
            'business'  => redirect()->route('business.dashboard'),
            'traveler'  => redirect()->route('traveler.dashboard'),
            default     => view('welcome'),
        };
    }

    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Profile (Authenticated)
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
| Traveler
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:traveler'])
    ->prefix('traveler')
    ->name('traveler.')
    ->group(function () {
        // Traveler Dashboard
        Route::get('/dashboard', [TravelerDashboardController::class, 'index'])->name('dashboard');

        // Itineraries CRUD (owners + collaborators via policy)
        Route::resource('itineraries', ItineraryController::class);

        // Collaboration: Invite route (authenticated travelers)
        Route::post('itineraries/{itinerary}/invite', [ItineraryController::class, 'invite'])
            ->name('itineraries.invite');

        // Itinerary Items (nested, shallow)
        Route::resource('itineraries.items', ItineraryItemController::class)
            ->shallow()
            ->only(['store', 'update', 'destroy']);

        // Preference Profiles CRUD
        Route::resource('preference-profiles', PreferenceProfileController::class);

        // Preferences nested under profiles
        Route::resource('preference-profiles.preferences', PreferenceController::class)
            ->shallow()
            ->only(['create','store','edit','update','destroy']);

        // Explicit create route for preferences
        Route::get('preference-profiles/{preferenceProfile}/preferences/create', [PreferenceController::class, 'create'])
            ->name('preferences.create');
    });

/*
|--------------------------------------------------------------------------
| Itinerary Invitations (Public)
|--------------------------------------------------------------------------
|
| These routes are used by recipients (who may or may not be logged in)
| to accept or decline an invitation via token links in their email.
|
*/
Route::prefix('itinerary-invitations')->name('itinerary-invitations.')->group(function () {
    Route::get('{token}', [ItineraryInvitationController::class, 'show'])->name('show');
    Route::post('{token}/accept', [ItineraryInvitationController::class, 'accept'])->name('accept');
    Route::post('{token}/decline', [ItineraryInvitationController::class, 'decline'])->name('decline');
});

/*
|--------------------------------------------------------------------------
| Itinerary PDF (Authenticated)
|--------------------------------------------------------------------------
*/
Route::get('/itineraries/{itinerary}/pdf', ItineraryPdfController::class)
    ->middleware(['web', 'auth'])
    ->name('itineraries.pdf');

/*
|--------------------------------------------------------------------------
| Auth Scaffolding
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
