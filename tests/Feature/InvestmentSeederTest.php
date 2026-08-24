<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_a_demonstrable_sourced_catalog(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertGreaterThanOrEqual(3, InvestmentCategory::query()->count());
        $this->assertGreaterThanOrEqual(5, Investment::query()->published()->count());
        $this->assertSame(0, Investment::query()->published()->doesntHave('sources')->count());
        $this->assertDatabaseHas('investments', ['slug' => 'tesouro-selic', 'risk_level' => 'low', 'is_published' => true]);
        $this->assertDatabaseHas('investments', ['slug' => 'fundos-de-investimento', 'risk_level' => 'variable', 'is_published' => true]);
    }
}
