<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimulationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_simulator_is_available_to_authenticated_users(): void
    {
        $this->get(route('simulator.index'))
            ->assertOk()
            ->assertSee('Simule um cenário hipotético')
            ->assertSee('Simular cenário')
            ->assertDontSee('id="simulation-chart"', false);

    }

    public function test_valid_post_displays_zero_rate_result_disclaimers_and_chart_data(): void
    {
        $response = $this->post(route('simulator.calculate'), $this->validPayload([
            'initial_amount' => '1.000,00',
            'periodic_contribution' => '100,00',
            'annual_rate' => '0',
            'term' => 12,
            'term_unit' => 'months',
        ]));

        $response->assertOk()
            ->assertSee('R$ 2.200,00')
            ->assertSee('R$ 0,00')
            ->assertSee('id="simulation-chart"', false)
            ->assertSee('id="simulation-chart-data"', false)
            ->assertSee('Os resultados são brutos e não consideram impostos');

        $this->assertSame(2, substr_count($response->getContent(), 'Simulação educacional: os valores apresentados são estimativas'));
    }

    public function test_invalid_numeric_inputs_are_rejected_and_preserved(): void
    {
        $this->from(route('simulator.index'))
            ->post(route('simulator.calculate'), $this->validPayload([
                'initial_amount' => '-1',
                'periodic_contribution' => 'não é número',
                'annual_rate' => '101',
                'term' => 0,
            ]))
            ->assertRedirect(route('simulator.index'))
            ->assertSessionHasErrors(['initial_amount', 'periodic_contribution', 'annual_rate', 'term'])
            ->assertSessionHasInput('annual_rate', '101');
    }

    public function test_excessive_terms_are_rejected_for_months_and_years(): void
    {
        $this->post(route('simulator.calculate'), $this->validPayload(['term' => 1201, 'term_unit' => 'months']))
            ->assertSessionHasErrors('term');

        $this->post(route('simulator.calculate'), $this->validPayload(['term' => 101, 'term_unit' => 'years']))
            ->assertSessionHasErrors('term');
    }

    public function test_only_enumerated_units_and_frequency_are_accepted(): void
    {
        $this->post(route('simulator.calculate'), $this->validPayload([
            'term_unit' => 'days',
            'contribution_frequency' => 'daily',
        ]))->assertSessionHasErrors(['term_unit', 'contribution_frequency']);
    }

    public function test_published_investment_can_contextualize_without_changing_rate(): void
    {
        $investment = $this->investment('CDB', 'cdb');

        $this->post(route('simulator.calculate'), $this->validPayload([
            'annual_rate' => '7,25',
            'investment_slug' => $investment->slug,
        ]))->assertOk()
            ->assertSee('Cenário hipotético contextualizado como CDB')
            ->assertSee('7,25% a.a.')
            ->assertSee(route('investments.show', $investment->slug));
    }

    public function test_draft_or_unknown_investment_cannot_contextualize_simulation(): void
    {
        $draft = $this->investment('Rascunho', 'rascunho', false);

        $this->post(route('simulator.calculate'), $this->validPayload(['investment_slug' => $draft->slug]))
            ->assertSessionHasErrors('investment_slug');

        $this->post(route('simulator.calculate'), $this->validPayload(['investment_slug' => 'inexistente']))
            ->assertSessionHasErrors('investment_slug');
    }

    public function test_get_preselection_accepts_published_slug_and_ignores_invalid_slug(): void
    {
        $investment = $this->investment('Tesouro Selic', 'tesouro-selic');

        $this->get(route('simulator.index', ['investimento' => $investment->slug]))
            ->assertOk()
            ->assertSee('value="tesouro-selic" selected', false)
            ->assertSee('value="10"', false);

        $this->get(route('simulator.index', ['investimento' => 'inexistente']))
            ->assertOk()
            ->assertDontSee('value="inexistente"', false);

        $this->get(route('simulator.index', ['investimento' => ['cdb']]))->assertOk();
    }

    public function test_simulator_links_are_integrated_with_home_dashboard_and_catalog(): void
    {
        $investment = $this->investment('CDB', 'cdb');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('simulator.index'));

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Simular cenário')
            ->assertSee(route('simulator.index'));

        $this->get(route('investments.show', $investment->slug))
            ->assertOk()
            ->assertSee('Simular cenário hipotético')
            ->assertSee(route('simulator.index', ['investimento' => $investment->slug]));
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'initial_amount' => '1000',
            'periodic_contribution' => '200',
            'annual_rate' => '10',
            'term' => 5,
            'term_unit' => 'years',
            'contribution_frequency' => 'monthly',
            'investment_slug' => '',
        ], $overrides);
    }

    private function investment(string $name, string $slug, bool $published = true): Investment
    {
        $category = InvestmentCategory::query()->create([
            'name' => 'Renda fixa',
            'slug' => 'renda-fixa-'.$slug,
            'sort_order' => 1,
        ]);

        return Investment::query()->create([
            'investment_category_id' => $category->id,
            'name' => $name,
            'slug' => $slug,
            'short_description' => 'Descrição curta.',
            'description' => 'Descrição completa.',
            'risk_level' => 'variable',
            'risk_description' => 'Risco.',
            'liquidity_description' => 'Liquidez.',
            'profitability_description' => 'Rentabilidade.',
            'sort_order' => 1,
            'is_published' => $published,
        ]);
    }
}
