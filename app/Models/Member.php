<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'student_id',
        'year',
        'faculty',
        'department',
        'motivation',
        'interests',
        'has_programming_experience',
        'programming_languages',
        'has_space_projects_experience',
        'space_projects_description',
        'status',
    ];

    protected $casts = [
        'interests' => 'array',
        'has_programming_experience' => 'boolean',
        'has_space_projects_experience' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
