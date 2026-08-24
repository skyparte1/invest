<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use App\Services\FinancialGoalProgressService;
use App\Services\LearningProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, FinancialGoalProgressService $progressService, LearningProgressService $learningProgressService): View
    {
        $firstName = str($request->user()->name)->trim()->before(' ')->toString();
        $goals = $request->user()->financialGoals()->get();
        $presentedGoals = $goals->map(fn (FinancialGoal $goal) => [
            'goal' => $goal,
            'metrics' => $progressService->calculate($goal),
        ]);
        $goalSummary = $progressService->summarize($presentedGoals);
        $learningSummary = $learningProgressService->summary($request->user());
        $favoriteCount = $request->user()->favoriteInvestments()->where('is_published', true)->count();

        return view('dashboard.index', compact('firstName', 'goalSummary', 'learningSummary', 'favoriteCount'));
    }
}
