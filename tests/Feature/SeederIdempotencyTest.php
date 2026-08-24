<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeederIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_seeders_are_idempotent(): void
    {
        $this->seed(DatabaseSeeder::class);
        $firstRun = $this->publicDataCounts();

        $this->seed(DatabaseSeeder::class);

        $this->assertSame($firstRun, $this->publicDataCounts());
    }

    private function publicDataCounts(): array
    {
        return collect([
            'categories',
            'contents',
            'sources',
            'investment_categories',
            'investments',
            'content_source',
            'investment_source',
        ])->mapWithKeys(fn (string $table) => [$table => DB::table($table)->count()])->all();
    }
}
