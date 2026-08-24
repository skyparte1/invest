<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvestmentController extends Controller
{
    public function index(): View
    {
        return view('admin.investments.index', ['investments' => Investment::with('category')->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.investments.form', $this->options(new Investment));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $sources = $data['sources'] ?? [];
        unset($data['sources']);
        $investment = Investment::create($data);
        $investment->sources()->sync($sources);

        return redirect()->route('admin.investimentos.index')->with('status', 'Investimento criado.');
    }

    public function edit(Investment $investment): View
    {
        return view('admin.investments.form', $this->options($investment));
    }

    public function update(Request $request, Investment $investment): RedirectResponse
    {
        $data = $this->validated($request, $investment);
        $sources = $data['sources'] ?? [];
        unset($data['sources']);
        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : $investment->slug;
        $investment->update($data);
        $investment->sources()->sync($sources);

        return redirect()->route('admin.investimentos.index')->with('status', 'Investimento atualizado.');
    }

    public function destroy(Investment $investment): RedirectResponse
    {
        $investment->delete();

        return back()->with('status', 'Investimento excluído.');
    }

    private function options(Investment $investment): array
    {
        return compact('investment') + ['categories' => InvestmentCategory::orderBy('name')->get(), 'sources' => Source::orderBy('institution')->get()];
    }

    private function validated(Request $request, ?Investment $investment = null): array
    {
        return $request->validate([
            'investment_category_id' => ['required', 'exists:investment_categories,id'], 'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('investments')->ignore($investment)], 'short_description' => ['required', 'string'],
            'description' => ['required', 'string'], 'risk_level' => ['required', Rule::in(Investment::RISK_LEVELS)],
            'risk_description' => ['required', 'string'], 'liquidity_description' => ['required', 'string'], 'profitability_description' => ['required', 'string'],
            'taxation_description' => ['nullable', 'string'], 'protection_description' => ['nullable', 'string'], 'advantages' => ['nullable', 'string'], 'points_of_attention' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'], 'is_published' => ['required', 'boolean'],
            'sources' => [Rule::requiredIf($request->boolean('is_published')), 'array'], 'sources.*' => ['integer', 'distinct', 'exists:sources,id'],
        ]);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'investimento';
        $slug = $base;
        $suffix = 2;
        while (Investment::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
