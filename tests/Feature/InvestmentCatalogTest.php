<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_catalog_only_lists_published_investments(): void
    {
        $category = $this->category('Renda fixa', 'renda-fixa');
        $published = $this->investment($category, 'CDB', 'cdb');
        $draft = $this->investment($category, 'Rascunho', 'rascunho', 'variable', false);

        $this->get(route('investments.index'))->assertOk()->assertSee($published->name)->assertDontSee($draft->name);
    }

    public function test_category_risk_and_combined_filters_work(): void
    {
        $fixed = $this->category('Renda fixa', 'renda-fixa', 1);
        $variable = $this->category('Renda variável', 'renda-variavel', 2);
        $selic = $this->investment($fixed, 'Tesouro Selic', 'tesouro-selic', 'low');
        $cdb = $this->investment($fixed, 'CDB', 'cdb', 'variable');
        $stock = $this->investment($variable, 'Ações', 'acoes', 'high');

        $this->get(route('investments.index', ['categoria' => 'renda-fixa']))->assertSee($selic->name)->assertSee($cdb->name)->assertDontSee($stock->name);
        $this->get(route('investments.index', ['risco' => 'high']))->assertSee($stock->name)->assertDontSee($selic->name);
        $this->get(route('investments.index', ['categoria' => 'renda-fixa', 'risco' => 'low']))->assertSee($selic->name)->assertDontSee($cdb->name)->assertDontSee($stock->name);
    }

    public function test_invalid_filters_are_ignored_without_server_error(): void
    {
        $investment = $this->investment($this->category('Fundos', 'fundos'), 'Fundos', 'fundos');

        $this->get(route('investments.index', ['categoria' => 'invalida', 'risco' => 'impossivel']))
            ->assertOk()->assertSee($investment->name);
    }

    public function test_published_detail_displays_risk_descriptions_and_source(): void
    {
        $investment = $this->investment($this->category('Fundos', 'fundos'), 'Fundos', 'fundos', 'variable');
        $source = Source::query()->create([
            'institution' => 'CVM', 'title' => 'Fonte oficial',
            'url' => 'https://www.gov.br/investidor/fonte', 'accessed_at' => '2026-08-24',
        ]);
        $investment->sources()->attach($source, ['sort_order' => 1]);

        $this->get(route('investments.show', $investment->slug))
            ->assertOk()->assertSee($investment->name)->assertSee($investment->category->name)
            ->assertSee('Risco: Variável')->assertSee($investment->risk_description)
            ->assertSee($investment->liquidity_description)->assertSee($source->title);
    }

    public function test_draft_and_unknown_investments_return_not_found(): void
    {
        $draft = $this->investment($this->category('Fundos', 'fundos'), 'Rascunho', 'rascunho', 'variable', false);

        $this->get(route('investments.show', $draft->slug))->assertNotFound();
        $this->get(route('investments.show', 'nao-existe'))->assertNotFound();
    }

    public function test_investment_markdown_is_safe(): void
    {
        $investment = $this->investment($this->category('Fundos', 'fundos'), 'Seguro', 'seguro');
        $investment->update([
            'description' => "## Título\n\nTexto **forte**.\n\n- item\n\n<script>alert('perigo')</script>\n\n[ruim](javascript:alert(1))",
        ]);

        $this->get(route('investments.show', $investment->slug))
            ->assertOk()->assertSee('<h2>Título</h2>', false)->assertSee('<strong>forte</strong>', false)
            ->assertSee('<li>item</li>', false)->assertDontSee("alert('perigo')", false)
            ->assertDontSee('href="javascript:', false);
    }

    private function category(string $name, string $slug, int $sortOrder = 1): InvestmentCategory
    {
        return InvestmentCategory::query()->create(['name' => $name, 'slug' => $slug, 'sort_order' => $sortOrder]);
    }

    private function investment(InvestmentCategory $category, string $name, string $slug, string $risk = 'variable', bool $published = true): Investment
    {
        return Investment::query()->create([
            'investment_category_id' => $category->id, 'name' => $name, 'slug' => $slug,
            'short_description' => 'Descrição curta.', 'description' => "## O que é?\n\nDescrição completa.",
            'risk_level' => $risk, 'risk_description' => 'Descrição do risco.',
            'liquidity_description' => 'Descrição da liquidez.', 'profitability_description' => 'Descrição da rentabilidade.',
            'sort_order' => 1, 'is_published' => $published,
        ]);
    }
}
