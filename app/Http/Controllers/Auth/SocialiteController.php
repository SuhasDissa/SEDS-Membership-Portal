<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialiteController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            // Download and store the avatar
            $avatarUrl = $this->downloadAvatar($googleUser->getAvatar(), $googleUser->getId());

            // Find existing user by email or google_id
            $user = \App\Models\User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if ($user) {
                // Delete old avatar if it exists and is not the default
                if ($user->avatar_url && strpos($user->avatar_url, 'storage/avatars/') !== false) {
                    $oldPath = str_replace('/storage/', '', $user->avatar_url);
                    Storage::disk('public')->delete($oldPath);
                }

                // Update existing user
                $user->update([
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $avatarUrl,
                    'email_verified_at' => now(),
                ]);
            } else {
                // Create new user with random password
                $user = \App\Models\User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $avatarUrl,
                    'email_verified_at' => now(),
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                    'role' => \App\Enums\UserRole::MEMBER->value,
                ]);
            }

            // Log the user in
            auth()->login($user);

            // Check if profile is completed
            if (!$user->hasCompletedProfile()) {
                return redirect()->route('onboarding');
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google: ' . $e->getMessage());
        }
    }

    /**
     * Download and store avatar from Google
     */
    private function downloadAvatar($googleAvatarUrl, $googleId)
    {
        try {
            // Fetch the avatar image
            $imageContent = file_get_contents($googleAvatarUrl);

            if ($imageContent === false) {
                return null;
            }

            // Generate a unique filename
            $filename = 'avatars/' . $googleId . '_' . time() . '.jpg';

            // Store the image in the public disk
            Storage::disk('public')->put($filename, $imageContent);

            // Return the public URL
            return '/storage/' . $filename;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Avatar Download Error: ' . $e->getMessage());
            return null;
        }
    }
}
