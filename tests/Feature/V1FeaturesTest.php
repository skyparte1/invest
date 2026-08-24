<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class V1FeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_access_is_separated_for_guest_user_and_admin(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Administração');
    }

    public function test_registration_and_profile_cannot_promote_user(): void
    {
        $this->post(route('register.store'), ['name' => 'Pessoa', 'email' => 'pessoa@example.com', 'password' => 'password', 'password_confirmation' => 'password', 'is_admin' => 1]);
        $user = User::where('email', 'pessoa@example.com')->firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->actingAs($user)->patch(route('profile.update'), ['name' => 'Pessoa', 'email' => 'pessoa@example.com', 'is_admin' => 1]);
        $this->assertFalse($user->fresh()->is_admin);
    }

    public function test_progress_is_authenticated_idempotent_reversible_and_private(): void
    {
        $content = $this->content();
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->put(route('learn.progress.store', $content))->assertRedirect(route('login'));
        $this->actingAs($user)->put(route('learn.progress.store', $content))->assertRedirect();
        $this->put(route('learn.progress.store', $content))->assertRedirect();
        $this->assertDatabaseCount('content_user_progress', 1);
        $this->assertDatabaseMissing('content_user_progress', ['user_id' => $other->id]);
        $this->delete(route('learn.progress.destroy', $content))->assertRedirect();
        $this->delete(route('learn.progress.destroy', $content))->assertRedirect();
        $this->assertDatabaseCount('content_user_progress', 0);
    }

    public function test_draft_cannot_be_completed_and_drafts_do_not_affect_summary(): void
    {
        $published = $this->content();
        $draft = $this->content(false, 'rascunho');
        $user = User::factory()->create();
        $this->actingAs($user)->put(route('learn.progress.store', $draft))->assertNotFound();
        $this->put(route('learn.progress.store', $published));
        $this->get(route('dashboard'))->assertOk()->assertSee('1 de 1 conteúdos');
    }

    public function test_learning_status_filter_is_personal_and_guest_ignores_it(): void
    {
        $done = $this->content(true, 'feito');
        $pending = $this->content(true, 'pendente');
        $user = User::factory()->create();
        $this->actingAs($user)->put(route('learn.progress.store', $done));
        $this->get(route('learn.index', ['status' => 'concluido']))->assertSee($done->title)->assertDontSee($pending->title);
        auth()->logout();
        $this->get(route('learn.index', ['status' => 'concluido']))->assertSee($done->title)->assertSee($pending->title)->assertDontSee('Pendente');
    }

    public function test_favorites_are_idempotent_reversible_filterable_and_combinable(): void
    {
        $favorite = $this->investment(true, 'favorito', 'low');
        $other = $this->investment(true, 'outro', 'high');
        $user = User::factory()->create();
        $this->actingAs($user)->put(route('investments.favorite.store', $favorite));
        $this->put(route('investments.favorite.store', $favorite));
        $this->assertDatabaseCount('investment_user_favorites', 1);
        $this->get(route('investments.index', ['favoritos' => 1, 'risco' => 'low']))->assertSee($favorite->name)->assertDontSee($other->name);
        $this->delete(route('investments.favorite.destroy', $favorite));
        $this->delete(route('investments.favorite.destroy', $favorite));
        $this->assertDatabaseCount('investment_user_favorites', 0);
    }

    public function test_guest_favorite_filter_is_ignored_and_draft_cannot_be_favorited(): void
    {
        $published = $this->investment();
        $draft = $this->investment(false, 'draft');
        $this->get(route('investments.index', ['favoritos' => 1]))->assertSee($published->name);
        $this->put(route('investments.favorite.store', $published))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->put(route('investments.favorite.store', $draft))->assertNotFound();
    }

    public function test_account_deletion_cascades_personal_progress_and_favorites_only(): void
    {
        $content = $this->content();
        $investment = $this->investment();
        $user = User::factory()->create();
        $this->actingAs($user)->put(route('learn.progress.store', $content));
        $this->put(route('investments.favorite.store', $investment));
        $user->delete();
        $this->assertDatabaseCount('content_user_progress', 0);
        $this->assertDatabaseCount('investment_user_favorites', 0);
        $this->assertDatabaseHas('contents', ['id' => $content->id]);
        $this->assertDatabaseHas('investments', ['id' => $investment->id]);
    }

    public function test_admin_publication_requires_source_and_slug_conflicts_are_predictable(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Base', 'slug' => 'base', 'sort_order' => 0]);
        $payload = ['category_id' => $category->id, 'title' => 'Mesmo título', 'summary' => 'Resumo', 'body' => 'Corpo', 'difficulty' => 'beginner', 'sort_order' => 0, 'is_published' => 1];
        $this->actingAs($admin)->post(route('admin.conteudos.store'), $payload)->assertSessionHasErrors('sources');
        $source = $this->source();
        $payload['sources'] = [$source->id];
        $this->post(route('admin.conteudos.store'), $payload)->assertSessionHasNoErrors();
        $this->post(route('admin.conteudos.store'), $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contents', ['slug' => 'mesmo-titulo']);
        $this->assertDatabaseHas('contents', ['slug' => 'mesmo-titulo-2']);
    }

    public function test_admin_source_rejects_non_http_url_and_linked_source_deletion(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post(route('admin.fontes.store'), ['institution' => 'X', 'title' => 'X', 'url' => 'javascript:alert(1)', 'accessed_at' => '2026-08-24'])->assertSessionHasErrors('url');
        $source = $this->source();
        $content = $this->content();
        $content->sources()->attach($source);
        $this->delete(route('admin.fontes.destroy', $source))->assertSessionHas('error');
        $this->assertDatabaseHas('sources', ['id' => $source->id]);
    }

    public function test_admin_draft_visibility_publication_and_investment_risk_validation(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Base', 'slug' => 'base', 'sort_order' => 0]);
        $contentPayload = ['category_id' => $category->id, 'title' => 'Material editorial', 'summary' => 'Resumo', 'body' => 'Corpo', 'difficulty' => 'beginner', 'sort_order' => 0, 'is_published' => 0];
        $this->actingAs($admin)->post(route('admin.conteudos.store'), $contentPayload)->assertSessionHasNoErrors();
        $content = Content::where('slug', 'material-editorial')->firstOrFail();
        $this->get(route('learn.index'))->assertDontSee($content->title);

        $source = $this->source();
        $this->put(route('admin.conteudos.update', $content), array_merge($contentPayload, ['slug' => '', 'is_published' => 1, 'sources' => [$source->id]]))->assertSessionHasNoErrors();
        $this->get(route('learn.index'))->assertSee($content->title);

        $investmentCategory = InvestmentCategory::create(['name' => 'Categoria', 'slug' => 'categoria-investimento', 'sort_order' => 0]);
        $investmentPayload = ['investment_category_id' => $investmentCategory->id, 'name' => 'Produto didático', 'short_description' => 'Resumo', 'description' => 'Descrição', 'risk_level' => 'extreme', 'risk_description' => 'Risco', 'liquidity_description' => 'Liquidez', 'profitability_description' => 'Rentabilidade', 'sort_order' => 0, 'is_published' => 1, 'sources' => [$source->id]];
        $this->post(route('admin.investimentos.store'), $investmentPayload)->assertSessionHasErrors('risk_level');
        $investmentPayload['risk_level'] = 'moderate';
        $this->post(route('admin.investimentos.store'), $investmentPayload)->assertSessionHasNoErrors();
        $this->get(route('investments.index'))->assertSee('Produto didático');
    }

    public function test_admin_navigation_is_visible_only_to_admin(): void
    {
        $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertDontSee('Administração');
        $this->actingAs(User::factory()->create(['is_admin' => true]))->get(route('dashboard'))->assertSee('Administração');
    }

    private function source(): Source
    {
        return Source::create(['institution' => 'BCB', 'title' => 'Fonte '.uniqid(), 'url' => 'https://example.com/'.uniqid(), 'accessed_at' => '2026-08-24']);
    }

    private function content(bool $published = true, string $slug = 'conteudo'): Content
    {
        $category = Category::firstOrCreate(['slug' => 'categoria'], ['name' => 'Categoria', 'sort_order' => 0]);

        return Content::create(['category_id' => $category->id, 'title' => 'Conteúdo '.$slug, 'slug' => $slug, 'summary' => 'Resumo', 'body' => 'Corpo', 'difficulty' => 'beginner', 'sort_order' => 0, 'is_published' => $published]);
    }

    private function investment(bool $published = true, string $slug = 'investimento', string $risk = 'low'): Investment
    {
        $category = InvestmentCategory::firstOrCreate(['slug' => 'renda-fixa'], ['name' => 'Renda fixa', 'sort_order' => 0]);

        return Investment::create(['investment_category_id' => $category->id, 'name' => 'Investimento '.$slug, 'slug' => $slug, 'short_description' => 'Resumo', 'description' => 'Descrição', 'risk_level' => $risk, 'risk_description' => 'Risco', 'liquidity_description' => 'Liquidez', 'profitability_description' => 'Rentabilidade', 'sort_order' => 0, 'is_published' => $published]);
    }
}
