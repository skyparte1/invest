<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_new_users_can_register_and_are_authenticated(): void
    {
        $response = $this->post('/register', [
            'name' => '  Ana   Souza  ',
            'email' => '  ANA@EXAMPLE.COM ',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::sole();
        $this->assertSame('Ana Souza', $user->name);
        $this->assertSame('ana@example.com', $user->email);
        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Outra Pessoa',
            'email' => 'ANA@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register')->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_password_confirmation_is_required(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Ana Souza',
            'email' => 'ana@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/register')->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_mismatched_confirmation_has_accessible_invalid_state(): void
    {
        $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Ana Souza',
            'email' => 'ana@example.com',
            'password' => 'senha-segura',
            'password_confirmation' => 'senha-diferente',
        ]);

        $response = $this->get(route('register'));

        $response->assertOk()->assertSee('aria-invalid="true" aria-describedby="password-error"', false);
        $this->assertMatchesRegularExpression(
            '/class="form-control\s+is-invalid\s*" id="password_confirmation"/',
            $response->getContent(),
        );
    }

    public function test_registration_rejects_invalid_email_short_password_and_mismatched_confirmation(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Ana Souza',
            'email' => 'email-invalido',
            'password' => 'curta',
            'password_confirmation' => 'diferente',
        ])->assertRedirect('/register')->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_away_from_registration(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/register')->assertRedirect(route('dashboard'));
    }
}
