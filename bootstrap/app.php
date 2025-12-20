<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'profile.completed' => \App\Http\Middleware\EnsureProfileCompleted::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
        
        // Exclude seds_user_data cookie from encryption so it's readable by JavaScript
        $middleware->encryptCookies(except: [
            'seds_user_data',
        ]);
        
        // Add SetUserDataCookie to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\SetUserDataCookie::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
