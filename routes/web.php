<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

use App\Http\Controllers\PublicItineraryController;
use App\Http\Controllers\ItineraryPdfController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlaceReviewController;

// Traveler Controllers
use App\Http\Controllers\Traveler\DashboardController as TravelerDashboardController;
use App\Http\Controllers\Traveler\ItineraryController as TravelerItineraryController;
use App\Http\Controllers\Traveler\ItineraryItemController;
use App\Http\Controllers\Traveler\PreferenceProfileController;
use App\Http\Controllers\Traveler\PreferenceController;
use App\Http\Controllers\Traveler\ItineraryInvitationController;
use App\Http\Controllers\Traveler\RewardsController;
use App\Http\Controllers\Traveler\ExpertsController as TravelerExpertsController;

// Expert Controllers (alias to avoid collisions)
use App\Http\Controllers\Expert\DashboardController as ExpertDashboardController;
use App\Http\Controllers\Expert\ItineraryController as ExpertItineraryController;
use App\Http\Controllers\Expert\TravelerController as ExpertTravelerController;
use App\Http\Controllers\Expert\ProfileController as ExpertProfileController;

// Business Controller
use App\Http\Controllers\BusinessController;

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

/*
|--------------------------------------------------------------------------
| Welcome / Home Redirect
|--------------------------------------------------------------------------
*/

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

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserManagementController::class)->except(['show']);
        Route::get('/users/export', [UserManagementController::class, 'export'])->name('users.export');
        Route::get('/users/search', [UserManagementController::class, 'search'])->name('users.search');
    });

/*
|--------------------------------------------------------------------------
| Expert
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:expert'])
    ->prefix('expert')
    ->name('expert.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [ExpertDashboardController::class, 'index'])
            ->name('dashboard');

        // Itineraries
        Route::get('/itineraries', [ExpertItineraryController::class, 'index'])
            ->name('itineraries.index');

        Route::get('/itineraries/{itinerary}', [ExpertItineraryController::class, 'show'])
            ->name('itineraries.show');

        // Travelers
        Route::get('/travelers', [ExpertTravelerController::class, 'index'])
            ->name('travelers.index');

        // Profile (SHOW)
        Route::get('/profile', [ExpertProfileController::class, 'show'])
            ->name('profile.show');

        // Profile (EDIT FORM)
        Route::get('/profile/edit', [ExpertProfileController::class, 'edit'])
            ->name('profile.edit');

        // Profile update
        Route::patch('/profile', [ExpertProfileController::class, 'update'])
            ->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Business
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:business'])
    ->prefix('business')
    ->name('business.')
    ->group(function () {

        Route::get('/dashboard', [BusinessController::class, 'index'])
            ->name('dashboard');
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

        Route::get('/dashboard', [TravelerDashboardController::class, 'index'])->name('dashboard');

        Route::resource('itineraries', TravelerItineraryController::class);

        Route::post('itineraries/{itinerary}/generate', [TravelerItineraryController::class, 'generate'])
            ->name('itineraries.generate');

        Route::post('itineraries/{itinerary}/invite', [TravelerItineraryController::class, 'invite'])
            ->name('itineraries.invite');

        Route::post('itineraries/{itinerary}/enable-collaboration', [TravelerItineraryController::class, 'enableCollaboration'])
            ->name('itineraries.enable-collaboration');

        Route::post('itineraries/{itinerary}/disable-collaboration', [TravelerItineraryController::class, 'disableCollaboration'])
            ->name('itineraries.disable-collaboration');

        Route::resource('itineraries.items', ItineraryItemController::class)
            ->shallow()
            ->only(['store', 'update', 'destroy']);

        Route::resource('preference-profiles', PreferenceProfileController::class);

        Route::resource('preference-profiles.preferences', PreferenceController::class)
            ->shallow()
            ->only(['create', 'store', 'edit', 'update', 'destroy']);

        Route::get('preference-profiles/{preferenceProfile}/preferences/create', [PreferenceController::class, 'create'])
            ->name('preferences.create');

        Route::get('/rewards', [RewardsController::class, 'index'])
            ->name('rewards');

        Route::post('/places/{place}/add-to-itinerary', [ItineraryItemController::class, 'addPlace'])
            ->name('places.add-to-itinerary');

        Route::get('/experts', [TravelerExpertsController::class, 'index'])->name('experts');
    });

/*
|--------------------------------------------------------------------------
| Invitations (Public)
|--------------------------------------------------------------------------
*/

Route::prefix('itinerary-invitations')->name('itinerary-invitations.')->group(function () {
    Route::get('{token}', [ItineraryInvitationController::class, 'show'])->name('show');
    Route::post('{token}/accept', [ItineraryInvitationController::class, 'accept'])->name('accept');
    Route::post('{token}/decline', [ItineraryInvitationController::class, 'decline'])->name('decline');
});

/*
|--------------------------------------------------------------------------
| PDF
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
