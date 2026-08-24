<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_dashboard_with_first_name_greeting(): void
    {
        $user = User::factory()->create(['name' => 'Ana Souza']);

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertSee('Olá, Ana.')
            ->assertSee('Continue sua jornada financeira.')
            ->assertDontSee('Em breve')
            ->assertSee('Planejar objetivos')
            ->assertSee('Dashboard')
            ->assertSee('Sair');

        $this->assertSame(4, substr_count($response->getContent(), 'Disponível'));
    }

    public function test_dashboard_summarizes_active_goals(): void
    {
        $user = User::factory()->create();
        $user->financialGoals()->create([
            'name' => 'Reserva',
            'target_amount' => 1000,
            'current_amount' => 100,
            'target_date' => now()->addMonth(),
        ]);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Você possui')
            ->assertSee('1 meta ativa')
            ->assertSee('Acompanhar planejamento');
    }
}
