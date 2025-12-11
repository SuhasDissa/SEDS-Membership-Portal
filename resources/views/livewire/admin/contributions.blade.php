<div class="p-6">
    {{-- Header --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">Manage Contributions</h1>
        <p class="text-base-content/70">Review, approve, or reject member contributions</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" data-aos="fade-up">
        <x-stat title="Total" :value="$this->stats['total']" icon="o-chart-bar"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Pending" :value="$this->stats['pending']" icon="o-clock"
            class="bg-warning text-warning-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Approved" :value="$this->stats['approved']" icon="o-check-circle"
            class="bg-success text-success-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Rejected" :value="$this->stats['rejected']" icon="o-x-circle"
            class="bg-error text-error-content shadow-sm hover:shadow-md transition-shadow" />
    </div>

    {{-- Filters --}}
    <div class="card bg-base-100 shadow-sm mb-6" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Search" wire:model.live="search" placeholder="Search by title or member name..."
                    icon="o-magnifying-glass" inline />
                <x-select label="Status Filter" wire:model.live="statusFilter" :options="[
        ['id' => 'all', 'name' => 'All Status'],
        ['id' => 'pending', 'name' => 'Pending'],
        ['id' => 'approved', 'name' => 'Approved'],
        ['id' => 'rejected', 'name' => 'Rejected'],
    ]" icon="o-funnel" inline />
            </div>
        </div>
    </div>

    {{-- Contributions Table --}}
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="200">
        <div class="card-body">
            @if($this->contributions->count() > 0)
                {{-- Bulk Actions Toolbar --}}
                @if(count($selectedIds) > 0)
                    <div class="alert alert-info mb-4 flex justify-between items-center">
                        <span><strong>{{ count($selectedIds) }}</strong> contribution(s) selected</span>
                        <div class="flex gap-2">
                            <button wire:click="bulkApprove" class="btn btn-success btn-sm">
                                <x-icon name="o-check" class="w-4 h-4" /> Approve
                            </button>
                            <button wire:click="openBulkRejectModal" class="btn btn-error btn-sm">
                                <x-icon name="o-x-mark" class="w-4 h-4" /> Reject
                            </button>
                            <button wire:click="bulkDelete" wire:confirm="Delete {{ count($selectedIds) }} selected contributions?" class="btn btn-ghost btn-sm">
                                <x-icon name="o-trash" class="w-4 h-4" /> Delete
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" 
                                           wire:model.live="selectAll" 
                                           class="checkbox checkbox-sm" 
                                           title="Select All" />
                                </th>
                                <th>Title</th>
                                <th>Member</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->contributions as $contribution)
                                <tr wire:key="contribution-{{ $contribution->id }}">
                                    <td>
                                        <input type="checkbox" 
                                               wire:model.live="selectedIds" 
                                               value="{{ $contribution->id }}" 
                                               class="checkbox checkbox-sm" />
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-medium">{{ $contribution->title }}</p>
                                            <p class="text-sm text-base-content/70">
                                                {{ Str::limit($contribution->description, 80) }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-medium">{{ $contribution->user->name }}</p>
                                            <p class="text-xs text-base-content/60">{{ $contribution->user->email }}</p>
                                        </div>
                                    </td>
                                    <td>{{ $contribution->date->format('M d, Y') }}</td>
                                    <td>
                                        @if($contribution->status === 'approved')
                                            <span class="badge badge-success gap-2">
                                                <x-icon name="o-check-circle" class="w-4 h-4" />
                                                Approved
                                            </span>
                                        @elseif($contribution->status === 'rejected')
                                            <div>
                                                <span class="badge badge-error gap-2">
                                                    <x-icon name="o-x-circle" class="w-4 h-4" />
                                                    Rejected
                                                </span>
                                                @if($contribution->rejection_reason)
                                                    <p class="text-xs text-base-content/60 mt-1">
                                                        {{ Str::limit($contribution->rejection_reason, 50) }}</p>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge badge-warning gap-2">
                                                <x-icon name="o-clock" class="w-4 h-4" />
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            @if($contribution->status === 'pending')
                                                <button wire:click="approveContribution({{ $contribution->id }})"
                                                    class="btn btn-success btn-sm" title="Approve">
                                                    <x-icon name="o-check" class="w-4 h-4" />
                                                </button>
                                                <button wire:click="openRejectModal({{ $contribution->id }})"
                                                    class="btn btn-error btn-sm" title="Reject">
                                                    <x-icon name="o-x-mark" class="w-4 h-4" />
                                                </button>
                                            @endif
                                            <button wire:click="deleteContribution({{ $contribution->id }})"
                                                wire:confirm="Are you sure you want to delete this contribution?"
                                                class="btn btn-ghost btn-sm" title="Delete">
                                                <x-icon name="o-trash" class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-base-content/70">No contributions found</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Reject Modal --}}
    @if($showRejectModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Reject Contribution</h3>

                <x-textarea label="Rejection Reason" wire:model="rejectionReason"
                    placeholder="Provide a reason for rejection (will be visible to the member)..." rows="4"
                    hint="This will be sent to the member" />

                <div class="modal-action">
                    <button wire:click="rejectContribution" class="btn btn-error">
                        <x-icon name="o-x-mark" class="w-5 h-5" />
                        Reject
                    </button>
                    <button wire:click="$set('showRejectModal', false)" class="btn btn-ghost">Cancel</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="$set('showRejectModal', false)"></div>
        </div>
    @endif

    {{-- Bulk Reject Modal --}}
    @if($showBulkRejectModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Bulk Reject Contributions</h3>
                <p class="mb-4">Rejecting <strong>{{ count($selectedIds) }}</strong> contribution(s)</p>
                
                <x-textarea
                    label="Rejection Reason"
                    wire:model="bulkRejectionReason"
                    placeholder="Provide a reason for rejection (will be applied to all selected contributions)..."
                    rows="4"
                    hint="This reason will be sent to all selected members"
                />

                <div class="modal-action">
                    <button wire:click="bulkReject" class="btn btn-error">
                        <x-icon name="o-x-mark" class="w-5 h-5" />
                        Reject All
                    </button>
                    <button wire:click="$set('showBulkRejectModal', false)" class="btn btn-ghost">Cancel</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="$set('showBulkRejectModal', false)"></div>
        </div>
    @endif
</div>