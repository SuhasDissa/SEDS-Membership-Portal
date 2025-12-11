<div class="p-6">
    {{-- Check if user is approved --}}
    @if(!auth()->user()->is_approved)
        <div class="alert alert-warning shadow-lg mb-6">
            <div>
                <x-icon name="o-exclamation-triangle" class="w-6 h-6" />
                <span>
                    <strong>Approval Required</strong><br>
                    Your account has not been approved yet. Please wait for admin approval.
                </span>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('contributions.index') }}" class="btn btn-ghost">
                Back to Contributions
            </a>
        </div>
    @else
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-base-content">Edit Activity</h1>
            <p class="text-base-content/70">Update your contribution details</p>

            {{-- Status Notice --}}
            @if($contribution->status === 'approved')
                <div class="alert alert-info mt-4">
                    <x-icon name="o-information-circle" class="w-6 h-6" />
                    <span>This contribution has been approved and can no longer be edited.</span>
                </div>
            @else
                <div class="alert alert-warning mt-4">
                    <x-icon name="o-exclamation-triangle" class="w-6 h-6" />
                    <span>You can edit this contribution until it is approved by an admin.</span>
                </div>
            @endif
        </div>

        <div class="max-w-2xl">
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <form wire:submit="update">
                        <x-input label="Activity Title" wire:model="title"
                            placeholder="e.g., Attended workshop on satellite design" inline />

                        <x-textarea label="Description" wire:model="description"
                            placeholder="Describe your contribution in detail..." rows="6" inline />

                        <x-input label="Date" wire:model="date" type="date" inline />

                        <div class="flex gap-4 mt-6">
                            <button type="submit" class="btn btn-primary">
                                <x-icon name="o-check-circle" class="w-5 h-5" />
                                Update
                            </button>
                            <a href="{{ route('contributions.index') }}" class="btn btn-ghost">
                                Cancel
                            </a>
                            <button type="button" wire:click="delete"
                                wire:confirm="Are you sure you want to delete this contribution?"
                                class="btn btn-error ml-auto">
                                <x-icon name="o-trash" class="w-5 h-5" />
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>