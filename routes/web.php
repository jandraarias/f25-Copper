<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\PublicItineraryController;
use App\Http\Controllers\ItineraryPdfController;

Route::get('/i/{uuid}', [PublicItineraryController::class, 'show'])
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

// Profile routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Role-based dashboards
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // User Management
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    });

Route::middleware(['auth', 'role:expert'])->group(function () {
    Route::get('/expert/dashboard', [ExpertController::class, 'index'])->name('expert.dashboard');
});

Route::middleware(['auth', 'role:business'])->group(function () {
    Route::get('/business/dashboard', [BusinessController::class, 'index'])->name('business.dashboard');
});

Route::middleware(['auth', 'role:traveler'])->group(function () {
    Route::get('/traveler/dashboard', [TravelerController::class, 'index'])->name('traveler.dashboard');
});

Route::get('/itineraries/{itinerary}/pdf', ItineraryPdfController::class)
    ->middleware(['web', 'auth'])
    ->name('itineraries.pdf');

// Breeze authentication routes
require __DIR__.'/auth.php';
