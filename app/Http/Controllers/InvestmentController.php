<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Support\SafeMarkdown;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index(Request $request): View
    {
        $categories = InvestmentCategory::query()->orderBy('sort_order')->orderBy('name')->get();
        $selectedCategory = $categories->firstWhere('slug', $request->string('categoria')->trim()->toString());
        $requestedRisk = $request->string('risco')->trim()->toString();
        $selectedRisk = in_array($requestedRisk, Investment::RISK_LEVELS, true) ? $requestedRisk : null;
        $favoriteIds = $request->user()?->favoriteInvestments()->pluck('investments.id') ?? collect();
        $favoritesOnly = $request->user() && $request->boolean('favoritos');

        $investments = Investment::query()
            ->published()
            ->with('category')
            ->when($selectedCategory, fn ($query) => $query->whereBelongsTo($selectedCategory, 'category'))
            ->when($selectedRisk, fn ($query) => $query->where('risk_level', $selectedRisk))
            ->when($favoritesOnly, fn ($query) => $query->whereIn('id', $favoriteIds))
            ->orderBy('investment_category_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $riskOptions = ['low' => 'Baixo', 'moderate' => 'Moderado', 'high' => 'Alto', 'variable' => 'Variável'];

        return view('investments.index', compact('categories', 'investments', 'selectedCategory', 'selectedRisk', 'riskOptions', 'favoriteIds', 'favoritesOnly'));
    }

    public function show(Request $request, string $slug): View
    {
        $investment = Investment::query()
            ->published()
            ->with(['category', 'sources'])
            ->where('slug', $slug)
            ->firstOrFail();

        $markdownSections = collect([
            'description' => $investment->description,
            'risk' => $investment->risk_description,
            'liquidity' => $investment->liquidity_description,
            'profitability' => $investment->profitability_description,
            'taxation' => $investment->taxation_description,
            'protection' => $investment->protection_description,
            'advantages' => $investment->advantages,
            'points' => $investment->points_of_attention,
        ])->filter(fn ($value) => filled($value))->map(fn ($value) => SafeMarkdown::render($value));

        $isFavorite = $request->user()?->favoriteInvestments()->whereKey($investment->id)->exists() ?? false;

        return view('investments.show', compact('investment', 'markdownSections', 'isFavorite'));
    }
}
