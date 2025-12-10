<?php

use function Livewire\Volt\{layout, title, state, computed, mount, on};
use App\Models\Post;
use App\Models\Contribution;

layout('components.layouts.app');
title('Dashboard');

state(['showContributionModal' => false]);
state(['contributionTitle' => '', 'contributionDescription' => '', 'contributionDate' => '']);

mount(function () {
    $this->contributionDate = now()->format('Y-m-d');
});

$posts = computed(function () {
    return Post::with('user')
        ->latest()
        ->take(10)
        ->get();
});

$monthlyContributions = computed(function () {
    return auth()->user()
        ->contributions()
        ->whereMonth('date', now()->month)
        ->whereYear('date', now()->year)
        ->get();
});

$contributionStats = computed(function () {
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
});

$submitContribution = function () {
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
};

on(['contribution-added' => function () {
    // Refresh computed properties
}]);

?>

<div class="p-6">
    {{-- Welcome Section --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-base-content/70">Here's what's happening in the SEDS community</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Feed Section --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contribution Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" data-aos="fade-up">
                <x-stat
                    title="Total"
                    :value="$this->contributionStats['total']"
                    icon="o-chart-bar"
                    class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
                />
                <x-stat
                    title="Approved"
                    :value="$this->contributionStats['approved']"
                    icon="o-check-circle"
                    class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
                />
                <x-stat
                    title="Pending"
                    :value="$this->contributionStats['pending']"
                    icon="o-clock"
                    class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
                />
                <x-stat
                    title="This Month"
                    :value="$this->contributionStats['this_month']"
                    icon="o-calendar"
                    class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
                />
            </div>

            {{-- Community Feed --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">
                        <x-icon name="o-newspaper" class="w-6 h-6" />
                        Community Feed
                    </h2>
                    
                    @if($this->posts->count() > 0)
                        <div class="space-y-4">
                            @foreach($this->posts as $post)
                                <div class="card bg-base-200">
                                    <div class="card-body">
                                        <div class="flex items-start gap-4">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary text-primary-content rounded-full w-12">
                                                    <span class="text-xl">{{ $post->user->initials() }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold">{{ $post->user->name }}</h3>
                                                <p class="text-sm text-base-content/70">{{ $post->created_at->diffForHumans() }}</p>
                                                <p class="mt-2">{{ $post->content }}</p>
                                                @if($post->image_url)
                                                    <img src="{{ $post->image_url }}" alt="Post image" class="mt-2 rounded-lg max-w-full" />
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                            <p class="text-base-content/70">No posts yet. Be the first to share something!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Contribution Tracker --}}
            <div class="card bg-base-100 text-primary-content shadow-sm hover:shadow-md transition-shadow" data-aos="fade-left">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">
                        <x-icon name="o-trophy" class="w-6 h-6" />
                        Contribution Tracker
                    </h2>
                    
                    <div class="stats stats-vertical shadow bg-base-200 text-base-content">
                        <div class="stat">
                            <div class="stat-title">This Month</div>
                            <div class="stat-value text-primary">{{ $this->monthlyContributions->count() }}</div>
                            <div class="stat-desc">{{ $this->monthlyContributions->where('status', 'approved')->count() }} approved</div>
                        </div>
                    </div>
                    
                    <button 
                        wire:click="$set('showContributionModal', true)" 
                        class="btn btn-primary w-full mt-4"
                    >
                        <x-icon name="o-plus-circle" class="w-5 h-5" />
                        Log Activity
                    </button>
                </div>
            </div>

            {{-- Recent Contributions --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-left" data-aos-delay="200">
                <div class="card-body">
                    <h3 class="card-title">
                        <x-icon name="o-list-bullet" class="w-5 h-5" />
                        Recent Activities
                    </h3>
                    
                    @if($this->monthlyContributions->count() > 0)
                        <ul class="space-y-2">
                            @foreach($this->monthlyContributions->take(5) as $contribution)
                                <li class="flex items-start gap-2">
                                    @if($contribution->status === 'approved')
                                        <x-icon name="o-check-circle" class="w-5 h-5 text-success flex-shrink-0 mt-0.5" />
                                    @else
                                        <x-icon name="o-clock" class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-medium text-sm">{{ $contribution->title }}</p>
                                        <p class="text-xs text-base-content/70">{{ $contribution->date->format('M d, Y') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-base-content/70 text-center py-4">No activities this month</p>
                    @endif
                    
                    <a href="{{ route('contributions.index') }}" class="btn btn-sm btn-outline w-full mt-4">
                        View All
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Contribution Modal --}}
    <x-modal wire:model="showContributionModal" title="Log New Activity" class="backdrop-blur">
        <form wire:submit.prevent="submitContribution">
            <div class="space-y-4">
                <x-input
                    label="Activity Title"
                    wire:model="contributionTitle"
                    placeholder="e.g., Attended workshop on satellite design"
                    inline
                    hint="Required"
                />
                @error('contributionTitle')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror

                <x-textarea
                    label="Description"
                    wire:model="contributionDescription"
                    placeholder="Describe your contribution..."
                    rows="4"
                    inline
                    hint="Required"
                />
                @error('contributionDescription')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror

                <x-input
                    label="Date"
                    wire:model="contributionDate"
                    type="date"
                    inline
                    hint="Required"
                />
                @error('contributionDate')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$set('showContributionModal', false)" />
                <x-button
                    label="Submit"
                    type="submit"
                    class="btn-primary"
                    spinner="submitContribution"
                />
            </x-slot:actions>
        </form>
    </x-modal>
</div>
