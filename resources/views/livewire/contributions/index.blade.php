<?php

use function Livewire\Volt\{layout, title, computed};

layout('components.layouts.app');
title('My Contributions');

$contributions = computed(function () {
    return auth()->user()
        ->contributions()
        ->latest('date')
        ->get();
});

$stats = computed(function () {
    $user = auth()->user();
    
    return [
        'total' => $user->contributions()->count(),
        'approved' => $user->contributions()->where('status', 'approved')->count(),
        'pending' => $user->contributions()->where('status', 'pending')->count(),
    ];
});

?>

<div class="p-6">
    {{-- Header --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">My Contributions</h1>
        <p class="text-base-content/70">Track all your activities and contributions</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" data-aos="fade-up">
        <x-stat
            title="Total Contributions"
            :value="$this->stats['total']"
            icon="o-chart-bar"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Approved"
            :value="$this->stats['approved']"
            icon="o-check-circle"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Pending Review"
            :value="$this->stats['pending']"
            icon="o-clock"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
    </div>

    {{-- Add New Button --}}
    <div class="mb-6" data-aos="fade-up" data-aos-delay="200">
        <a href="{{ route('contributions.create') }}" class="btn btn-primary">
            <x-icon name="o-plus-circle" class="w-5 h-5" />
            Log New Activity
        </a>
    </div>

    {{-- Contributions List --}}
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="400">
        <div class="card-body">
            @if($this->contributions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->contributions as $contribution)
                                <tr>
                                    <td class="font-medium">{{ $contribution->title }}</td>
                                    <td>{{ Str::limit($contribution->description, 100) }}</td>
                                    <td>{{ $contribution->date->format('M d, Y') }}</td>
                                    <td>
                                        @if($contribution->status === 'approved')
                                            <span class="badge badge-success gap-2">
                                                <x-icon name="o-check-circle" class="w-4 h-4" />
                                                Approved
                                            </span>
                                        @else
                                            <span class="badge badge-warning gap-2">
                                                <x-icon name="o-clock" class="w-4 h-4" />
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-base-content/70 mb-4">No contributions yet</p>
                    <a href="{{ route('contributions.create') }}" class="btn btn-primary">
                        <x-icon name="o-plus-circle" class="w-5 h-5" />
                        Log Your First Activity
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
