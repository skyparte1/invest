<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\Simulation;
use App\Models\User;
use App\Services\ReturnReferences;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

    public function test_saving_a_calculated_scenario_is_explicit_private_and_retry_safe(): void
    {
        $investment = $this->investment('CDB de exemplo', 'cdb');
        $payload = $this->validPayload(['investment_slug' => $investment->slug, 'annual_rate' => 0]);
        $response = $this->post(route('simulator.calculate'), $payload);
        $response->assertOk()->assertSee('Salvar esta simulação')->assertSee($investment->liquidity_description);
        $this->assertDatabaseCount('simulations', 0);
        preg_match('/name="submission_id" value="([^"]+)"/', $response->getContent(), $matches);
        $payload['submission_id'] = $matches[1];
        $this->post(route('simulator.save'), $payload)->assertRedirect(route('simulator.history'));
        $this->post(route('simulator.save'), $payload)->assertRedirect(route('simulator.history'));
        $this->assertDatabaseCount('simulations', 1);
        $this->get(route('simulator.history'))->assertSee($investment->name)->assertSee('R$ 13.000,00');
        $this->actingAs(User::factory()->create())->get(route('simulator.history'))->assertDontSee($investment->name);
    }

    public function test_reference_catalog_and_optional_application(): void
    {
        $this->travelTo(now()->setDate(2026, 11, 15));
        foreach ([
            'poupanca' => ['Poupança'], 'tesouro-selic' => ['Meta Selic'], 'cdb' => ['100% do CDI'],
            'fundos-de-investimento' => ['100% do CDI', 'Ibovespa'], 'fundos-imobiliarios' => ['IFIX'], 'acoes' => ['Ibovespa'],
        ] as $slug => $names) {
            $refs = ReturnReferences::forModality($slug);
            $this->assertSame($names, array_column($refs, 'name'));
        }
        $this->investment('CDB', 'cdb');
        $refs = ReturnReferences::forModality('cdb');
        $id = array_key_first($refs);
        $this->get(route('simulator.index', ['investimento' => 'cdb']))->assertSee('value="10"', false)
            ->assertSee('aria-label="Usar esta referência: 100% do CDI"', false)->assertSee('role="status"', false);
        $payload = $this->validPayload(['investment_slug' => 'cdb', 'reference_id' => $id, 'annual_rate' => $refs[$id]['value'], 'submission_id' => (string) Str::uuid()]);
        $this->post(route('simulator.save'), $payload)->assertSessionHasNoErrors();
        $this->assertSame($refs[$id]['date'], Simulation::sole()->rate_reference['date']);
        $this->post(route('simulator.calculate'), array_merge($payload, ['annual_rate' => 3]))->assertSessionHasErrors('annual_rate');
        $this->travel(2)->months();
        $this->get(route('simulator.index'))->assertSee('Referência desatualizada')->assertDontSee('Usar esta referência');
        $this->post(route('simulator.calculate'), $payload)->assertSessionHasErrors('annual_rate');
        $this->get(route('simulator.history'))->assertSee('100% do CDI');
        $this->travelBack();
        $this->travelTo(now()->setDate(2026, 11, 15));
        config(['return_references.indicators.di.value' => null]);
        $this->get(route('simulator.index'))->assertSee('Sem taxa numérica utilizável')->assertDontSee('Usar esta referência');
        config(['return_references.indicators.di.url' => 'javascript:alert(1)']);
        $this->get(route('simulator.index'))->assertSee('ainda não possui referência validada')->assertDontSee('javascript:alert');
    }

    public function test_manual_loss_is_saved_and_invalid_rates_rejected(): void
    {
        $payload = $this->validPayload(['periodic_contribution' => 0, 'annual_rate' => '-25,5', 'term' => 1]);
        $this->post(route('simulator.calculate'), $payload)->assertOk()->assertSee('Prejuízo estimado')->assertSee('-R$ 255,00');
        $this->post(route('simulator.save'), $payload + ['submission_id' => (string) Str::uuid()])->assertSessionHasNoErrors();
        $this->get(route('simulator.history'))->assertSee('-R$ 255,00')->assertDontSee('Referência aplicada');
        foreach ([-101, -100, 101] as $rate) {
            $this->post(route('simulator.calculate'), array_merge($payload, ['annual_rate' => $rate]))->assertSessionHasErrors('annual_rate');
        }
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
