<?php

namespace App\Services;

use App\Models\Contribution;
use App\Models\User;
use App\Models\Post;
use Carbon\Carbon;

class AnalyticsService
{
    public function getContributionStats(string $period = 'month', ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Contribution::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        return [
            'total' => $query->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'approval_rate' => $this->calculateApprovalRate($query),
        ];
    }

    public function getTopContributors(int $limit = 10, string $period = 'all'): array
    {
        $query = User::withCount([
            'contributions' => function ($q) use ($period) {
                $q->where('status', 'approved');

                if ($period === 'month') {
                    $q->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                } elseif ($period === 'year') {
                    $q->whereYear('created_at', now()->year);
                }
            }
        ])
            ->where('is_approved', true)
            ->orderBy('contributions_count', 'desc')
            ->limit($limit)
            ->get();

        return $query->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'count' => $user->contributions_count,
            ];
        })->toArray();
    }

    public function getMonthlyContributions(int $year = null): array
    {
        $year = $year ?? now()->year;

        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = Contribution::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'approved')
                ->count();

            $data[] = [
                'month' => Carbon::create($year, $month)->format('M'),
                'count' => $count,
            ];
        }

        return $data;
    }

    public function getMemberGrowth(): array
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('is_approved', true)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function calculateApprovalRate($query): float
    {
        $total = (clone $query)->whereIn('status', ['approved', 'rejected'])->count();

        if ($total === 0) {
            return 0;
        }

        $approved = (clone $query)->where('status', 'approved')->count();

        return round(($approved / $total) * 100, 2);
    }

    public function getContributionCategoryBreakdown(): array
    {
        // This would need contribution type/category field in future
        return [];
    }
}
