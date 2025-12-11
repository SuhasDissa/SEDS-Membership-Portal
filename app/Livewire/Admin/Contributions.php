<?php

namespace App\Livewire\Admin;

use App\Models\Contribution;
use App\Models\ActivityLog;
use App\Models\Notification;
use Livewire\Component;

class Contributions extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $showRejectModal = false;
    public $selectedContributionId = null;
    public $rejectionReason = '';

    // Bulk actions
    public $selectedIds = [];
    public $selectAll = false;
    public $showBulkRejectModal = false;
    public $bulkRejectionReason = '';

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

        ActivityLog::log('approved_contribution', $contribution, [
            'title' => $contribution->title,
            'member' => $contribution->user->name,
        ]);

        // Send notification to user
        Notification::notify(
            $contribution->user_id,
            'contribution_approved',
            '🎉 Contribution Approved!',
            "Your contribution '{$contribution->title}' has been approved and is now visible to all members."
        );

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

        // Send notification to user
        Notification::notify(
            $contribution->user_id,
            'contribution_rejected',
            '❌ Contribution Rejected',
            "Your contribution '{$contribution->title}' was not approved. Reason: {$this->rejectionReason}",
            ['rejection_reason' => $this->rejectionReason]
        );

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

    // Bulk Actions
    public function bulkApprove()
    {
        if (empty($this->selectedIds)) {
            session()->flash('error', 'No contributions selected.');
            return;
        }

        Contribution::whereIn('id', $this->selectedIds)
            ->update(['status' => 'approved', 'rejection_reason' => null]);

        $count = count($this->selectedIds);
        session()->flash('success', "{$count} contribution(s) approved successfully!");

        $this->selectedIds = [];
    }

    public function openBulkRejectModal()
    {
        if (empty($this->selectedIds)) {
            session()->flash('error', 'No contributions selected.');
            return;
        }

        $this->bulkRejectionReason = '';
        $this->showBulkRejectModal = true;
    }

    public function bulkReject()
    {
        $this->validate([
            'bulkRejectionReason' => 'required|string|max:500',
        ]);

        if (empty($this->selectedIds)) {
            session()->flash('error', 'No contributions selected.');
            return;
        }

        Contribution::whereIn('id', $this->selectedIds)
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $this->bulkRejectionReason,
            ]);

        $count = count($this->selectedIds);
        session()->flash('success', "{$count} contribution(s) rejected.");

        $this->selectedIds = [];
        $this->showBulkRejectModal = false;
        $this->bulkRejectionReason = '';
    }

    public function bulkDelete()
    {
        if (empty($this->selectedIds)) {
            session()->flash('error', 'No contributions selected.');
            return;
        }

        Contribution::whereIn('id', $this->selectedIds)->delete();

        $count = count($this->selectedIds);
        session()->flash('success', "{$count} contribution(s) deleted.");

        $this->selectedIds = [];
    }

    public function render()
    {
        return view('livewire.admin.contributions');
    }
}
