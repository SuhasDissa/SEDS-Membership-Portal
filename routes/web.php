<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Auth\SocialiteController;

// Landing Page (Public)
Volt::route('/', 'landing.index')->name('landing');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Volt::route('/register', 'auth.register')->name('register');
    Volt::route('/login', 'auth.login')->name('login');
    
    // Google OAuth
    Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Protected Routes (Requires Authentication)
Route::middleware('auth')->group(function () {
    
    // Onboarding/Profile Completion (accessible even without completed profile)
    Volt::route('/onboarding', 'onboarding.complete-profile')->name('onboarding');
    
    // Routes that require completed profile
    Route::middleware('profile.completed')->group(function () {
        
        // Student Dashboard
        Volt::route('/dashboard', 'dashboard.index')->name('dashboard');
        
        // Profile & Settings
        Volt::route('/profile', 'profile.show')->name('profile.show');
        Volt::route('/settings', 'settings.index')->name('settings');
        
        // Contributions
        Volt::route('/contributions', 'contributions.index')->name('contributions.index');
        Volt::route('/contributions/create', 'contributions.create')->name('contributions.create');
        
        // Admin Routes (Protected by admin middleware)
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Volt::route('/', 'admin.dashboard')->name('dashboard');
            Volt::route('/members', 'admin.members.index')->name('members');
            Volt::route('/members/{user}', 'admin.members.show')->name('members.show');
        });
    });
});
