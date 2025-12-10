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
        return [
            ['id' => '', 'name' => 'All Faculties'],
            ['id' => 'Engineering', 'name' => 'Engineering'],
            ['id' => 'IT', 'name' => 'Information Technology'],
            ['id' => 'Architecture', 'name' => 'Architecture'],
            ['id' => 'Business', 'name' => 'Business'],
            ['id' => 'Science', 'name' => 'Science'],
            ['id' => 'Other', 'name' => 'Other'],
        ];
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
            'admin_users' => User::where('is_admin', true)->count(),
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
        $user->update(['is_admin' => !$user->is_admin]);
        
        $status = $user->is_admin ? 'promoted to admin' : 'removed from admin';
        session()->flash('success', "User {$user->name} has been {$status}!");
    }

    public function render()
    {
        return view('livewire.admin.members.index');
    }
}
