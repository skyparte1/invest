<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Source;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_educational_seeders_create_the_essential_sourced_content_set(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('categories', ['slug' => 'fundamentos-financeiros']);
        $this->assertDatabaseHas('categories', ['slug' => 'planejamento-financeiro']);
        $this->assertDatabaseHas('categories', ['slug' => 'investimentos']);
        $this->assertDatabaseHas('contents', ['slug' => 'juros-compostos', 'is_published' => true]);
        $this->assertDatabaseHas('contents', ['slug' => 'liquidez', 'is_published' => true]);
        $this->assertGreaterThanOrEqual(3, Category::query()->count());
        $this->assertGreaterThanOrEqual(2, Source::query()->count());
        $this->assertGreaterThanOrEqual(6, Content::query()->published()->count());
        $this->assertSame(0, Content::query()->published()->doesntHave('sources')->count());
    }
}
