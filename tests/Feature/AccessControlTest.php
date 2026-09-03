<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_only_public_pages_and_health_checks(): void
    {
        foreach (['home', 'login', 'register', 'health'] as $route) {
            $this->get(route($route))->assertOk();
        }

        $this->get('/up')->assertOk();
    }

    public function test_guest_is_redirected_from_main_and_admin_pages_to_login(): void
    {
        foreach (['learn.index', 'investments.index', 'simulator.index', 'planning.index', 'dashboard', 'profile.edit', 'admin.dashboard'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_authenticated_user_can_access_main_pages_but_not_admin(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['learn.index', 'investments.index', 'simulator.index', 'planning.index', 'dashboard', 'profile.edit'] as $route) {
            $this->get(route($route))->assertOk();
        }

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_only_an_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_login_returns_guest_to_originally_requested_page(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->get(route('simulator.index'))->assertRedirect(route('login'));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('simulator.index'));
    }
}
