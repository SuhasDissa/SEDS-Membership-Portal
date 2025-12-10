<?php

namespace App\Livewire\Dashboard;

use App\Models\Post;
use App\Models\Contribution;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public bool $showContributionModal = false;
    public string $contributionTitle = '';
    public string $contributionDescription = '';
    public string $contributionDate = '';

    public function mount()
    {
        $this->contributionDate = now()->format('Y-m-d');
    }

    public function getPostsProperty()
    {
        return Post::with('user')
            ->latest()
            ->take(10)
            ->get();
    }

    public function getMonthlyContributionsProperty()
    {
        return auth()->user()
            ->contributions()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();
    }

    public function getContributionStatsProperty()
    {
        $user = auth()->user();

        return [
            'total' => $user->contributions()->count(),
            'approved' => $user->contributions()->where('status', 'approved')->count(),
            'pending' => $user->contributions()->where('status', 'pending')->count(),
            'this_month' => $user->contributions()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),
        ];
    }

    public function submitContribution()
    {
        $this->validate([
            'contributionTitle' => 'required|string|max:255',
            'contributionDescription' => 'required|string',
            'contributionDate' => 'required|date',
        ]);

        auth()->user()->contributions()->create([
            'title' => $this->contributionTitle,
            'description' => $this->contributionDescription,
            'date' => $this->contributionDate,
            'status' => 'pending',
        ]);

        $this->showContributionModal = false;
        $this->contributionTitle = '';
        $this->contributionDescription = '';
        $this->contributionDate = now()->format('Y-m-d');

        session()->flash('success', 'Contribution logged successfully!');

        $this->dispatch('contribution-added');
    }

    #[On('contribution-added')]
    public function refreshContributions()
    {
        // This will refresh computed properties
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
