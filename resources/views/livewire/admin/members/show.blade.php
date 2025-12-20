<div class="p-6">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.members') }}" class="btn btn-ghost">
            <x-icon name="o-arrow-left" class="w-5 h-5" />
            Back to Members
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="card-body items-center text-center">
                <div class="avatar placeholder mb-4">
                    <div class="bg-primary text-primary-content rounded-full w-32">
                        <span class="text-4xl">{{ $user->initials() }}</span>
                    </div>
                </div>
                
                <h2 class="card-title text-2xl">{{ $user->name }}</h2>
                <p class="text-base-content/70">{{ $user->email }}</p>
                
                <div class="flex gap-2 mt-4">
                    @if($user->is_approved)
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                    
                    @php
                        $roleLabel = $user->getRoleLabel();
                        $badgeClass = match($user->role) {
                            \App\Enums\UserRole::ADMIN->value => 'badge-accent',
                            \App\Enums\UserRole::BOARD_MEMBER->value => 'badge-info',
                            default => 'badge-ghost',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $roleLabel }}</span>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
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
                            <p class="text-base-content">{{ $user->university_id ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Faculty</span>
                            </label>
                            <p class="text-base-content">{{ $user->faculty ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Department</span>
                            </label>
                            <p class="text-base-content">{{ $user->department ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Phone</span>
                            </label>
                            <p class="text-base-content">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Joined</span>
                            </label>
                            <p class="text-base-content">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Email Verified</span>
                            </label>
                            <p class="text-base-content">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-warning">Not Verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contributions --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <h3 class="card-title text-xl mb-4">
                        <x-icon name="o-chart-bar" class="w-6 h-6" />
                        Contributions
                    </h3>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-title">Total</div>
                            <div class="stat-value text-primary">{{ $user->contributions()->count() }}</div>
                        </div>
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-title">Approved</div>
                            <div class="stat-value text-success">{{ $user->contributions()->where('status', 'approved')->count() }}</div>
                        </div>
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-title">Pending</div>
                            <div class="stat-value text-warning">{{ $user->contributions()->where('status', 'pending')->count() }}</div>
                        </div>
                    </div>
                    
                    @if($user->contributions()->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->contributions()->latest()->take(5)->get() as $contribution)
                                        <tr>
                                            <td>
                                                <div class="font-medium">{{ $contribution->title }}</div>
                                                <div class="text-sm text-base-content/70">{{ Str::limit($contribution->description, 50) }}</div>
                                            </td>
                                            <td>{{ $contribution->date->format('M d, Y') }}</td>
                                            <td>
                                                @if($contribution->status === 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-2" />
                            <p class="text-base-content/70">No contributions yet</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Posts --}}
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <h3 class="card-title text-xl mb-4">
                        <x-icon name="o-newspaper" class="w-6 h-6" />
                        Recent Posts
                    </h3>
                    
                    @if($user->posts()->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->posts()->latest()->take(3)->get() as $post)
                                <div class="card bg-base-200">
                                    <div class="card-body">
                                        <p>{{ $post->content }}</p>
                                        <p class="text-sm text-base-content/70">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-2" />
                            <p class="text-base-content/70">No posts yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
