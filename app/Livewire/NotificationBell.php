<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $showDropdown = false;

    public function getNotificationsProperty()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();
    }

    public function getUnreadCountProperty()
    {
        return auth()->user()
            ->notifications()
            ->unread()
            ->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);

        if ($notification->user_id === auth()->id()) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        $this->dispatch('notifications-read');
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
