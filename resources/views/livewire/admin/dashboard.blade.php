<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-base-content">Admin Dashboard</h1>
        <p class="text-base-content/70">Overview of your SEDS Membership Portal</p>
    </div>

    {{-- Statistics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Members Stats --}}
        <div
            class="card bg-gradient-to-br from-primary to-primary-focus text-primary-content shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="card-title text-3xl font-bold">{{ $this->stats['total_members'] }}</h2>
                        <p class="opacity-90">Total Members</p>
                    </div>
                    <x-icon name="o-users" class="w-16 h-16 opacity-50" />
                </div>
                @if($this->stats['pending_members'] > 0)
                    <div class="mt-4 pt-4 border-t border-primary-content/20">
                        <p class="text-sm">
                            <span class="badge badge-warning badge-sm">{{ $this->stats['pending_members'] }}</span>
                            Pending Approval
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pending Contributions --}}
        <div
            class="card bg-gradient-to-br from-warning to-warning-focus text-warning-content shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="card-title text-3xl font-bold">{{ $this->stats['pending_contributions'] }}</h2>
                        <p class="opacity-90">Pending Contributions</p>
                    </div>
                    <x-icon name="o-clock" class="w-16 h-16 opacity-50" />
                </div>
                <div class="mt-4 pt-4 border-t border-warning-content/20">
                    <a href="{{ route('admin.contributions') }}" class="text-sm hover:underline">
                        View All Contributions →
                    </a>
                </div>
            </div>
        </div>

        {{-- Approved Contributions --}}
        <div
            class="card bg-gradient-to-br from-success to-success-focus text-success-content shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="card-title text-3xl font-bold">{{ $this->stats['approved_contributions'] }}</h2>
                        <p class="opacity-90">Approved Contributions</p>
                    </div>
                    <x-icon name="o-check-circle" class="w-16 h-16 opacity-50" />
                </div>
            </div>
        </div>

        {{-- Total Posts --}}
        <div
            class="card bg-gradient-to-br from-info to-info-focus text-info-content shadow-lg hover:shadow-xl transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="card-title text-3xl font-bold">{{ $this->stats['total_posts'] }}</h2>
                        <p class="opacity-90">Total Posts</p>
                    </div>
                    <x-icon name="o-newspaper" class="w-16 h-16 opacity-50" />
                </div>
                <div class="mt-4 pt-4 border-t border-info-content/20">
                    <p class="text-sm">
                        {{ $this->stats['published_posts'] }} Published
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow col-span-1 md:col-span-2">
            <div class="card-body">
                <h3 class="card-title">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <a href="{{ route('admin.members') }}" class="btn btn-outline">
                        <x-icon name="o-users" class="w-5 h-5" />
                        Manage Members
                    </a>
                    <a href="{{ route('admin.contributions') }}" class="btn btn-outline">
                        <x-icon name="o-chart-bar" class="w-5 h-5" />
                        Manage Contributions
                    </a>
                    <a href="{{ route('admin.posts') }}" class="btn btn-outline">
                        <x-icon name="o-newspaper" class="w-5 h-5" />
                        Manage Posts
                    </a>
                    <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline">
                        <x-icon name="o-clipboard-document-list" class="w-5 h-5" />
                        Activity Logs
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Welcome Message --}}
    <div class="alert alert-info mt-6">
        <x-icon name="o-information-circle" class="w-6 h-6" />
        <div>
            <h3 class="font-bold">Welcome to the Admin Dashboard!</h3>
            <div class="text-sm">Use the navigation menu to manage members, contributions, posts, and view activity
                logs.</div>
        </div>
    </div>
</div>