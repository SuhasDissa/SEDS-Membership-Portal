<?php

use function Livewire\Volt\{layout, title, state, rules, mount, computed};
use App\Models\User;

layout('components.layouts.guest');
title('Complete Your Profile');

state([
    'university_id' => '',
    'faculty' => '',
    'department' => '',
    'phone' => '',
    'avatar_url' => '',
]);

$faculties = computed(fn () => [
    ['id' => 'Engineering', 'name' => 'Engineering'],
    ['id' => 'IT', 'name' => 'Information Technology'],
    ['id' => 'Architecture', 'name' => 'Architecture'],
    ['id' => 'Business', 'name' => 'Business'],
    ['id' => 'Science', 'name' => 'Science'],
    ['id' => 'Other', 'name' => 'Other'],
]);

rules([
    'university_id' => 'required|string|unique:users,university_id|regex:/^[0-9]{6}[A-Z]$/',
    'faculty' => 'required|in:Engineering,IT,Architecture,Business,Science,Other',
    'department' => 'required|string|max:255',
    'phone' => 'required|string|max:15',
]);

mount(function () {
    $user = auth()->user();
    
    // Pre-fill if user has Google avatar
    if ($user->avatar_url) {
        $this->avatar_url = $user->avatar_url;
    }
});

$completeProfile = function () {
    $this->validate();
    
    $user = auth()->user();
    
    $user->update([
        'university_id' => $this->university_id,
        'faculty' => $this->faculty,
        'department' => $this->department,
        'phone' => $this->phone,
        'avatar_url' => $this->avatar_url ?: $user->avatar_url,
    ]);
    
    session()->flash('success', 'Profile completed successfully!');
    
    return redirect()->route('dashboard');
};

?>

<div class="min-h-screen bg-base-200 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-2xl">
        <div>
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <x-app-logo-icon class="w-20 h-20 text-primary" />
                </div>
                <h1 class="text-4xl font-bold text-primary mb-2">Welcome to SEDS Mora!</h1>
                <p class="text-base-content/70">Please complete your profile to continue</p>
            </div>

            {{-- Profile Completion Card --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <form wire:submit="completeProfile">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- University ID --}}
                            <div class="md:col-span-2">
                                <x-input 
                                    label="University ID" 
                                    wire:model="university_id" 
                                    icon="o-identification" 
                                    placeholder="230152X"
                                    hint="Format: 6 digits followed by a letter (e.g., 230152X)"
                                    inline
                                />
                            </div>
                            
                            {{-- Faculty --}}
                            <div class="md:col-span-2">
                                <x-select 
                                    label="Faculty" 
                                    wire:model="faculty" 
                                    :options="$this->faculties" 
                                    placeholder="Select your faculty"
                                    icon="o-academic-cap"
                                    inline
                                />
                            </div>
                            
                            {{-- Department --}}
                            <div class="md:col-span-2">
                                <x-input 
                                    label="Department" 
                                    wire:model="department" 
                                    icon="o-building-library" 
                                    placeholder="e.g., Computer Science & Engineering"
                                    inline
                                />
                            </div>
                            
                            {{-- Phone --}}
                            <div class="md:col-span-2">
                                <x-input 
                                    label="Phone Number" 
                                    wire:model="phone" 
                                    icon="o-phone" 
                                    placeholder="+94 77 123 4567"
                                    inline
                                />
                            </div>
                            
                            {{-- Avatar URL (Optional) --}}
                            <div class="md:col-span-2">
                                <x-input 
                                    label="Profile Photo URL (Optional)" 
                                    wire:model="avatar_url" 
                                    icon="o-photo" 
                                    placeholder="https://example.com/photo.jpg"
                                    hint="Leave blank to use default avatar"
                                    inline
                                />
                            </div>
                        </div>
                        
                        {{-- Preview Avatar --}}
                        @if($avatar_url)
                            <div class="mt-4 flex justify-center">
                                <div class="avatar">
                                    <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="{{ $avatar_url }}" alt="Profile Preview" />
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Submit Button --}}
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary w-full">
                                <x-icon name="o-check-circle" class="w-5 h-5" />
                                Complete Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
