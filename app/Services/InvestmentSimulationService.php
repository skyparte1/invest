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

        if (! is_finite($annualRate) || $annualRate <= -100 || $annualRate > 100) {
            throw new InvalidArgumentException('Taxa anual fora do intervalo permitido.');
        }
        $monthlyRate = expm1(log1p($annualRate / 100) / 12);

        $contributionsTotal = $periodicContribution * $months;
        $totalInvested = $initialAmount + $contributionsTotal;

        $balance = $initialAmount;
        $invested = $initialAmount;
        $series = [];

        for ($month = 1; $month <= $months; $month++) {
            $balance *= 1 + $monthlyRate;
            $balance += $periodicContribution;
            $invested = $initialAmount + $periodicContribution * $month;
            if ($annualRate == 0.0) {
                $balance = $invested;
            }

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
            'final_balance' => $balance,
            'estimated_earnings' => $annualRate == 0.0 ? 0.0 : $balance - $totalInvested,
            'series' => $series,
        ];
    }
}
