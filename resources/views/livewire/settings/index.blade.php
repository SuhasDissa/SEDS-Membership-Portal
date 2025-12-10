<?php

use function Livewire\Volt\{layout, title, state, rules, mount, computed};

layout('components.layouts.app');
title('Settings');

state([
    'name' => '',
    'email' => '',
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
    'name' => 'required|string|max:255',
    'email' => 'required|email',
    'university_id' => 'required|string',
    'faculty' => 'required|in:Engineering,IT,Architecture,Business,Science,Other',
    'department' => 'required|string|max:255',
    'phone' => 'required|string|max:15',
]);

mount(function () {
    $user = auth()->user();
    
    $this->name = $user->name;
    $this->email = $user->email;
    $this->university_id = $user->university_id;
    $this->faculty = $user->faculty;
    $this->department = $user->department;
    $this->phone = $user->phone;
    $this->avatar_url = $user->avatar_url ?? '';
});

$updateProfile = function () {
    $this->validate();
    
    $user = auth()->user();
    
    $user->update([
        'name' => $this->name,
        'email' => $this->email,
        'university_id' => $this->university_id,
        'faculty' => $this->faculty,
        'department' => $this->department,
        'phone' => $this->phone,
        'avatar_url' => $this->avatar_url,
    ]);
    
    session()->flash('success', 'Profile updated successfully!');
};

?>

<div class="p-6">
    {{-- Header --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">Settings</h1>
        <p class="text-base-content/70">Manage your account settings</p>
    </div>

    <div class="max-w-3xl">
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up">
            <div class="card-body">
                <h2 class="card-title text-xl mb-4">Profile Information</h2>
                
                <form wire:submit="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div class="md:col-span-2">
                            <x-input 
                                label="Full Name" 
                                wire:model="name" 
                                icon="o-user"
                                inline
                            />
                        </div>
                        
                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <x-input 
                                label="Email" 
                                wire:model="email" 
                                type="email" 
                                icon="o-envelope"
                                inline
                            />
                        </div>
                        
                        {{-- University ID --}}
                        <x-input 
                            label="University ID" 
                            wire:model="university_id" 
                            icon="o-identification"
                            inline
                        />
                        
                        {{-- Faculty --}}
                        <x-select 
                            label="Faculty" 
                            wire:model="faculty" 
                            :options="$this->faculties" 
                            icon="o-academic-cap"
                            inline
                        />
                        
                        {{-- Department --}}
                        <div class="md:col-span-2">
                            <x-input 
                                label="Department" 
                                wire:model="department" 
                                icon="o-building-library"
                                inline
                            />
                        </div>
                        
                        {{-- Phone --}}
                        <div class="md:col-span-2">
                            <x-input 
                                label="Phone Number" 
                                wire:model="phone" 
                                icon="o-phone"
                                inline
                            />
                        </div>
                        
                        {{-- Avatar URL --}}
                        <div class="md:col-span-2">
                            <x-input 
                                label="Profile Photo URL (Optional)" 
                                wire:model="avatar_url" 
                                icon="o-photo"
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
                    
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">
                            <x-icon name="o-check-circle" class="w-5 h-5" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
