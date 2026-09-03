<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_mvp_routes_are_available(): void
    {
        foreach (['home', 'login', 'register'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_authenticated_mvp_routes_are_available(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['learn.index', 'investments.index', 'simulator.index', 'dashboard', 'planning.index', 'profile.edit'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_private_mvp_routes_redirect_guests_to_login(): void
    {
        foreach (['learn.index', 'investments.index', 'simulator.index', 'dashboard', 'planning.index', 'profile.edit', 'admin.dashboard'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_health_check_is_minimal_public_and_does_not_query_the_database(): void
    {
        $this->expectsDatabaseQueryCount(0);

        $this->getJson(route('health'))
            ->assertOk()
            ->assertExactJson(['status' => 'ok']);
    }

    public function test_security_headers_are_sent_without_exposing_stack_details(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');

        $content = $this->getJson(route('health'))->getContent();
        $this->assertStringNotContainsString('Laravel', $content);
        $this->assertStringNotContainsString('PHP', $content);
        $this->assertStringNotContainsString('database', $content);
    }
}
