<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimulationRequest;
use App\Models\Investment;
use App\Services\InvestmentSimulationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SimulationController extends Controller
{
    public function index(Request $request): View
    {
        $investmentSlug = $request->query('investimento');
        $selectedInvestment = is_string($investmentSlug)
            ? Investment::published()->where('slug', $investmentSlug)->first()
            : null;

        return $this->render($this->defaults($selectedInvestment?->slug), $selectedInvestment);
    }

    public function history(Request $request): View
    {
        return view('simulator.history', [
            'simulations' => $request->user()->simulations()->latest('id')->simplePaginate(10),
        ]);
    }

    public function calculate(SimulationRequest $request, InvestmentSimulationService $service): View|RedirectResponse
    {
        $validated = $request->validated();
        $selectedInvestment = empty($validated['investment_slug'])
            ? null
            : Investment::published()->where('slug', $validated['investment_slug'])->first();

        $result = $service->simulate(
            (float) $validated['initial_amount'],
            (float) $validated['periodic_contribution'],
            (float) $validated['annual_rate'],
            (int) $validated['term'],
            $validated['term_unit'],
        );

        if ($request->routeIs('simulator.save')) {
            $submission = $request->validate(['submission_id' => ['required', 'uuid']]);
            $request->user()->simulations()->firstOrCreate($submission, [
                'investment_id' => $selectedInvestment?->id,
                'investment_name' => $selectedInvestment?->name,
                'initial_amount' => $result['initial_amount'],
                'monthly_contribution' => $result['periodic_contribution'],
                'term_months' => $result['months'],
                'annual_rate' => $result['annual_rate'],
                'final_balance' => $result['final_balance'],
            ]);

            return to_route('simulator.history')->with('status', 'Simulação salva.');
        }

        return $this->render($validated, $selectedInvestment, $result);
    }

    private function render(array $form, ?Investment $selectedInvestment = null, ?array $result = null): View
    {
        $investments = Investment::published()
            ->orderBy('name')
            ->get(['name', 'slug', 'short_description', 'risk_level', 'liquidity_description', 'profitability_description']);

        return view('simulator.index', compact('form', 'investments', 'selectedInvestment', 'result'));
    }

    private function defaults(?string $investmentSlug): array
    {
        return [
            'initial_amount' => '1000',
            'periodic_contribution' => '200',
            'annual_rate' => '10',
            'term' => '5',
            'term_unit' => 'years',
            'contribution_frequency' => 'monthly',
            'investment_slug' => $investmentSlug,
        ];
    }
}
