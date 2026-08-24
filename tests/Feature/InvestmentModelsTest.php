<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_investment_relationships_and_cast_work(): void
    {
        $category = $this->category();
        $investment = $this->investment($category);
        $source = Source::query()->create([
            'institution' => 'Instituição oficial', 'title' => 'Fonte',
            'url' => 'https://www.bcb.gov.br/fonte-investimento', 'accessed_at' => '2026-08-24',
        ]);
        $investment->sources()->attach($source, ['sort_order' => 1]);

        $this->assertTrue($category->investments->contains($investment));
        $this->assertTrue($investment->category->is($category));
        $this->assertTrue($investment->sources->contains($source));
        $this->assertTrue($source->investments->contains($investment));
        $this->assertTrue($investment->is_published);
    }

    public function test_published_scope_excludes_drafts(): void
    {
        $category = $this->category();
        $this->investment($category, 'publicado', true);
        $this->investment($category, 'rascunho', false);

        $this->assertSame(['publicado'], Investment::query()->published()->pluck('slug')->all());
    }

    private function category(): InvestmentCategory
    {
        return InvestmentCategory::query()->create(['name' => 'Renda fixa', 'slug' => 'renda-fixa', 'sort_order' => 1]);
    }

    private function investment(InvestmentCategory $category, string $slug = 'publicado', bool $published = true): Investment
    {
        return Investment::query()->create([
            'investment_category_id' => $category->id, 'name' => ucfirst($slug), 'slug' => $slug,
            'short_description' => 'Descrição curta.', 'description' => '## O que é?\n\nDescrição.',
            'risk_level' => 'variable', 'risk_description' => 'Risco varia.',
            'liquidity_description' => 'Liquidez varia.', 'profitability_description' => 'Retorno varia.',
            'sort_order' => 1, 'is_published' => $published,
        ]);
    }
}
