<div class="dropdown dropdown-end" x-data="{ open: false }">
    {{-- Notification Bell Button --}}
    <button @click="open = !open" class="btn btn-ghost btn-circle relative">
        <x-icon name="o-bell" class="w-6 h-6" />

        @if($this->unreadCount > 0)
            <span class="absolute top-1 right-1 flex h-5 w-5 items-center justify-center">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-error opacity-75"></span>
                <span
                    class="relative inline-flex h-5 w-5 items-center justify-center rounded-full bg-error text-[10px] font-bold text-white">
                    {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
                </span>
            </span>
        @endif
    </button>

    {{-- Dropdown Content --}}
    <div x-show="open" @click.away="open = false" x-transition
        class="dropdown-content z-50 mt-3 w-96 rounded-lg bg-base-100 shadow-xl border border-base-300">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-base-300 px-4 py-3">
            <h3 class="font-bold text-lg">Notifications</h3>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="btn btn-ghost btn-xs">
                    Mark all as read
                </button>
            @endif
        </div>

        {{-- Notifications List --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}" wire:click="markAsRead({{ $notification->id }})" class="border-b border-base-300 px-4 py-3 hover:bg-base-200 cursor-pointer transition-colors
                                {{ is_null($notification->read_at) ? 'bg-primary/5' : '' }}">

                    <div class="flex items-start gap-3">
                        {{-- Icon based on type --}}
                        <div class="flex-shrink-0 mt-1">
                            @if($notification->type === 'contribution_approved')
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-success/20">
                                    <x-icon name="o-check-circle" class="w-5 h-5 text-success" />
                                </div>
                            @elseif($notification->type === 'contribution_rejected')
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-error/20">
                                    <x-icon name="o-x-circle" class="w-5 h-5 text-error" />
                                </div>
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-info/20">
                                    <x-icon name="o-bell" class="w-5 h-5 text-info" />
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm">{{ $notification->title }}</p>
                            <p class="text-sm text-base-content/70 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-base-content/50 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Unread indicator --}}
                        @if(is_null($notification->read_at))
                            <div class="flex-shrink-0">
                                <div class="h-2 w-2 rounded-full bg-primary"></div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <x-icon name="o-bell-slash" class="w-12 h-12 mx-auto text-base-content/30 mb-2" />
                    <p class="text-base-content/70">No notifications yet</p>
                </div>
            @endforelse
        </div>

        {{-- Footer with "View All" link (optional) --}}
        @if($this->notifications->count() > 0)
            <div class="border-t border-base-300 px-4 py-2 text-center">
                <a href="{{ route('notifications') }}" class="text-sm text-primary hover:underline">
                    View all notifications
                </a>
            </div>
        @endif
    </div>
</div>