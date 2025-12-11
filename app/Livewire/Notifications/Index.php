<?php

namespace App\Livewire\Notifications;

use Livewire\Component;

class Index extends Component
{
    public function getNotificationsProperty()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->get();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function render()
    {
        return view('livewire.notifications.index');
    }
}
