<div class="p-6">
    {{-- Header --}}
    <div class="mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-base-content">Admin Dashboard</h1>
        <p class="text-base-content/70">Manage SEDS Mora members</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" data-aos="fade-up">
        <x-stat
            title="Total Members"
            :value="$this->stats['total_users']"
            icon="o-users"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Approved"
            :value="$this->stats['approved_users']"
            icon="o-check-circle"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Pending"
            :value="$this->stats['pending_users']"
            icon="o-clock"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
        <x-stat
            title="Admins"
            :value="$this->stats['admin_users']"
            icon="o-shield-check"
            class="bg-primary text-primary-content shadow-sm hover:shadow-md transition-shadow"
        />
    </div>

    {{-- Members Table --}}
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="200">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4">
                <x-icon name="o-user-group" class="w-6 h-6" />
                Member Management
            </h2>

            {{-- Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <x-input 
                    wire:model.live="search" 
                    icon="o-magnifying-glass" 
                    placeholder="Search by name, email, or university ID..."
                />
                
                <x-select 
                    wire:model.live="facultyFilter" 
                    :options="$this->faculties" 
                    icon="o-funnel"
                />
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>University ID</th>
                            <th>Faculty</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-10">
                                                <span class="text-sm">{{ $user->initials() }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $user->name }}</div>
                                            <div class="text-sm opacity-50">{{ $user->department ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-ghost">{{ $user->university_id ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $user->faculty ?? 'N/A' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_approved)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $roleLabel = $user->getRoleLabel();
                                        $badgeClass = match($user->role) {
                                            \App\Enums\UserRole::ADMIN->value => 'badge-accent',
                                            \App\Enums\UserRole::BOARD_MEMBER->value => 'badge-info',
                                            default => 'badge-ghost',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $roleLabel }}</span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        @if(!$user->is_approved)
                                            <button 
                                                wire:click="approveUser({{ $user->id }})" 
                                                class="btn btn-success btn-xs"
                                                title="Approve User"
                                            >
                                                <x-icon name="o-check" class="w-4 h-4" />
                                            </button>
                                        @else
                                            <button 
                                                wire:click="banUser({{ $user->id }})" 
                                                class="btn btn-error btn-xs"
                                                title="Ban User"
                                            >
                                                <x-icon name="o-x-mark" class="w-4 h-4" />
                                            </button>
                                        @endif
                                        
                                        <button 
                                            wire:click="toggleAdmin({{ $user->id }})" 
                                            class="btn btn-accent btn-xs"
                                            title="Change Role (Current: {{ $user->getRoleLabel() }})"
                                        >
                                            <x-icon name="o-shield-check" class="w-4 h-4" />
                                        </button>
                                        
                                        <a 
                                            href="{{ route('admin.members.show', $user) }}" 
                                            class="btn btn-primary btn-xs"
                                            title="View Details"
                                        >
                                            <x-icon name="o-eye" class="w-4 h-4" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8">
                                    <x-icon name="o-inbox" class="w-16 h-16 mx-auto text-base-content/30 mb-2" />
                                    <p class="text-base-content/70">No members found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
