<?php

namespace App\Services;

use InvalidArgumentException;

class InvestmentSimulationService
{
    public function simulate(
        float $initialAmount,
        float $periodicContribution,
        float $annualRate,
        int $term,
        string $termUnit = 'months',
    ): array {
        $months = match ($termUnit) {
            'months' => $term,
            'years' => $term * 12,
            default => throw new InvalidArgumentException('A unidade do prazo deve ser months ou years.'),
        };

        $annualRateDecimal = $annualRate / 100;
        $monthlyRate = (1 + $annualRateDecimal) ** (1 / 12) - 1;
        $growthFactor = (1 + $monthlyRate) ** $months;

        $futureInitialAmount = $initialAmount * $growthFactor;
        $futureContributions = $monthlyRate == 0.0
            ? $periodicContribution * $months
            : $periodicContribution * (($growthFactor - 1) / $monthlyRate);

        $contributionsTotal = $periodicContribution * $months;
        $totalInvested = $initialAmount + $contributionsTotal;
        $finalBalance = $futureInitialAmount + $futureContributions;

        $balance = $initialAmount;
        $invested = $initialAmount;
        $series = [];

        for ($month = 1; $month <= $months; $month++) {
            $balance *= 1 + $monthlyRate;
            $balance += $periodicContribution;
            $invested += $periodicContribution;

            $series[] = [
                'month' => $month,
                'invested' => $invested,
                'balance' => $balance,
            ];
        }

        return [
            'initial_amount' => $initialAmount,
            'periodic_contribution' => $periodicContribution,
            'annual_rate' => $annualRate,
            'monthly_rate' => $monthlyRate,
            'term' => $term,
            'term_unit' => $termUnit,
            'months' => $months,
            'contributions_total' => $contributionsTotal,
            'total_invested' => $totalInvested,
            'final_balance' => $finalBalance,
            'estimated_earnings' => (float) max(0, $finalBalance - $totalInvested),
            'series' => $series,
        ];
    }
}
