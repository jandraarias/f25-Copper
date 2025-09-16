<?php

use Illuminate\Support\Facades\Route;

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Welcome Admin!';
    });
});

// Local Expert-only routes
Route::middleware(['auth', 'role:expert'])->group(function () {
    Route::get('/expert/dashboard', function () {
        return 'Expert Dashboard';
    });
});

// Business-only routes
Route::middleware(['auth', 'role:business'])->group(function () {
    Route::get('/business/dashboard', function () {
        return 'Business Dashboard';
    });
});

// Traveler-only routes
Route::middleware(['auth', 'role:traveler'])->group(function () {
    Route::get('/traveler/dashboard', function () {
        return 'Traveler Dashboard';
    });
});
