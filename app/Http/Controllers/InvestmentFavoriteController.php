<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvestmentFavoriteController extends Controller
{
    public function store(Request $request, Investment $investment): RedirectResponse
    {
        abort_unless($investment->is_published, 404);
        $request->user()->favoriteInvestments()->syncWithoutDetaching([$investment->id]);

        return back()->with('status', 'Investimento salvo nos favoritos.');
    }

    public function destroy(Request $request, Investment $investment): RedirectResponse
    {
        abort_unless($investment->is_published, 404);
        $request->user()->favoriteInvestments()->detach($investment->id);

        return back()->with('status', 'Investimento removido dos favoritos.');
    }
}
