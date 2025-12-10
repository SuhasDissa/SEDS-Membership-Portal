<?php

use function Livewire\Volt\{layout, title, state, rules};

layout('components.layouts.app');
title('Log New Activity');

state(['title' => '', 'description' => '', 'date' => '']);

rules([
    'title' => 'required|string|max:255',
    'description' => 'required|string',
    'date' => 'required|date',
]);

$submit = function () {
    $this->validate();
    
    auth()->user()->contributions()->create([
        'title' => $this->title,
        'description' => $this->description,
        'date' => $this->date,
        'status' => 'pending',
    ]);
    
    session()->flash('success', 'Contribution logged successfully!');
    
    return redirect()->route('contributions.index');
};

?>

<div class="p-6">
    {{-- Header --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">Log New Activity</h1>
        <p class="text-base-content/70">Record your contribution to SEDS Mora</p>
    </div>

    <div class="max-w-2xl">
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up">
            <div class="card-body">
                <form wire:submit="submit">
                    <x-input 
                        label="Activity Title" 
                        wire:model="title" 
                        placeholder="e.g., Attended workshop on satellite design"
                        inline
                    />
                    
                    <x-textarea 
                        label="Description" 
                        wire:model="description" 
                        placeholder="Describe your contribution in detail..."
                        rows="6"
                        inline
                    />
                    
                    <x-input 
                        label="Date" 
                        wire:model="date" 
                        type="date"
                        inline
                    />
                    
                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="btn btn-primary">
                            <x-icon name="o-check-circle" class="w-5 h-5" />
                            Submit
                        </button>
                        <a href="{{ route('contributions.index') }}" class="btn btn-ghost">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
