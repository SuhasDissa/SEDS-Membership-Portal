<?php

namespace App\Livewire\Admin;

use App\Models\Contribution;
use Livewire\Component;

class Contributions extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $showRejectModal = false;
    public $selectedContributionId = null;
    public $rejectionReason = '';

    public function getContributionsProperty()
    {
        $query = Contribution::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->latest('date')->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Contribution::count(),
            'pending' => Contribution::where('status', 'pending')->count(),
            'approved' => Contribution::where('status', 'approved')->count(),
            'rejected' => Contribution::where('status', 'rejected')->count(),
        ];
    }

    public function approveContribution($contributionId)
    {
        $contribution = Contribution::findOrFail($contributionId);
        $contribution->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        session()->flash('success', "Contribution '{$contribution->title}' has been approved!");
    }

    public function openRejectModal($contributionId)
    {
        $this->selectedContributionId = $contributionId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function rejectContribution()
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:500',
        ]);

        $contribution = Contribution::findOrFail($this->selectedContributionId);
        $contribution->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        session()->flash('success', "Contribution '{$contribution->title}' has been rejected.");

        $this->showRejectModal = false;
        $this->selectedContributionId = null;
        $this->rejectionReason = '';
    }

    public function deleteContribution($contributionId)
    {
        $contribution = Contribution::findOrFail($contributionId);
        $title = $contribution->title;
        $contribution->delete();

        session()->flash('success', "Contribution '{$title}' has been deleted.");
    }

    public function render()
    {
        return view('livewire.admin.contributions');
    }
}
