<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_learning_page_lists_only_published_contents(): void
    {
        $category = $this->createCategory('Fundamentos', 'fundamentos');
        $published = $this->createContent($category, 'Conteúdo publicado', 'publicado', true);
        $draft = $this->createContent($category, 'Conteúdo reservado', 'reservado', false);

        $this->get(route('learn.index'))
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title)
            ->assertSee(route('learn.index'), false);
    }

    public function test_authenticated_user_can_access_learning_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('learn.index'))
            ->assertOk();
    }

    public function test_category_filter_uses_slug_and_excludes_other_categories(): void
    {
        $fundamentals = $this->createCategory('Fundamentos', 'fundamentos', 1);
        $investments = $this->createCategory('Investimentos', 'investimentos', 2);
        $budget = $this->createContent($fundamentals, 'Orçamento pessoal', 'orcamento');
        $liquidity = $this->createContent($investments, 'Liquidez', 'liquidez');

        $this->get(route('learn.index', ['categoria' => $fundamentals->slug]))
            ->assertOk()
            ->assertSee($budget->title)
            ->assertDontSee($liquidity->title);
    }

    public function test_unknown_category_is_ignored_without_server_error(): void
    {
        $category = $this->createCategory('Fundamentos', 'fundamentos');
        $content = $this->createContent($category, 'Orçamento pessoal', 'orcamento');

        $this->get(route('learn.index', ['categoria' => 'nao-existe']))
            ->assertOk()
            ->assertSee($content->title);
    }

    public function test_published_content_displays_category_body_and_sources(): void
    {
        $category = $this->createCategory('Fundamentos financeiros', 'fundamentos');
        $content = $this->createContent($category, 'Orçamento pessoal', 'orcamento');
        $source = Source::query()->create([
            'institution' => 'Banco Central do Brasil',
            'title' => 'Fonte oficial',
            'url' => 'https://www.bcb.gov.br/fonte',
            'publication_date' => null,
            'accessed_at' => '2026-08-24',
        ]);
        $content->sources()->attach($source, ['sort_order' => 1]);

        $this->get(route('learn.show', $content->slug))
            ->assertOk()
            ->assertSee($content->title)
            ->assertSee($category->name)
            ->assertSee('Texto educacional')
            ->assertSee($source->institution)
            ->assertSee($source->title);
    }

    public function test_draft_and_unknown_slugs_return_not_found(): void
    {
        $category = $this->createCategory('Fundamentos', 'fundamentos');
        $draft = $this->createContent($category, 'Rascunho', 'rascunho', false);

        $this->get(route('learn.show', $draft->slug))->assertNotFound();
        $this->get(route('learn.show', 'nao-existe'))->assertNotFound();
    }

    public function test_markdown_is_rendered_and_arbitrary_html_is_stripped(): void
    {
        $category = $this->createCategory('Fundamentos', 'fundamentos');
        $content = $this->createContent($category, 'Markdown seguro', 'markdown-seguro');
        $content->update(['body' => "## Título seguro\n\nTexto **destacado**.\n\n<script>alert('perigo')</script>"]);

        $this->get(route('learn.show', $content->slug))
            ->assertOk()
            ->assertSee('<h2>Título seguro</h2>', false)
            ->assertSee('<strong>destacado</strong>', false)
            ->assertDontSee("<script>alert('perigo')</script>", false)
            ->assertDontSee("alert('perigo')", false);
    }

    private function createCategory(string $name, string $slug, int $sortOrder = 1): Category
    {
        return Category::query()->create([
            'name' => $name,
            'slug' => $slug,
            'description' => 'Descrição da categoria.',
            'sort_order' => $sortOrder,
        ]);
    }

    private function createContent(Category $category, string $title, string $slug, bool $published = true): Content
    {
        return Content::query()->create([
            'category_id' => $category->id,
            'title' => $title,
            'slug' => $slug,
            'summary' => 'Resumo educacional.',
            'body' => "## Texto educacional\n\nConteúdo fundamentado.",
            'difficulty' => 'beginner',
            'estimated_minutes' => 4,
            'sort_order' => 1,
            'is_published' => $published,
        ]);
    }
}
