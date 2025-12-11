<div class="p-6">
    <h1 class="text-3xl font-bold text-base-content mb-6">All Notifications</h1>

    <div class="space-y-2">
        @forelse($this->notifications as $notification)
            <div wire:key="notification-{{ $notification->id }}" wire:click="markAsRead({{ $notification->id }})" class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow cursor-pointer
                            {{ is_null($notification->read_at) ? 'border-l-4 border-primary' : '' }}">
                <div class="card-body p-4">
                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            @if($notification->type === 'contribution_approved')
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-success/20">
                                    <x-icon name="o-check-circle" class="w-6 h-6 text-success" />
                                </div>
                            @elseif($notification->type === 'contribution_rejected')
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-error/20">
                                    <x-icon name="o-x-circle" class="w-6 h-6 text-error" />
                                </div>
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-info/20">
                                    <x-icon name="o-bell" class="w-6 h-6 text-info" />
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $notification->title }}</h3>
                            <p class="text-sm text-base-content/70 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-base-content/50 mt-2">
                                {{ $notification->created_at->format('M d, Y \a\t h:i A') }}
                            </p>
                        </div>

                        {{-- Unread badge --}}
                        @if(is_null($notification->read_at))
                            <div class="flex-shrink-0">
                                <span class="badge badge-primary badge-sm">New</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body text-center py-12">
                    <x-icon name="o-bell-slash" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-lg text-base-content/70">No notifications yet</p>
                </div>
            </div>
        @endforelse
    </div>
</div>