<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Contribution;
use App\Models\Post;

class Dashboard extends Component
{
    public function getStatsProperty()
    {
        return [
            'total_users' => User::count(),
            'approved_users' => User::where('is_approved', true)->count(),
            'pending_users' => User::where('is_approved', false)->count(),
            'total_contributions' => Contribution::count(),
            'pending_contributions' => Contribution::where('status', 'pending')->count(),
            'total_posts' => Post::count(),
        ];
    }

    public function getRecentUsersProperty()
    {
        return User::latest()->take(5)->get();
    }

    public function getPendingContributionsProperty()
    {
        return Contribution::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
