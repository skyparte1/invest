<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimulationRequest;
use App\Models\Investment;
use App\Services\InvestmentSimulationService;
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

    public function calculate(SimulationRequest $request, InvestmentSimulationService $service): View
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

        return $this->render($validated, $selectedInvestment, $result);
    }

    private function render(array $form, ?Investment $selectedInvestment = null, ?array $result = null): View
    {
        $investments = Investment::published()
            ->orderBy('name')
            ->get(['name', 'slug']);

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
