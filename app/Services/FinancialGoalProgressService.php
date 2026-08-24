<?php

namespace App\Services;

use App\Models\FinancialGoal;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class FinancialGoalProgressService
{
    public function calculate(FinancialGoal $goal, ?CarbonInterface $today = null): array
    {
        $today = ($today ?? CarbonImmutable::today())->startOfDay();
        $targetDate = $goal->target_date->toImmutable()->startOfDay();
        $targetAmount = (float) $goal->target_amount;
        $currentAmount = (float) $goal->current_amount;

        $completed = $currentAmount >= $targetAmount;
        $expired = $targetDate->isBefore($today) && ! $completed;
        $progressPercentage = ($currentAmount / $targetAmount) * 100;
        $remainingAmount = max(0.0, $targetAmount - $currentAmount);
        $daysRemaining = max(0, (int) $today->diffInDays($targetDate, false));

        $monthsReference = $expired
            ? 0
            : (($targetDate->year * 12 + $targetDate->month) - ($today->year * 12 + $today->month)) + 1;

        $monthlyReference = match (true) {
            $completed => 0.0,
            $expired => null,
            default => $remainingAmount / $monthsReference,
        };

        return [
            'completed' => $completed,
            'surpassed' => $currentAmount > $targetAmount,
            'expired' => $expired,
            'progress_percentage' => $progressPercentage,
            'progress_bar_percentage' => min(100.0, $progressPercentage),
            'remaining_amount' => $remainingAmount,
            'days_remaining' => $daysRemaining,
            'months_reference' => $monthsReference,
            'monthly_reference' => $monthlyReference,
        ];
    }

    public function summarize(Collection $presentedGoals): array
    {
        return [
            'active' => $presentedGoals->filter(
                fn (array $item) => ! $item['metrics']['completed'] && ! $item['metrics']['expired']
            )->count(),
            'completed' => $presentedGoals->where('metrics.completed', true)->count(),
            'target_total' => $presentedGoals->sum(fn (array $item) => (float) $item['goal']->target_amount),
            'current_total' => $presentedGoals->sum(fn (array $item) => (float) $item['goal']->current_amount),
        ];
    }
}
