<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-base-content">Admin Dashboard</h1>
        <p class="text-base-content/70">Overview of SEDS Mora portal</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <x-stat
            title="Total Members"
            :value="$this->stats['total_users']"
            icon="o-users"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Approved Members"
            :value="$this->stats['approved_users']"
            icon="o-check-circle"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Pending Approval"
            :value="$this->stats['pending_users']"
            icon="o-clock"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Total Contributions"
            :value="$this->stats['total_contributions']"
            icon="o-chart-bar"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Pending Contributions"
            :value="$this->stats['pending_contributions']"
            icon="o-exclamation-circle"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Total Posts"
            :value="$this->stats['total_posts']"
            icon="o-newspaper"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="card-body">
                <h2 class="card-title">
                    <x-icon name="o-user-plus" class="w-6 h-6" />
                    Recent Members
                </h2>
                
                @if($this->recentUsers->count() > 0)
                    <div class="space-y-2">
                        @foreach($this->recentUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary text-primary-content rounded-full w-10">
                                            <span class="text-sm">{{ $user->initials() }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $user->name }}</p>
                                        <p class="text-sm text-base-content/70">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div>
                                    @if($user->is_approved)
                                        <span class="badge badge-success badge-sm">Approved</span>
                                    @else
                                        <span class="badge badge-warning badge-sm">Pending</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('admin.members') }}" class="btn btn-outline btn-sm w-full mt-4">
                        View All Members
                    </a>
                @else
                    <p class="text-center text-base-content/70 py-8">No members yet</p>
                @endif
            </div>
        </div>

        {{-- Pending Contributions --}}
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="card-body">
                <h2 class="card-title">
                    <x-icon name="o-clock" class="w-6 h-6" />
                    Pending Contributions
                </h2>
                
                @if($this->pendingContributions->count() > 0)
                    <div class="space-y-2">
                        @foreach($this->pendingContributions as $contribution)
                            <div class="p-3 bg-base-200 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium">{{ $contribution->title }}</p>
                                        <p class="text-sm text-base-content/70">{{ $contribution->user->name }}</p>
                                        <p class="text-xs text-base-content/60 mt-1">{{ $contribution->date->format('M d, Y') }}</p>
                                    </div>
                                    <span class="badge badge-warning badge-sm">Pending</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-base-content/70 py-8">No pending contributions</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow mt-6">
        <div class="card-body">
            <h2 class="card-title">
                <x-icon name="o-bolt" class="w-6 h-6" />
                Quick Actions
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <a href="{{ route('admin.members') }}" class="btn btn-primary">
                    <x-icon name="o-users" class="w-5 h-5" />
                    Manage Members
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <x-icon name="o-home" class="w-5 h-5" />
                    View Dashboard
                </a>
                <a href="{{ route('settings') }}" class="btn btn-accent">
                    <x-icon name="o-cog-6-tooth" class="w-5 h-5" />
                    Settings
                </a>
            </div>
        </div>
    </div>
</div>
