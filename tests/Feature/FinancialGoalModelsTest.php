<?php

namespace Tests\Feature;

use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialGoalModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_and_financial_goal_relationships_work(): void
    {
        $user = User::factory()->create();
        $goal = $user->financialGoals()->create([
            'name' => 'Curso',
            'target_amount' => '12000.00',
            'current_amount' => '2500.50',
            'target_date' => '2027-08-24',
        ]);

        $this->assertTrue($user->financialGoals->contains($goal));
        $this->assertTrue($goal->user->is($user));

        $user->delete();

        $this->assertDatabaseMissing('financial_goals', ['id' => $goal->id]);
    }

    public function test_financial_values_and_target_date_have_expected_casts(): void
    {
        $goal = User::factory()->create()->financialGoals()->create([
            'name' => 'Computador',
            'target_amount' => '8000',
            'current_amount' => '1200.5',
            'target_date' => '2027-01-15',
        ]);

        $this->assertSame('8000.00', $goal->target_amount);
        $this->assertSame('1200.50', $goal->current_amount);
        $this->assertSame('15/01/2027', $goal->target_date->format('d/m/Y'));
        $this->assertInstanceOf(FinancialGoal::class, $goal);
    }
}
