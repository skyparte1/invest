<?php

namespace Tests\Unit;

use App\Services\InvestmentSimulationService;
use PHPUnit\Framework\TestCase;

class InvestmentSimulationServiceTest extends TestCase
{
    private InvestmentSimulationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new InvestmentSimulationService;
    }

    public function test_zero_rate_without_contributions_keeps_initial_amount(): void
    {
        $result = $this->service->simulate(1000, 0, 0, 12);

        $this->assertSame(1000.0, $result['total_invested']);
        $this->assertSame(1000.0, $result['final_balance']);
        $this->assertSame(0.0, $result['estimated_earnings']);
    }

    public function test_zero_rate_with_contributions_matches_total_invested(): void
    {
        $result = $this->service->simulate(1000, 100, 0, 12);

        $this->assertSame(1200.0, $result['contributions_total']);
        $this->assertSame(2200.0, $result['total_invested']);
        $this->assertSame(2200.0, $result['final_balance']);
        $this->assertSame(0.0, $result['estimated_earnings']);
    }

    public function test_annual_rate_compounds_initial_amount_to_one_year_rate(): void
    {
        $result = $this->service->simulate(1000, 0, 10, 12);

        $this->assertEqualsWithDelta(1100, $result['final_balance'], 0.000001);
    }

    public function test_monthly_contributions_use_compound_annuity_formula(): void
    {
        $result = $this->service->simulate(500, 100, 12, 12);
        $monthlyRate = (1.12 ** (1 / 12)) - 1;
        $expected = 500 * (1 + $monthlyRate) ** 12
            + 100 * (((1 + $monthlyRate) ** 12 - 1) / $monthlyRate);

        $this->assertEqualsWithDelta($expected, $result['final_balance'], 0.000001);
    }

    public function test_annual_rate_is_converted_to_equivalent_monthly_rate(): void
    {
        $result = $this->service->simulate(0, 0, 10, 1);

        $this->assertEqualsWithDelta((1.10 ** (1 / 12)) - 1, $result['monthly_rate'], 0.000000000001);
    }

    public function test_twelve_months_and_one_year_are_equivalent(): void
    {
        $months = $this->service->simulate(1000, 200, 10, 12, 'months');
        $year = $this->service->simulate(1000, 200, 10, 1, 'years');

        $this->assertSame(12, $year['months']);
        $this->assertEqualsWithDelta($months['final_balance'], $year['final_balance'], 0.000001);
    }

    public function test_closed_formula_and_iterative_series_are_consistent(): void
    {
        $result = $this->service->simulate(1250.75, 345.20, 8.35, 240);
        $lastPoint = $result['series'][239];

        $this->assertEqualsWithDelta($result['final_balance'], $lastPoint['balance'], 0.01);
        $this->assertEqualsWithDelta($result['total_invested'], $lastPoint['invested'], 0.01);
    }

    public function test_first_end_of_month_contribution_receives_no_interest_that_month(): void
    {
        $result = $this->service->simulate(0, 100, 12, 1);

        $this->assertSame(100.0, $result['series'][0]['balance']);
        $this->assertEqualsWithDelta(100, $result['final_balance'], 0.000001);
    }

    public function test_valid_limits_never_produce_nan_or_infinite_values(): void
    {
        $result = $this->service->simulate(1_000_000_000, 1_000_000_000, 100, 1200);

        foreach (['monthly_rate', 'total_invested', 'final_balance', 'estimated_earnings'] as $key) {
            $this->assertFalse(is_nan($result[$key]));
            $this->assertFalse(is_infinite($result[$key]));
        }
    }
}
