<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Component;

class ActivityLogs extends Component
{
    public $search = '';
    public $actionFilter = 'all';

    public function getLogsProperty()
    {
        $query = ActivityLog::with(['user', 'subject'])
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('action', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->actionFilter !== 'all') {
            $query->where('action', $this->actionFilter);
        }

        return $query->take(100)->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.activity-logs');
    }
}
