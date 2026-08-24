<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_learning_model_relationships_and_casts_work(): void
    {
        $category = Category::query()->create([
            'name' => 'Fundamentos', 'slug' => 'fundamentos', 'sort_order' => 1,
        ]);
        $content = Content::query()->create([
            'category_id' => $category->id,
            'title' => 'Conceito',
            'slug' => 'conceito',
            'summary' => 'Resumo',
            'body' => 'Corpo',
            'difficulty' => 'beginner',
            'sort_order' => 1,
            'is_published' => 1,
        ]);
        $source = Source::query()->create([
            'institution' => 'Instituição oficial',
            'title' => 'Referência',
            'url' => 'https://www.bcb.gov.br/referencia',
            'publication_date' => '2022-05-31',
            'accessed_at' => '2026-08-24',
        ]);
        $content->sources()->attach($source, ['sort_order' => 1]);

        $this->assertTrue($category->contents->contains($content));
        $this->assertTrue($content->category->is($category));
        $this->assertTrue($content->sources->contains($source));
        $this->assertTrue($source->contents->contains($content));
        $this->assertTrue($content->is_published);
        $this->assertSame('2022-05-31', $source->publication_date->toDateString());
        $this->assertSame('2026-08-24', $source->accessed_at->toDateString());
    }

    public function test_published_scope_excludes_drafts(): void
    {
        $category = Category::query()->create([
            'name' => 'Fundamentos', 'slug' => 'fundamentos', 'sort_order' => 1,
        ]);

        foreach ([['publicado', true], ['rascunho', false]] as [$slug, $published]) {
            Content::query()->create([
                'category_id' => $category->id,
                'title' => ucfirst($slug),
                'slug' => $slug,
                'summary' => 'Resumo',
                'body' => 'Corpo',
                'difficulty' => 'beginner',
                'sort_order' => 1,
                'is_published' => $published,
            ]);
        }

        $this->assertSame(['publicado'], Content::query()->published()->pluck('slug')->all());
    }
}
