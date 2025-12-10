<?php

use function Livewire\Volt\{layout, title, computed};

layout('components.layouts.app');
title('My Profile');

$user = computed(fn () => auth()->user());

?>

<div class="p-6">
    {{-- Header --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">My Profile</h1>
        <p class="text-base-content/70">View your profile information</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-right">
            <div class="card-body items-center text-center">
                <div class="avatar placeholder mb-4">
                    <div class="bg-primary text-primary-content rounded-full w-32">
                        <span class="text-4xl">{{ $this->user->initials() }}</span>
                    </div>
                </div>
                
                <h2 class="card-title text-2xl">{{ $this->user->name }}</h2>
                <p class="text-base-content/70">{{ $this->user->email }}</p>
                
                <div class="flex gap-2 mt-4">
                    @if($this->user->is_approved)
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-warning">Pending Approval</span>
                    @endif
                    
                    @if($this->user->is_admin)
                        <span class="badge badge-accent">Admin</span>
                    @endif
                </div>
                
                <a href="{{ route('settings') }}" class="btn btn-primary w-full mt-4">
                    <x-icon name="o-pencil" class="w-5 h-5" />
                    Edit Profile
                </a>
            </div>
        </div>

        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-left">
                <div class="card-body">
                    <h3 class="card-title text-xl mb-4">
                        <x-icon name="o-identification" class="w-6 h-6" />
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">University ID</span>
                            </label>
                            <p class="text-base-content">{{ $this->user->university_id }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Faculty</span>
                            </label>
                            <p class="text-base-content">{{ $this->user->faculty }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Department</span>
                            </label>
                            <p class="text-base-content">{{ $this->user->department }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Phone</span>
                            </label>
                            <p class="text-base-content">{{ $this->user->phone }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Member Since</span>
                            </label>
                            <p class="text-base-content">{{ $this->user->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Email Verified</span>
                            </label>
                            <p class="text-base-content">
                                @if($this->user->email_verified_at)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-warning">Not Verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Summary --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-left" data-aos-delay="200">
                <div class="card-body">
                    <h3 class="card-title text-xl mb-4">
                        <x-icon name="o-chart-bar" class="w-6 h-6" />
                        Activity Summary
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-title">Contributions</div>
                            <div class="stat-value text-primary">{{ $this->user->contributions()->count() }}</div>
                        </div>
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-title">Approved</div>
                            <div class="stat-value text-success">{{ $this->user->contributions()->where('status', 'approved')->count() }}</div>
                        </div>
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-title">Posts</div>
                            <div class="stat-value text-accent">{{ $this->user->posts()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
