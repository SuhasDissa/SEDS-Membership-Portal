<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\SocialiteController;

// Landing Page (Public)
Route::view('/', 'pages.landing')->name('landing');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::view('/register', 'pages.auth.register')->name('register');
    Route::view('/login', 'pages.auth.login')->name('login');
    
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

// Email Verification Routes
Route::middleware('auth')->group(function () {
    // Email verification notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Email verification handler
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('onboarding')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    // Resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Protected Routes (Requires Authentication)
Route::middleware('auth')->group(function () {

    // Onboarding/Profile Completion (accessible even without completed profile)
    Route::view('/onboarding', 'pages.onboarding.complete-profile')->name('onboarding');
    
    // Routes that require completed profile
    Route::middleware('profile.completed')->group(function () {
        
        // Student Dashboard
        Route::view('/dashboard', 'pages.dashboard.index')->name('dashboard');
        
        // Profile & Settings
        Route::view('/profile', 'pages.profile.show')->name('profile.show');
        Route::view('/settings', 'pages.settings.index')->name('settings');

        // Contributions
        Route::view('/contributions', 'pages.contributions.index')->name('contributions.index');
        Route::view('/contributions/create', 'pages.contributions.create')->name('contributions.create');
        
        // Admin Routes (Protected by admin middleware)
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::view('/', 'pages.admin.dashboard')->name('dashboard');
            Route::view('/members', 'pages.admin.members.index')->name('members');
            Route::get('/members/{user}', function (\App\Models\User $user) {
                return view('pages.admin.members.show', compact('user'));
            })->name('members.show');
        });
    });
});
