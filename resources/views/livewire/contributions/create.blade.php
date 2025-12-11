<div class="p-6">
    {{-- Check if user is approved --}}
    @if(!auth()->user()->is_approved)
        <div class="alert alert-warning shadow-lg mb-6" data-aos="fade-down">
            <div>
                <x-icon name="o-exclamation-triangle" class="w-6 h-6" />
                <span>
                    <strong>Approval Required</strong><br>
                    Your account has not been approved yet. Please wait for admin approval before logging contributions.
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
    @endif
</div>
