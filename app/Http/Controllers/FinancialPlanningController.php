<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinancialGoalRequest;
use App\Http\Requests\UpdateFinancialGoalRequest;
use App\Models\FinancialGoal;
use App\Services\FinancialGoalProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FinancialPlanningController extends Controller
{
    public function index(Request $request, FinancialGoalProgressService $progressService): View
    {
        $goals = $request->user()->financialGoals()->orderBy('target_date')->orderBy('created_at')->get();
        $presentedGoals = $goals->map(fn (FinancialGoal $goal) => [
            'goal' => $goal,
            'metrics' => $progressService->calculate($goal),
        ]);
        $summary = $progressService->summarize($presentedGoals);

        return view('planning.index', compact('presentedGoals', 'summary'));
    }

    public function store(StoreFinancialGoalRequest $request): RedirectResponse
    {
        Gate::authorize('create', FinancialGoal::class);

        $request->user()->financialGoals()->create($request->validated());

        return to_route('planning.index')->with('status', 'Meta criada com sucesso.');
    }

    public function edit(FinancialGoal $financialGoal): View
    {
        Gate::authorize('update', $financialGoal);

        return view('planning.edit', compact('financialGoal'));
    }

    public function update(UpdateFinancialGoalRequest $request, FinancialGoal $financialGoal): RedirectResponse
    {
        Gate::authorize('update', $financialGoal);

        $financialGoal->update($request->validated());

        return to_route('planning.index')->with('status', 'Meta atualizada com sucesso.');
    }

    public function destroy(FinancialGoal $financialGoal): RedirectResponse
    {
        Gate::authorize('delete', $financialGoal);

        $financialGoal->delete();

        return to_route('planning.index')->with('status', 'Meta excluída com sucesso.');
    }
}
