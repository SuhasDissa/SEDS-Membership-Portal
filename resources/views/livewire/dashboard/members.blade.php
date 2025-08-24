<?php

use App\Models\Member;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updateStatus($memberId, $status)
    {
        $member = Member::find($memberId);
        $member->update([
            'status' => $status,
            'approved_at' => $status === 'approved' ? now() : null,
        ]);

        session()->flash('message', "Member status updated to {$status}.");
    }

    public function with()
    {
        $query = Member::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $statusOptions = [
            ['id' => 'all', 'name' => 'All Status'],
            ['id' => 'pending', 'name' => 'Pending'],
            ['id' => 'approved', 'name' => 'Approved'],
            ['id' => 'rejected', 'name' => 'Rejected'],
        ];

        return [
            'members' => $query->orderBy('created_at', 'desc')->paginate(10),
            'totalMembers' => Member::count(),
            'pendingMembers' => Member::where('status', 'pending')->count(),
            'approvedMembers' => Member::where('status', 'approved')->count(),
            'statusOptions' => $statusOptions,
        ];
    }
};
?>


    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="mb-6">
            <x-mary-header title="Member Management" subtitle="Manage member registrations and applications" size="text-3xl" />
        </div>

        @if (session()->has('message'))
            <x-mary-alert icon="o-check-circle" class="alert-success mb-6" dismissible>
                {{ session('message') }}
            </x-mary-alert>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <x-mary-stat
                title="Total Members"
                value="{{ $totalMembers }}"
                icon="o-users"
                color="text-zinc-900 dark:text-white"
            />
            
            <x-mary-stat
                title="Pending Applications"
                value="{{ $pendingMembers }}"
                icon="o-clock"
                color="text-amber-600"
            />
            
            <x-mary-stat
                title="Approved Members"
                value="{{ $approvedMembers }}"
                icon="o-check-circle"
                color="text-green-600"
            />
        </div>
        <div>
            <div>
                <x-mary-select label="Filter by Status" :options="$statusOptions" option-value="id" option-label="name" wire:model.live="statusFilter" />
            </div>
        </div>

        <!-- Members Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 table-auto">
                    <thead class="bg-zinc-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Academic Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Interests</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Applied</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($members as $member)
                            <tr>
                                <td class="px-6 py-4 align-top">
                                    <div class="space-y-1">
                                        <div class="font-medium text-zinc-900 dark:text-white">{{ $member->full_name }}</div>
                                        <div class="text-sm text-zinc-500">{{ $member->email }}</div>
                                        <div class="text-sm text-zinc-500">{{ $member->phone }}</div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="space-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                                        <div>ID: {{ $member->student_id }}</div>
                                        <div class="text-zinc-600 dark:text-zinc-400">{{ $member->year === 'graduate' ? 'Graduate' : $member->year . ' Year' }}</div>
                                        <div>{{ $member->faculty }}</div>
                                        <div class="text-xs text-zinc-500">{{ $member->department }}</div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    @php
                                        $interests = $member->interests;
                                        if (!is_array($interests)) {
                                            $decoded = json_decode($interests, true);
                                            $interests = is_array($decoded) ? $decoded : [];
                                        }
                                    @endphp
                                    @if(!empty($interests))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($interests, 0, 3) as $interest)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200">
                                                    {{ $interest }}
                                                </span>
                                            @endforeach
                                            @if(count($interests) > 3)
                                                <span class="text-xs text-zinc-500 self-center">+{{ count($interests) - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-top">
                                    @php
                                        $status = $member->status;
                                        $statusClasses = $status === 'approved'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30'
                                            : ($status === 'rejected'
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900/30'
                                                : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30');
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium {{ $statusClasses }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 align-top text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $member->created_at->format('M j, Y') }}
                                </td>

                                <td class="px-6 py-4 align-top text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        @if($member->status === 'pending')
                                            <x-mary-button class="btn-success btn-sm" wire:click="updateStatus({{ $member->id }}, 'approved')">
                                                Approve
                                            </x-mary-button>
                                            <x-mary-button class="btn-outline btn-error btn-sm" wire:click="updateStatus({{ $member->id }}, 'rejected')">
                                                Reject
                                            </x-mary-button>
                                        @elseif($member->status === 'approved')
                                            <x-mary-button class="btn-outline btn-warning btn-sm" wire:click="updateStatus({{ $member->id }}, 'pending')">
                                                Set Pending
                                            </x-mary-button>
                                        @else
                                            <x-mary-button class="btn-success btn-sm" wire:click="updateStatus({{ $member->id }}, 'approved')">
                                                Approve
                                            </x-mary-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-6 text-center">
                                    <div class="flex flex-col items-center space-y-3">
                                        <svg class="h-12 w-12 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8z" />
                                        </svg>
                                        <div>
                                            <div class="text-lg font-medium text-zinc-900 dark:text-white">No members found</div>
                                            <div class="text-sm text-zinc-500">Try adjusting your search or filters</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>