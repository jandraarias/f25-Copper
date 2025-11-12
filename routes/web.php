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
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlaceReviewController;

use App\Http\Controllers\Traveler\DashboardController as TravelerDashboardController;
use App\Http\Controllers\Traveler\ItineraryController;
use App\Http\Controllers\Traveler\ItineraryItemController;
use App\Http\Controllers\Traveler\PreferenceProfileController;
use App\Http\Controllers\Traveler\PreferenceController;
use App\Http\Controllers\Traveler\ItineraryInvitationController;
use App\Http\Controllers\Traveler\RewardsController;
use App\Http\Controllers\Traveler\ExpertsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:20,1'])->get('/i/{uuid}', [PublicItineraryController::class, 'show'])
    ->name('public.itinerary.short');

Route::middleware(['throttle:20,1'])->get('/public/itineraries/{uuid}', [PublicItineraryController::class, 'show'])
    ->name('public.itinerary.show');

Route::get('/places/{place}', [PlaceController::class, 'show'])
    ->name('places.show');

Route::get('/places/{place}/reviews/page/{page}', [PlaceController::class, 'reviewsPage'])
    ->name('places.reviews.page');

Route::get('/places/{place}/reviews', [PlaceReviewController::class, 'index'])
    ->name('places.reviews.index');

Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
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

        // Itineraries CRUD
        Route::resource('itineraries', ItineraryController::class);

        // AI Itinerary Generation / Regeneration
        Route::post('itineraries/{itinerary}/generate', [ItineraryController::class, 'generate'])
            ->name('itineraries.generate');

        // Collaboration: Invite route
        Route::post('itineraries/{itinerary}/invite', [ItineraryController::class, 'invite'])
            ->name('itineraries.invite');

        // Itinerary Items
        Route::resource('itineraries.items', ItineraryItemController::class)
            ->shallow()
            ->only(['store', 'update', 'destroy']);

        // Preference Profiles
        Route::resource('preference-profiles', PreferenceProfileController::class);

        // Preferences under profiles
        Route::resource('preference-profiles.preferences', PreferenceController::class)
            ->shallow()
            ->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Explicit preferences create
        Route::get('preference-profiles/{preferenceProfile}/preferences/create', [PreferenceController::class, 'create'])
            ->name('preferences.create');

        // Correct Rewards Route
        Route::get('/rewards', [RewardsController::class, 'index'])
            ->name('rewards');

        // Add Place to Itinerary Route
        Route::post('/places/{place}/add-to-itinerary', [ItineraryItemController::class, 'addPlace'])
            ->name('places.add-to-itinerary')
            ->middleware('role:traveler');

        // Experts Listing Route
        Route::get('/experts', [ExpertsController::class, 'index'])->name('experts');

    });

/*
|--------------------------------------------------------------------------
| Itinerary Invitations (Public)
|--------------------------------------------------------------------------
*/

Route::prefix('itinerary-invitations')->name('itinerary-invitations.')->group(function () {
    Route::get('{token}', [ItineraryInvitationController::class, 'show'])->name('show');
    Route::post('{token}/accept', [ItineraryInvitationController::class, 'accept'])->name('accept');
    Route::post('{token}/decline', [ItineraryInvitationController::class, 'decline'])->name('decline');
});

/*
|--------------------------------------------------------------------------
| Itinerary PDF
|--------------------------------------------------------------------------
*/

Route::get('/itineraries/{itinerary}/pdf', ItineraryPdfController::class)
    ->middleware(['web', 'auth'])
    ->name('itineraries.pdf');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
