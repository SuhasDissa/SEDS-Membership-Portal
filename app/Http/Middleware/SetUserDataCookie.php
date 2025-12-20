<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserDataCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only set cookie for authenticated users
        if ($user = $request->user()) {
            // Prepare user data
            $userData = [
                'name' => $user->name,
                'role' => $user->getRoleLabel(),
                'avatar_url' => $user->avatar, // Uses the getAvatarAttribute accessor
            ];
            
            // Encode as base64 JSON
            $encodedData = base64_encode(json_encode($userData));
            
            // Set cookie for .sedsmora.org domain
            $cookie = cookie(
                name: 'seds_user_data',
                value: $encodedData,
                minutes: config('session.lifetime', 120), // Match session lifetime
                path: '/',
                domain: '.sedsmora.org',
                secure: true,        // HTTPS only
                httpOnly: false,     // Allow JavaScript access on main site
                raw: false,
                sameSite: 'none'     // Allow cross-site access
            );
            
            $response->withCookie($cookie);
        }
        
        return $response;
    }
}
