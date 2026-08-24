<?php

namespace Tests\Feature;

use App\Models\FinancialGoal;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialPlanningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CarbonImmutable::setTestNow('2026-08-24 12:00:00');
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('planning.index'))->assertRedirect(route('login'));
        $this->post(route('planning.store'), $this->validPayload())->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_empty_state_and_disclaimer(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('planning.index'))
            ->assertOk()
            ->assertSee('Você ainda não possui metas cadastradas.')
            ->assertSee('O planejamento apresentado possui finalidade educacional e organizacional.')
            ->assertSee('Criar meta');
    }

    public function test_user_creates_goal_with_normalized_brazilian_values_and_name(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user)
            ->post(route('planning.store'), $this->validPayload([
                'user_id' => $otherUser->id,
                'name' => '  Reserva   para viagem  ',
                'target_amount' => '12.000,00',
                'current_amount' => '6.500,25',
            ]))
            ->assertRedirect(route('planning.index'))
            ->assertSessionHas('status', 'Meta criada com sucesso.');

        $this->assertDatabaseHas('financial_goals', [
            'user_id' => $user->id,
            'name' => 'Reserva para viagem',
            'target_amount' => '12000.00',
            'current_amount' => '6500.25',
        ]);
        $this->assertDatabaseMissing('financial_goals', ['user_id' => $otherUser->id]);
    }

    public function test_listing_only_contains_authenticated_users_goals(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->goal($user, ['name' => 'Meta visível']);
        $this->goal($other, ['name' => 'Meta privada de outro usuário']);

        $this->actingAs($user)->get(route('planning.index'))
            ->assertOk()
            ->assertSee('Meta visível')
            ->assertDontSee('Meta privada de outro usuário');
    }

    public function test_user_edits_goal_and_updates_current_amount(): void
    {
        $user = User::factory()->create();
        $goal = $this->goal($user);

        $this->actingAs($user)->get(route('planning.edit', $goal))
            ->assertOk()->assertSee($goal->name)->assertSee('Salvar alterações');

        $this->actingAs($user)
            ->patch(route('planning.update', $goal), $this->validPayload([
                'name' => 'Meta atualizada',
                'current_amount' => '8.000,00',
                'target_date' => '2026-07-01',
            ]))
            ->assertRedirect(route('planning.index'));

        $this->assertDatabaseHas('financial_goals', [
            'id' => $goal->id,
            'name' => 'Meta atualizada',
            'current_amount' => '8000.00',
            'target_date' => '2026-07-01 00:00:00',
        ]);
    }

    public function test_user_deletes_own_goal(): void
    {
        $user = User::factory()->create();
        $goal = $this->goal($user);

        $this->actingAs($user)->delete(route('planning.destroy', $goal))
            ->assertRedirect(route('planning.index'))
            ->assertSessionHas('status', 'Meta excluída com sucesso.');

        $this->assertDatabaseMissing('financial_goals', ['id' => $goal->id]);
    }

    public function test_validation_rejects_invalid_values_and_preserves_input(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('planning.index'))
            ->post(route('planning.store'), [
                'name' => '',
                'target_amount' => '0',
                'current_amount' => '-1',
                'target_date' => '2026-08-23',
                'description' => str_repeat('a', 1001),
            ])
            ->assertRedirect(route('planning.index'))
            ->assertSessionHasErrors(['name', 'target_amount', 'current_amount', 'target_date', 'description'])
            ->assertSessionHasInput('current_amount', '-1');
    }

    public function test_validation_rejects_invalid_date_and_values_above_limit(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('planning.store'), $this->validPayload([
                'target_amount' => '1000000000,01',
                'current_amount' => '1000000000,01',
                'target_date' => 'data-invalida',
            ]))
            ->assertSessionHasErrors(['target_amount', 'current_amount', 'target_date']);
    }

    public function test_goal_card_displays_progress_remaining_reference_and_completed_status(): void
    {
        $user = User::factory()->create();
        $this->goal($user, [
            'name' => 'Curso',
            'target_amount' => '12000',
            'current_amount' => '6000',
            'target_date' => '2027-01-15',
        ]);
        $this->goal($user, [
            'name' => 'Computador',
            'target_amount' => '5000',
            'current_amount' => '5500',
            'target_date' => '2026-09-30',
        ]);

        $this->actingAs($user)->get(route('planning.index'))
            ->assertOk()
            ->assertSee('50,00%')
            ->assertSee('R$ 6.000,00')
            ->assertSee('R$ 1.000,00')
            ->assertSee('Meta superada')
            ->assertSee('role="progressbar"', false)
            ->assertSee('aria-valuemax="100"', false);
    }

    public function test_other_user_cannot_edit_update_or_delete_goal(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $goal = $this->goal($owner);

        $this->actingAs($intruder)->get(route('planning.edit', $goal))->assertForbidden();
        $this->actingAs($intruder)->patch(route('planning.update', $goal), $this->validPayload())->assertForbidden();
        $this->actingAs($intruder)->delete(route('planning.destroy', $goal))->assertForbidden();

        $this->assertDatabaseHas('financial_goals', ['id' => $goal->id, 'user_id' => $owner->id]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Reserva para viagem',
            'target_amount' => '12000',
            'current_amount' => '6000',
            'target_date' => '2027-01-15',
            'description' => 'Objetivo pessoal.',
        ], $overrides);
    }

    private function goal(User $user, array $overrides = []): FinancialGoal
    {
        return $user->financialGoals()->create(array_merge([
            'name' => 'Meta de teste',
            'target_amount' => '12000',
            'current_amount' => '2000',
            'target_date' => '2027-01-15',
            'description' => null,
        ], $overrides));
    }
}
