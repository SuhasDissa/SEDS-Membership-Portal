<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'university_id',
        'faculty',
        'department',
        'phone',
        'avatar_url',
        'bio',
        'skills',
        'interests',
        'is_admin',
        'is_approved',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_approved' => 'boolean',
            'skills' => 'array',
            'interests' => 'array',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user has completed their profile
     */
    public function hasCompletedProfile(): bool
    {
        return !is_null($this->university_id) 
            && !is_null($this->faculty) 
            && !is_null($this->department) 
            && !is_null($this->phone);
    }

    /**
     * Get the user's avatar URL or generate a default one
     */
    public function getAvatarAttribute(): string
    {
        if (!$this->avatar_url) {
            return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=random";
        }

        // If it's a full URL (Google avatar), return it as is
        if (filter_var($this->avatar_url, FILTER_VALIDATE_URL)) {
            return $this->avatar_url;
        }

        // Otherwise, it's a storage path, return the public URL
        return asset('storage/' . $this->avatar_url);
    }

    /**
     * Contributions relationship
     */
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    /**
     * Posts relationship
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
