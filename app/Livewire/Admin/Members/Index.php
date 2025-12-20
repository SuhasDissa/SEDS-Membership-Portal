<?php

namespace App\Livewire\Admin\Members;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public $search = '';
    public $facultyFilter = '';

    public function getFacultiesProperty()
    {
        $faculties = collect(User::UNIVERSITY_DATA['faculties'])->map(fn($f) => [
            'id' => $f['name'],
            'name' => $f['name']
        ])->toArray();

        return array_merge([['id' => '', 'name' => 'All Faculties']], $faculties);
    }

    public function getUsersProperty()
    {
        $query = User::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('university_id', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->facultyFilter) {
            $query->where('faculty', $this->facultyFilter);
        }
        
        return $query->latest()->get();
    }

    public function getStatsProperty()
    {
        return [
            'total_users' => User::count(),
            'approved_users' => User::where('is_approved', true)->count(),
            'pending_users' => User::where('is_approved', false)->count(),
            'admin_users' => User::where('role', \App\Enums\UserRole::ADMIN->value)->count(),
            'board_members' => User::where('role', \App\Enums\UserRole::BOARD_MEMBER->value)->count(),
            'regular_members' => User::where('role', \App\Enums\UserRole::MEMBER->value)->count(),
        ];
    }

    public function approveUser($userId)
    {
        $user = User::find($userId);
        $user->update(['is_approved' => true]);
        
        session()->flash('success', "User {$user->name} has been approved!");
    }

    public function banUser($userId)
    {
        $user = User::find($userId);
        $user->update(['is_approved' => false]);
        
        session()->flash('success', "User {$user->name} has been banned!");
    }

    public function toggleAdmin($userId)
    {
        $user = User::find($userId);
        
        // Cycle through roles: Member -> Board Member -> Admin -> Member
        $newRole = match($user->role) {
            \App\Enums\UserRole::MEMBER->value => \App\Enums\UserRole::BOARD_MEMBER->value,
            \App\Enums\UserRole::BOARD_MEMBER->value => \App\Enums\UserRole::ADMIN->value,
            \App\Enums\UserRole::ADMIN->value => \App\Enums\UserRole::MEMBER->value,
            default => \App\Enums\UserRole::MEMBER->value,
        };
        
        $user->update(['role' => $newRole]);
        
        $roleLabel = \App\Enums\UserRole::fromValue($newRole)->label();
        session()->flash('success', "User {$user->name} has been changed to {$roleLabel}!");
    }

    public function render()
    {
        return view('livewire.admin.members.index');
    }
}
