<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_routes_require_authentication(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->patch(route('profile.update'))->assertRedirect(route('login'));
        $this->put(route('profile.password.update'))->assertRedirect(route('login'));
        $this->delete(route('profile.destroy'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Dados pessoais')
            ->assertSee('Alterar senha')
            ->assertSee('Excluir conta')
            ->assertSee($user->email);
    }

    public function test_user_updates_normalized_profile_without_changing_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nome Antigo',
            'email' => 'antigo@example.com',
            'password' => 'senha-antiga',
        ]);
        $passwordHash = $user->password;

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '  Ana   Maria  ',
            'email' => '  ANA.MARIA@EXAMPLE.COM ',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHas('status', 'Perfil atualizado com sucesso.');

        $user->refresh();
        $this->assertSame('Ana Maria', $user->name);
        $this->assertSame('ana.maria@example.com', $user->email);
        $this->assertSame($passwordHash, $user->password);
    }

    public function test_profile_email_must_be_unique_except_for_current_user(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => 'taken@example.com',
        ])->assertSessionHasErrorsIn('updateProfile', ['email']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => ' USER@EXAMPLE.COM ',
        ])->assertRedirect(route('profile.edit'));
    }

    public function test_password_update_requires_correct_current_password_and_confirmation(): void
    {
        $user = User::factory()->create(['password' => 'senha-atual']);

        $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'senha-errada',
            'password' => 'senha-nova',
            'password_confirmation' => 'senha-nova',
        ])->assertSessionHasErrorsIn('updatePassword', ['current_password']);

        $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'senha-atual',
            'password' => 'senha-nova',
            'password_confirmation' => 'outra-senha',
        ])->assertSessionHasErrorsIn('updatePassword', ['password']);

        $this->assertTrue(Hash::check('senha-atual', $user->fresh()->password));
    }

    public function test_user_updates_password_and_remains_authenticated(): void
    {
        $user = User::factory()->create(['password' => 'senha-atual']);

        $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'senha-atual',
            'password' => 'senha-nova',
            'password_confirmation' => 'senha-nova',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHas('status', 'Senha atualizada com sucesso.');

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('senha-nova', $user->fresh()->password));
    }

    public function test_account_deletion_requires_correct_password(): void
    {
        $user = User::factory()->create(['password' => 'senha-atual']);

        $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'senha-errada',
        ])->assertSessionHasErrorsIn('deleteAccount', ['password']);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_account_deletion_removes_goals_but_preserves_public_content(): void
    {
        $user = User::factory()->create(['password' => 'senha-atual']);
        $goal = $user->financialGoals()->create([
            'name' => 'Meta pessoal',
            'target_amount' => 1000,
            'current_amount' => 100,
            'target_date' => now()->addMonth(),
        ]);
        $category = Category::create([
            'name' => 'Fundamentos',
            'slug' => 'fundamentos',
            'description' => 'Conteúdo público.',
            'sort_order' => 1,
        ]);
        $content = Content::create([
            'category_id' => $category->id,
            'title' => 'Conteúdo público',
            'slug' => 'conteudo-publico',
            'summary' => 'Resumo.',
            'body' => 'Texto.',
            'difficulty' => 'beginner',
            'estimated_minutes' => 5,
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'senha-atual',
        ])->assertRedirect(route('home'))
            ->assertSessionHas('status', 'Conta excluída com sucesso.');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('financial_goals', ['id' => $goal->id]);
        $this->assertDatabaseHas('contents', ['id' => $content->id]);
    }
}
