<?php

namespace Tests\Unit;

use App\Models\FinancialGoal;
use App\Services\FinancialGoalProgressService;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class FinancialGoalProgressServiceTest extends TestCase
{
    private FinancialGoalProgressService $service;

    private CarbonImmutable $today;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new FinancialGoalProgressService;
        $this->today = CarbonImmutable::parse('2026-08-24');
    }

    public function test_normal_progress_and_remaining_amount_are_calculated(): void
    {
        $metrics = $this->calculate(12000, 3000, '2026-12-31');

        $this->assertSame(25.0, $metrics['progress_percentage']);
        $this->assertSame(9000.0, $metrics['remaining_amount']);
        $this->assertFalse($metrics['completed']);
        $this->assertFalse($metrics['expired']);
    }

    public function test_zero_accumulated_has_zero_progress(): void
    {
        $metrics = $this->calculate(5000, 0, '2026-09-30');

        $this->assertSame(0.0, $metrics['progress_percentage']);
        $this->assertSame(5000.0, $metrics['remaining_amount']);
    }

    public function test_reaching_target_completes_goal(): void
    {
        $metrics = $this->calculate(6000, 6000, '2026-09-30');

        $this->assertTrue($metrics['completed']);
        $this->assertFalse($metrics['surpassed']);
        $this->assertSame(0.0, $metrics['remaining_amount']);
        $this->assertSame(0.0, $metrics['monthly_reference']);
    }

    public function test_surpassed_goal_caps_bar_and_never_has_negative_remaining_amount(): void
    {
        $metrics = $this->calculate(4000, 5600, '2026-09-30');

        $this->assertTrue($metrics['completed']);
        $this->assertTrue($metrics['surpassed']);
        $this->assertSame(140.0, $metrics['progress_percentage']);
        $this->assertSame(100.0, $metrics['progress_bar_percentage']);
        $this->assertSame(0.0, $metrics['remaining_amount']);
    }

    public function test_calendar_month_reference_includes_current_and_target_months(): void
    {
        $sameMonth = $this->calculate(1000, 0, '2026-08-31');
        $nextMonth = $this->calculate(1000, 0, '2026-09-15');

        $this->assertSame(1, $sameMonth['months_reference']);
        $this->assertSame(2, $nextMonth['months_reference']);
    }

    public function test_monthly_reference_divides_remaining_value_by_calendar_months(): void
    {
        $metrics = $this->calculate(12000, 6000, '2027-01-15');

        $this->assertSame(6, $metrics['months_reference']);
        $this->assertSame(1000.0, $metrics['monthly_reference']);
    }

    public function test_expired_incomplete_goal_has_no_future_monthly_reference(): void
    {
        $metrics = $this->calculate(12000, 6000, '2026-08-23');

        $this->assertTrue($metrics['expired']);
        $this->assertFalse($metrics['completed']);
        $this->assertSame(0, $metrics['months_reference']);
        $this->assertNull($metrics['monthly_reference']);
        $this->assertSame(0, $metrics['days_remaining']);
    }

    public function test_completed_goal_with_past_date_is_not_marked_expired(): void
    {
        $metrics = $this->calculate(12000, 12000, '2026-07-01');

        $this->assertTrue($metrics['completed']);
        $this->assertFalse($metrics['expired']);
        $this->assertSame(0.0, $metrics['monthly_reference']);
    }

    private function calculate(float $target, float $current, string $date): array
    {
        $goal = new FinancialGoal([
            'name' => 'Meta de teste',
            'target_amount' => $target,
            'current_amount' => $current,
            'target_date' => $date,
        ]);

        return $this->service->calculate($goal, $this->today);
    }
}
