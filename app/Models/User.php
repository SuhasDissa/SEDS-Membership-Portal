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

    public const UNIVERSITY_DATA = [
        "university" => "University of Moratuwa, Sri Lanka",
        "faculties" => [
            [
                "name" => "Faculty of Architecture",
                "departments" => [
                    "Department of Architecture",
                    "Department of Building Economics",
                    "Department of Town and Country Planning",
                    "Department of Integrated Design"
                ]
            ],
            [
                "name" => "Faculty of Engineering",
                "departments" => [
                    "Department of Chemical and Process Engineering",
                    "Department of Civil Engineering",
                    "Department of Computer Science and Engineering",
                    "Department of Earth Resources Engineering",
                    "Department of Electrical Engineering",
                    "Department of Electronic and Telecommunication Engineering",
                    "Department of Materials Science and Engineering",
                    "Department of Mechanical Engineering",
                    "Department of Textile and Apparel Engineering",
                    "Department of Transport Management and Logistic Engineering"
                ]
            ],
            [
                "name" => "Faculty of Information Technology",
                "departments" => [
                    "Department of Information Technology",
                    "Department of Computational Mathematics",
                    "Department of Interdisciplinary Studies"
                ]
            ],
            [
                "name" => "Faculty of Business",
                "departments" => [
                    "Department of Decision Sciences",
                    "Department of Industrial Management",
                    "Department of Management of Technology"
                ]
            ],
            [
                "name" => "Faculty of Medicine",
                "departments" => [
                    "Department of Anatomy",
                    "Department of Biochemistry and Clinical Chemistry",
                    "Department of Medical Education",
                    "Department of Medical Technology",
                    "Department of Medicine and Mental Health",
                    "Department of Microbiology and Parasitology",
                    "Department of Pathology and Forensic Medicine",
                    "Department of Pediatrics and Neonatology",
                    "Department of Pharmacology",
                    "Department of Physiology",
                    "Department of Public Health and Family Medicine",
                    "Department of Surgery and Anesthesia"
                ]
            ]
        ]
    ];

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
        'role',
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
            'role' => 'integer',
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
            ->map(fn($word) => Str::substr($word, 0, 1))
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

    /**
     * Notifications relationship
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Activity logs relationship
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(\App\Enums\UserRole $role): bool
    {
        return $this->role === $role->value;
    }

    /**
     * Check if user has minimum required role level
     */
    public function hasMinimumRole(\App\Enums\UserRole $role): bool
    {
        return $this->role >= $role->value;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role >= \App\Enums\UserRole::ADMIN->value;
    }

    /**
     * Check if user is a board member or higher
     */
    public function isBoardMember(): bool
    {
        return $this->role >= \App\Enums\UserRole::BOARD_MEMBER->value;
    }

    /**
     * Check if user is a member
     */
    public function isMember(): bool
    {
        return $this->role >= \App\Enums\UserRole::MEMBER->value;
    }

    /**
     * Get the user's role label
     */
    public function getRoleLabel(): string
    {
        $roleEnum = \App\Enums\UserRole::fromValue($this->role);
        return $roleEnum ? $roleEnum->label() : 'Unknown';
    }
}
