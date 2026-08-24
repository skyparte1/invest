<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use App\Services\FinancialGoalProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, FinancialGoalProgressService $progressService): View
    {
        $firstName = str($request->user()->name)->trim()->before(' ')->toString();
        $goals = $request->user()->financialGoals()->get();
        $presentedGoals = $goals->map(fn (FinancialGoal $goal) => [
            'goal' => $goal,
            'metrics' => $progressService->calculate($goal),
        ]);
        $goalSummary = $progressService->summarize($presentedGoals);

        return view('dashboard.index', compact('firstName', 'goalSummary'));
    }
}
