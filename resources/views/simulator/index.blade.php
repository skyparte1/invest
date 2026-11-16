@extends('layouts.app')

@section('title', 'Simulador educacional | Invest')
@section('meta_description', 'Simule cenários hipotéticos de crescimento de capital com juros compostos e aportes mensais.')

@section('content')
    <section class="simulator-hero" aria-labelledby="simulator-title">
        <div class="container">
            <span class="eyebrow">Ferramenta educacional</span>
            <h1 id="simulator-title">Simule um cenário hipotético</h1>
            <a href="{{ route('simulator.history') }}">Minhas simulações salvas</a>
            <p>Explore uma projeção matemática bruta, sem garantia de retorno. Uma taxa anual constante simplifica a evolução do capital e não reproduz a volatilidade de ações e fundos.</p>
        </div>
    </section>

    <section class="simulator-section" aria-label="Formulário e resultado da simulação">
        <div class="container">
            <div class="simulator-layout">
                <div class="simulator-form-card">
                    <div class="simulator-card-heading">
                        <span class="eyebrow">Seus parâmetros</span>
                        <h2>Monte o cenário</h2>
                        <p>Os valores iniciais são apenas exemplos editáveis.</p>
                    </div>

                    <aside class="simulator-notice" role="note">
                        <span class="notice-symbol" aria-hidden="true">i</span>
                        <p>Simulação educacional: os valores apresentados são estimativas baseadas nos parâmetros informados e não representam garantia de rentabilidade ou recomendação de investimento.</p>
                    </aside>

                    <form method="POST" action="{{ route('simulator.calculate') }}" novalidate>
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="initial_amount">Valor inicial</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">R$</span>
                                    <input class="form-control @error('initial_amount') is-invalid @enderror" id="initial_amount" name="initial_amount" type="text" inputmode="decimal" value="{{ old('initial_amount', $form['initial_amount']) }}" maxlength="20" required aria-describedby="initial-help @error('initial_amount') initial-error @enderror" @error('initial_amount') aria-invalid="true" @enderror>
                                    @error('initial_amount')<div class="invalid-feedback" id="initial-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text" id="initial-help">Aceita zero ou um valor positivo.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="periodic_contribution">Aporte periódico</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">R$</span>
                                    <input class="form-control @error('periodic_contribution') is-invalid @enderror" id="periodic_contribution" name="periodic_contribution" type="text" inputmode="decimal" value="{{ old('periodic_contribution', $form['periodic_contribution']) }}" maxlength="20" required aria-describedby="contribution-help @error('periodic_contribution') contribution-error @enderror" @error('periodic_contribution') aria-invalid="true" @enderror>
                                    @error('periodic_contribution')<div class="invalid-feedback" id="contribution-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text" id="contribution-help">O aporte é realizado ao final de cada mês.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="annual_rate">Taxa hipotética utilizada (% ao ano)</label>
                                <div class="input-group has-validation">
                                    <input class="form-control @error('annual_rate') is-invalid @enderror" id="annual_rate" name="annual_rate" type="text" inputmode="decimal" value="{{ old('annual_rate', $form['annual_rate']) }}" maxlength="10" required aria-describedby="rate-help @error('annual_rate') rate-error @enderror" @error('annual_rate') aria-invalid="true" @enderror>
                                    <span class="input-group-text">% a.a.</span>
                                    @error('annual_rate')<div class="invalid-feedback" id="rate-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text" id="rate-help">Informe uma taxa maior que −100% e de até 100% ao ano. Use o sinal de menos para simular perdas.</div>
                                <input type="hidden" id="reference_id" name="reference_id" value="{{ $errors->any() ? '' : ($form['reference_id'] ?? '') }}">
                                <span id="reference-status" role="status"></span>
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="form-label" for="term">Prazo</label>
                                <input class="form-control @error('term') is-invalid @enderror" id="term" name="term" type="number" value="{{ old('term', $form['term']) }}" min="1" max="1200" step="1" required aria-describedby="term-help @error('term') term-error @enderror" @error('term') aria-invalid="true" @enderror>
                                @error('term')<div class="invalid-feedback" id="term-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="form-label" for="term_unit">Unidade</label>
                                <select class="form-select @error('term_unit') is-invalid @enderror" id="term_unit" name="term_unit" required @error('term_unit') aria-invalid="true" aria-describedby="term-unit-error" @enderror>
                                    <option value="months" @selected(old('term_unit', $form['term_unit']) === 'months')>Meses</option>
                                    <option value="years" @selected(old('term_unit', $form['term_unit']) === 'years')>Anos</option>
                                </select>
                                @error('term_unit')<div class="invalid-feedback" id="term-unit-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="contribution_frequency">Frequência do aporte</label>
                                <select class="form-select @error('contribution_frequency') is-invalid @enderror" id="contribution_frequency" name="contribution_frequency" required @error('contribution_frequency') aria-invalid="true" aria-describedby="frequency-error" @enderror>
                                    <option value="monthly" @selected(old('contribution_frequency', $form['contribution_frequency']) === 'monthly')>Mensal</option>
                                </select>
                                @error('contribution_frequency')<div class="invalid-feedback" id="frequency-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="investment_slug">Modalidade para contextualizar <span class="optional-label">(opcional)</span></label>
                                <select class="form-select @error('investment_slug') is-invalid @enderror" id="investment_slug" name="investment_slug" @error('investment_slug') aria-invalid="true" aria-describedby="investment-error" @enderror>
                                    <option value="">Nenhuma modalidade específica</option>
                                    @foreach ($investments as $investment)
                                        <option value="{{ $investment->slug }}" @selected(old('investment_slug', $form['investment_slug'] ?? '') === $investment->slug)>{{ $investment->name }}</option>
                                    @endforeach
                                </select>
                                @error('investment_slug')<div class="invalid-feedback" id="investment-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        @foreach ($investments as $investment)
                            <section class="simulation-context mt-3" data-context-slug="{{ $investment->slug }}" @if (old('investment_slug', $selectedInvestment?->slug) !== $investment->slug) hidden @endif>
                                <h3>{{ $investment->name }}</h3>
                                <p>{{ $investment->short_description }}</p>
                                <dl>
                                    <dt>Risco</dt><dd>{{ $investment->riskLabel() }}</dd>
                                    <dt>Liquidez</dt><dd>{{ $investment->liquidity_description }}</dd>
                                    <dt>Rentabilidade conceitual</dt><dd>{{ $investment->profitability_description }}</dd>
                                </dl>
                                <a href="{{ route('investments.show', $investment->slug) }}">Consultar detalhes e fontes</a>
                                <h4 class="mt-3">Referência de rentabilidade</h4>
                                @if ($investment->slug === 'fundos-de-investimento')
                                    <p>Escolha um índice para comparar. Cada fundo segue sua classe e estratégia, com custos e desempenho próprios.</p>
                                @endif
                                @forelse (App\Services\ReturnReferences::forModality($investment->slug) as $id => $ref)
                                    <div class="mb-3">
                                        <strong>{{ $ref['type'] }} · {{ $ref['name'] }}</strong>
                                        <p>{{ $ref['period'] }} · {{ $ref['date'] ?? 'Data indisponível' }}
                                            @if ($ref['value'] !== null)
                                                · {{ str_replace('.', ',', (string) $ref['value']) }} {{ $ref['unit'] }}
                                            @endif
                                        </p>
                                        <p>{{ $ref['description'] }} {{ $ref['notes'] }}</p>
                                        <a href="{{ $ref['url'] }}">Fonte oficial de {{ $ref['name'] }}</a>
                                        @if ($ref['usable'])
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-reference="{{ $id }}" data-rate="{{ $ref['value'] }}" aria-label="Usar esta referência: {{ $ref['name'] }}">Usar esta referência</button>
                                        @elseif (! $ref['fresh'])
                                            <p>Referência desatualizada ou sem data validada.</p>
                                        @else
                                            <p>Sem taxa numérica utilizável.</p>
                                        @endif
                                    </div>
                                @empty
                                    <p>Esta modalidade ainda não possui referência validada.</p>
                                @endforelse
                            </section>
                        @endforeach

                        <p class="term-limit" id="term-help">Prazo máximo: 100 anos ou 1.200 meses.</p>
                        <button class="btn btn-primary btn-lg w-100" type="submit">Simular cenário</button>
                    </form>
                </div>

                <aside class="simulator-summary-card" aria-labelledby="summary-title" @if (! $result) aria-live="polite" @endif>
                    @if ($result)
                        <span class="eyebrow">Resultado bruto</span>
                        <h2 id="summary-title">Resumo do cenário</h2>
                        <form method="POST" action="{{ route('simulator.save') }}" data-disable-on-submit>
                            @csrf
                            <input type="hidden" name="submission_id" value="{{ Illuminate\Support\Str::uuid() }}">
                            @foreach ($form as $field => $value)
                                <input type="hidden" name="{{ $field }}" value="{{ $value }}">
                            @endforeach
                            <button class="btn btn-outline-primary" type="submit">Salvar esta simulação</button>
                        </form>

                        <div class="result-highlight">
                            <span>Saldo estimado</span>
                            <strong>{{ App\Support\CurrencyFormatter::brl($result['final_balance']) }}</strong>
                        </div>

                        <dl class="result-list">
                            <div><dt>Valor inicial</dt><dd>{{ App\Support\CurrencyFormatter::brl($result['initial_amount']) }}</dd></div>
                            <div><dt>Aportes acumulados</dt><dd>{{ App\Support\CurrencyFormatter::brl($result['contributions_total']) }}</dd></div>
                            <div><dt>Total investido</dt><dd>{{ App\Support\CurrencyFormatter::brl($result['total_invested']) }}</dd></div>
                            <div><dt>{{ $result['estimated_earnings'] < 0 ? 'Prejuízo estimado' : 'Rendimento estimado' }}</dt><dd @class(['amount-loss' => $result['estimated_earnings'] < 0, 'amount-gain' => $result['estimated_earnings'] > 0])>{{ App\Support\CurrencyFormatter::brl($result['estimated_earnings']) }}</dd></div>
                            <div><dt>Taxa hipotética utilizada</dt><dd>{{ str_replace('.', ',', (string) $result['annual_rate']) }}% a.a.</dd></div>
                            <div><dt>Taxa mensal equivalente</dt><dd>{{ App\Support\CurrencyFormatter::percentage($result['monthly_rate'] * 100, 6) }} a.m.</dd></div>
                            <div><dt>Prazo</dt><dd>{{ $result['months'] }} {{ $result['months'] === 1 ? 'mês' : 'meses' }}</dd></div>
                        </dl>

                        @if ($selectedInvestment)
                            <div class="simulation-context">
                                <strong>Cenário hipotético contextualizado como {{ $selectedInvestment->name }}</strong>
                                <p>A taxa utilizada é uma hipótese de cálculo. O retorno efetivo pode variar.</p>
                                <a href="{{ route('investments.show', $selectedInvestment->slug) }}">Conhecer modalidade <span aria-hidden="true">→</span></a>
                            </div>
                        @endif

                        <aside class="result-disclaimer" role="note">
                            <p>Simulação educacional: os valores apresentados são estimativas baseadas nos parâmetros informados e não representam garantia de rentabilidade ou recomendação de investimento.</p>
                            <p>Os resultados são brutos e não consideram impostos, tarifas, inflação, custos operacionais ou regras específicas de investimentos.</p>
                        </aside>
                    @else
                        <span class="eyebrow">Resultado</span>
                        <h2 id="summary-title">Seu cenário aparecerá aqui</h2>
                        <p class="empty-summary">Preencha ou ajuste os parâmetros e selecione “Simular cenário”. O cálculo será feito no servidor, sem salvar os dados informados.</p>
                        <ul class="summary-preview">
                            <li>Total investido</li>
                            <li>Saldo e rendimento estimados</li>
                            <li>Evolução mês a mês</li>
                        </ul>
                    @endif
                </aside>
            </div>

            @if ($result)
                <section class="simulation-chart-card" aria-labelledby="chart-title">
                    <div class="chart-heading">
                        <div><span class="eyebrow">Evolução mensal</span><h2 id="chart-title">Capital ao longo do tempo</h2></div>
                        <p>O gráfico compara o total investido ao saldo estimado ao longo do período. O aporte ocorre ao final de cada mês.</p>
                    </div>
                    <div class="chart-container">
                        <canvas id="simulation-chart" role="img" aria-label="Gráfico de linhas do total investido e do saldo estimado mês a mês"></canvas>
                    </div>
                    <script id="simulation-chart-data" type="application/json">@json($result['series'])</script>
                </section>
            @endif
        </div>
    </section>
@endsection
