<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-base-content">Activity Logs</h1>
        <p class="text-base-content/70">Track all admin actions and system events</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <x-stat title="Total Actions" :value="$this->stats['total']" icon="o-clipboard-document-list"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow" />
        <x-stat title="Today's Actions" :value="$this->stats['today']" icon="o-calendar"
            class="bg-accent text-accent-content shadow-sm hover:shadow-md transition-shadow" />
    </div>

    {{-- Filters --}}
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Search" wire:model.live="search" placeholder="Search by action or admin name..."
                    icon="o-magnifying-glass" inline />
                <x-select label="Action Filter" wire:model.live="actionFilter" :options="[
        ['id' => 'all', 'name' => 'All Actions'],
        ['id' => 'approved_contribution', 'name' => 'Approved Contribution'],
        ['id' => 'rejected_contribution', 'name' => 'Rejected Contribution'],
        ['id' => 'created_post', 'name' => 'Created Post'],
        ['id' => 'deleted_post', 'name' => 'Deleted Post'],
    ]" icon="o-funnel" inline />
            </div>
        </div>
    </div>

    {{-- Activity Logs Table --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            @if($this->logs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Admin</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->logs as $log)
                                <tr>
                                    <td>
                                        <div>
                                            <p class="font-medium">{{ $log->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-base-content/60">{{ $log->created_at->format('h:i A') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-medium">{{ $log->user->name }}</p>
                                            <p class="text-xs text-base-content/60">{{ $log->user->email }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($log->properties)
                                            <div class="text-sm">
                                                @foreach($log->properties as $key => $value)
                                                    <p><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</p>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-base-content/50">-</span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-base-content/60">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-base-content/70">No activity logs found</p>
                </div>
            @endif
        </div>
    </div>
</div>