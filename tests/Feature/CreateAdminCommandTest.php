<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_an_admin_with_normalized_data_and_hashed_password(): void
    {
        $this->artisan('app:create-admin')
            ->expectsQuestion('Nome', '  Ana   Souza  ')
            ->expectsQuestion('E-mail', '  ANA@EXAMPLE.COM ')
            ->expectsQuestion('Senha', 'password123')
            ->expectsQuestion('Confirme a senha', 'password123')
            ->expectsOutput('Conta administrativa criada com sucesso.')
            ->assertSuccessful();

        $admin = User::sole();

        $this->assertSame('Ana Souza', $admin->name);
        $this->assertSame('ana@example.com', $admin->email);
        $this->assertTrue($admin->is_admin);
        $this->assertNotSame('password123', $admin->password);
        $this->assertTrue(Hash::check('password123', $admin->password));
    }

    public function test_command_promotes_existing_user_without_creating_a_duplicate(): void
    {
        $user = User::factory()->create([
            'email' => 'ana@example.com',
            'is_admin' => false,
        ]);

        $this->artisan('app:create-admin')
            ->expectsQuestion('Nome', 'Ana Administradora')
            ->expectsQuestion('E-mail', 'ANA@EXAMPLE.COM')
            ->expectsQuestion('Senha', 'new-password')
            ->expectsQuestion('Confirme a senha', 'new-password')
            ->expectsOutput('Conta existente promovida a administradora com sucesso.')
            ->assertSuccessful();

        $this->assertDatabaseCount('users', 1);
        $user->refresh();
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_command_rejects_invalid_email_and_password(): void
    {
        $this->artisan('app:create-admin')
            ->expectsQuestion('Nome', 'Ana')
            ->expectsQuestion('E-mail', 'email-invalido')
            ->expectsQuestion('Senha', 'curta')
            ->expectsQuestion('Confirme a senha', 'diferente')
            ->expectsOutput('Informe um e-mail válido.')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_regular_user_remains_non_admin_by_default(): void
    {
        $this->assertFalse(User::factory()->create()->refresh()->is_admin);
    }
}
