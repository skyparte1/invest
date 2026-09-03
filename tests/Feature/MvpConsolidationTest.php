<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MvpConsolidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_journey_links_to_all_mvp_modules(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('learn.index'))
            ->assertSee(route('investments.index'))
            ->assertSee(route('simulator.index'))
            ->assertSee(route('planning.index'))
            ->assertSee('Criar conta gratuitamente');
    }

    public function test_authenticated_landing_cta_uses_dashboard_wording(): void
    {
        $this->actingAs(User::factory()->create())->get(route('home'))
            ->assertOk()
            ->assertSee('Ir para o dashboard')
            ->assertDontSee('Criar conta gratuitamente');
    }

    public function test_navbar_adapts_to_guest_and_authenticated_user(): void
    {
        $guest = $this->get(route('login'));
        $guest->assertOk()->assertSee('Entrar')->assertSee('Criar conta')->assertDontSee('Perfil');
        $guest->assertDontSee('<a class="nav-link" href="'.route('learn.index').'"', false)
            ->assertDontSee('<a class="nav-link" href="'.route('investments.index').'"', false)
            ->assertDontSee('<a class="nav-link" href="'.route('simulator.index').'"', false)
            ->assertDontSee('<a class="nav-link" href="'.route('planning.index').'"', false);

        $profile = $this->actingAs(User::factory()->create())->get(route('profile.edit'));
        $profile->assertOk()->assertSee('Dashboard')->assertSee('Perfil')->assertSee('Sair');
        $profile->assertSee('href="'.route('profile.edit').'"', false)->assertSee('aria-current="page"', false);
    }

    public function test_footer_has_no_placeholder_links_and_includes_educational_note(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('href="#"', false)
            ->assertSee('Não oferece recomendação individualizada de investimento.');
    }

    public function test_custom_not_found_page_is_rendered(): void
    {
        $this->get('/pagina-que-nao-existe')
            ->assertNotFound()
            ->assertSee('Página não encontrada.')
            ->assertSee('Voltar ao início');
    }

    public function test_custom_forbidden_page_is_rendered_for_goal_idor_attempt(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $goal = $owner->financialGoals()->create([
            'name' => 'Meta privada',
            'target_amount' => 1000,
            'current_amount' => 0,
            'target_date' => now()->addMonth(),
        ]);

        $this->actingAs($intruder)->get(route('planning.edit', $goal))
            ->assertForbidden()
            ->assertSee('Você não tem acesso a este conteúdo.')
            ->assertSee('Ir para o dashboard');
    }
}
