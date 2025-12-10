<?php

namespace App\Livewire\Contributions;

use Livewire\Component;

class Index extends Component
{
    public function getContributionsProperty()
    {
        return auth()->user()
            ->contributions()
            ->latest('date')
            ->get();
    }

    public function getStatsProperty()
    {
        $user = auth()->user();

        return [
            'total' => $user->contributions()->count(),
            'approved' => $user->contributions()->where('status', 'approved')->count(),
            'pending' => $user->contributions()->where('status', 'pending')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.contributions.index');
    }
}
