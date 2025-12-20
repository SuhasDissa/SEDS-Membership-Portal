<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user has admin role or higher
        if (!$user || !$user->hasMinimumRole(\App\Enums\UserRole::ADMIN)) {
            abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
}
