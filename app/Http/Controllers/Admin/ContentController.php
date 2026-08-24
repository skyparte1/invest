<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View
    {
        return view('admin.contents.index', ['contents' => Content::with('category')->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.contents.form', $this->options(new Content));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $sources = $data['sources'] ?? [];
        unset($data['sources']);
        $content = Content::create($data);
        $content->sources()->sync($sources);

        return redirect()->route('admin.conteudos.index')->with('status', 'Conteúdo criado.');
    }

    public function edit(Content $content): View
    {
        return view('admin.contents.form', $this->options($content));
    }

    public function update(Request $request, Content $content): RedirectResponse
    {
        $data = $this->validated($request, $content);
        $sources = $data['sources'] ?? [];
        unset($data['sources']);
        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : $content->slug;
        $content->update($data);
        $content->sources()->sync($sources);

        return redirect()->route('admin.conteudos.index')->with('status', 'Conteúdo atualizado.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        $content->delete();

        return back()->with('status', 'Conteúdo excluído.');
    }

    private function options(Content $content): array
    {
        return compact('content') + ['categories' => Category::orderBy('name')->get(), 'sources' => Source::orderBy('institution')->get()];
    }

    private function validated(Request $request, ?Content $content = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'], 'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('contents')->ignore($content)],
            'summary' => ['required', 'string'], 'body' => ['required', 'string'], 'difficulty' => ['required', Rule::in(Content::DIFFICULTIES)],
            'estimated_minutes' => ['nullable', 'integer', 'min:1'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_published' => ['required', 'boolean'],
            'sources' => [Rule::requiredIf($request->boolean('is_published')), 'array'], 'sources.*' => ['integer', 'distinct', 'exists:sources,id'],
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'conteudo';
        $slug = $base;
        $suffix = 2;
        while (Content::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
