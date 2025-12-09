<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public-facing Controllers
use App\Http\Controllers\PublicItineraryController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlaceReviewController;
use App\Http\Controllers\ItineraryPdfController;

// Shared Controllers
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Traveler Controllers
use App\Http\Controllers\Traveler\DashboardController as TravelerDashboardController;
use App\Http\Controllers\Traveler\ItineraryController as TravelerItineraryController;
use App\Http\Controllers\Traveler\ItineraryItemController;
use App\Http\Controllers\Traveler\PreferenceProfileController;
use App\Http\Controllers\Traveler\PreferenceController;
use App\Http\Controllers\Traveler\ItineraryInvitationController;
use App\Http\Controllers\Traveler\RewardsController;
use App\Http\Controllers\Traveler\ExpertsController as TravelerExpertsController;
use App\Http\Controllers\Traveler\MessageController as TravelerMessageController;

// Expert Controllers
use App\Http\Controllers\Expert\DashboardController as ExpertDashboardController;
use App\Http\Controllers\Expert\ItineraryController as ExpertItineraryController;
use App\Http\Controllers\Expert\ItineraryEditController as ExpertItineraryEditController;
use App\Http\Controllers\Expert\TravelerController as ExpertTravelerController;
use App\Http\Controllers\Expert\ProfileController as ExpertProfileController;
use App\Http\Controllers\Expert\MessageController as ExpertMessageController;
use App\Http\Controllers\Expert\ItineraryInvitationController as ExpertItineraryInvitationController;

// Business Controllers
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Business\DashboardController as BusinessDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:20,1'])->group(function () {
    Route::get('/i/{uuid}', [PublicItineraryController::class, 'show'])
        ->name('public.itinerary.short');

    Route::get('/public/itineraries/{uuid}', [PublicItineraryController::class, 'show'])
        ->name('public.itinerary.show');
});

// Places
Route::get('/places/{place}', [PlaceController::class, 'show'])
    ->name('places.show');

Route::get('/places/{place}/reviews/page/{page}', [PlaceController::class, 'reviewsPage'])
    ->name('places.reviews.page');

Route::get('/places/{place}/reviews', [PlaceReviewController::class, 'index'])
    ->name('places.reviews.index');

/*
|--------------------------------------------------------------------------
| Welcome / Home
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
| Authenticated User Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserManagementController::class)->except(['show']);

        Route::get('users/export', [UserManagementController::class, 'export'])->name('users.export');
        Route::get('users/search', [UserManagementController::class, 'search'])->name('users.search');
    });

/*
|--------------------------------------------------------------------------
| Expert Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:expert'])
    ->prefix('expert')
    ->name('expert.')
    ->group(function () {

        Route::get('/dashboard', [ExpertDashboardController::class, 'index'])->name('dashboard');

        // Itinerary Invitations
        Route::get('/itinerary-invitations', [ExpertItineraryInvitationController::class, 'index'])->name('itinerary-invitations.index');
        Route::get('/itinerary-invitations/{invitation}', [ExpertItineraryInvitationController::class, 'show'])->name('itinerary-invitations.show');
        Route::post('/itinerary-invitations/{invitation}/accept', [ExpertItineraryInvitationController::class, 'accept'])->name('itinerary-invitations.accept');
        Route::post('/itinerary-invitations/{invitation}/decline', [ExpertItineraryInvitationController::class, 'decline'])->name('itinerary-invitations.decline');

        // Itineraries
        Route::get('/itineraries', [ExpertItineraryController::class, 'index'])->name('itineraries.index');
        Route::get('/itineraries/{itinerary}', [ExpertItineraryController::class, 'show'])->name('itineraries.show');
        Route::get('/itineraries/{itinerary}/edit', [ExpertItineraryEditController::class, 'edit'])->name('itineraries.edit');

        // Expert Editing & Suggestions
        Route::post('/itineraries/{itinerary}/suggest-replacement', [ExpertItineraryEditController::class, 'suggestReplacement'])->name('itineraries.suggest-replacement');
        Route::get('/itineraries/{itinerary}/items/{item}/suggestions', [ExpertItineraryEditController::class, 'getItemSuggestions'])->name('itineraries.item-suggestions');
        Route::get('/search-places', [ExpertItineraryEditController::class, 'searchPlaces'])->name('search-places');

        // Travelers
        Route::get('/travelers', [ExpertTravelerController::class, 'index'])->name('travelers.index');
        Route::get('/travelers/{traveler}', [ExpertTravelerController::class, 'show'])->name('travelers.show');

        // Messaging
        Route::get('/messages', [ExpertMessageController::class, 'inbox'])->name('messages.index');
        Route::get('/messages/{traveler}', [ExpertMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{traveler}', [ExpertMessageController::class, 'store'])->name('messages.store');

        // Expert's own profile
        Route::get('/profile', [ExpertProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ExpertProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ExpertProfileController::class, 'update'])->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Business Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:business'])
    ->prefix('business')
    ->name('business.')
    ->group(function () {

        Route::get('/dashboard', [BusinessDashboardController::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Traveler Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:traveler'])
    ->prefix('traveler')
    ->name('traveler.')
    ->group(function () {

        Route::get('/dashboard', [TravelerDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Itineraries
        |--------------------------------------------------------------------------
        */
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
            
      Route::get('/itineraries/{itinerary}/places', [TravelerItineraryController::class, 'placesJson']
            )->name('itineraries.places');

        // Expert Suggestions Management
        Route::get('/itineraries/{itinerary}/suggestions', [ExpertItineraryEditController::class, 'manageSuggestions'])
            ->name('itineraries.manage-suggestions');
        Route::post('/suggestions/{suggestion}/approve', [ExpertItineraryEditController::class, 'approveSuggestion'])
            ->name('suggestions.approve');
        Route::post('/suggestions/{suggestion}/reject', [ExpertItineraryEditController::class, 'rejectSuggestion'])
            ->name('suggestions.reject');



        /*
        |--------------------------------------------------------------------------
        | Preference Profiles
        |--------------------------------------------------------------------------
        */
        Route::resource('preference-profiles', PreferenceProfileController::class);

        Route::resource('preference-profiles.preferences', PreferenceController::class)
            ->shallow()
            ->only(['create', 'store', 'edit', 'update', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Rewards
        |--------------------------------------------------------------------------
        */
        Route::get('/rewards', [RewardsController::class, 'index'])
            ->name('rewards');

        /*
        |--------------------------------------------------------------------------
        | Traveler Messaging
        |--------------------------------------------------------------------------
        */
        Route::prefix('messages')->name('messages.')->group(function () {

            Route::get('/', [TravelerMessageController::class, 'index'])
                ->name('index');

            Route::get('/{expert}', [TravelerMessageController::class, 'show'])
                ->name('show');

            Route::post('/{expert}', [TravelerMessageController::class, 'store'])
                ->name('store');
        });

        /*
        |--------------------------------------------------------------------------
        | Local Experts
        |--------------------------------------------------------------------------
        */

        // List experts
        Route::get('/experts', [TravelerExpertsController::class, 'index'])
            ->name('experts.index');

        // Traveler-facing expert profile
        Route::get('/experts/{expert}', [TravelerExpertsController::class, 'show'])
            ->name('experts.show');

        /*
        |--------------------------------------------------------------------------
        | Place → Add to itinerary
        |--------------------------------------------------------------------------
        */
        Route::post('/places/{place}/add-to-itinerary', [ItineraryItemController::class, 'addPlace'])
            ->name('places.add-to-itinerary');
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
| PDF Export
|--------------------------------------------------------------------------
*/

Route::get('/itineraries/{itinerary}/pdf', ItineraryPdfController::class)
    ->middleware(['auth'])
    ->name('itineraries.pdf');

/*
|--------------------------------------------------------------------------
| Auth Scaffolding
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

