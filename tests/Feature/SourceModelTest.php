<?php

namespace Tests\Feature;

use App\Models\Source;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_url_hash_is_generated_and_updated_for_long_urls(): void
    {
        $url = 'https://example.com/'.str_repeat('a', 2000);

        $source = Source::create([
            'institution' => 'Instituição',
            'title' => 'Fonte longa',
            'url' => $url,
            'accessed_at' => '2026-08-25',
        ]);

        $this->assertSame(hash('sha256', $url), $source->url_hash);
        $this->assertSame($url, $source->fresh()->url);

        $updatedUrl = 'https://example.com/atualizada';
        $source->update(['url' => $updatedUrl]);

        $this->assertSame(hash('sha256', $updatedUrl), $source->fresh()->url_hash);
    }

    public function test_database_rejects_duplicate_source_urls_by_hash(): void
    {
        $attributes = [
            'institution' => 'Instituição',
            'title' => 'Fonte',
            'url' => 'https://example.com/fonte',
            'accessed_at' => '2026-08-25',
        ];

        Source::create($attributes);

        $this->expectException(QueryException::class);

        Source::create($attributes);
    }
}
