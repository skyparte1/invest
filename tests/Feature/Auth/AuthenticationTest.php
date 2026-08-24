<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_users_can_authenticate_with_normalized_email(): void
    {
        $user = User::factory()->create([
            'email' => 'ana@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => '  ANA@EXAMPLE.COM ',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_regenerates_the_session_identifier(): void
    {
        $user = User::factory()->create(['password' => 'password123']);
        $this->get('/login');
        $previousSessionId = session()->getId();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertNotSame($previousSessionId, session()->getId());
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'ana@example.com',
            'password' => 'password123',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'ana@example.com',
            'password' => 'incorrect-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login')->assertSessionHasErrors([
            'email' => 'Não foi possível entrar com os dados informados.',
        ]);
    }

    public function test_remember_option_authenticates_user(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
        $response->assertCookie(Auth::guard()->getRecallerName());
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect(route('dashboard'));
    }

    public function test_intended_destination_is_respected_after_login(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->get('/dashboard')->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/dashboard');
    }

    public function test_excessive_login_attempts_are_rate_limited(): void
    {
        RateLimiter::clear('ana@example.com|127.0.0.1');

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', [
                'email' => 'ana@example.com',
                'password' => 'incorrect-password',
            ]);
        }

        $response = $this->from('/login')->post('/login', [
            'email' => 'ana@example.com',
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Muitas tentativas de acesso.',
            session('errors')->first('email'),
        );
    }
}
