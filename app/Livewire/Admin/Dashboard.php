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
            'total_members' => User::where('is_approved', true)->count(),
            'pending_members' => User::where('is_approved', false)->count(),
            'pending_contributions' => Contribution::where('status', 'pending')->count(),
            'approved_contributions' => Contribution::where('status', 'approved')->count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
