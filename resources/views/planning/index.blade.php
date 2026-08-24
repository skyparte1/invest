@extends('layouts.app')

@section('title', 'Planejamento financeiro | Invest')
@section('meta_description', 'Organize metas financeiras pessoais e acompanhe referências matemáticas de progresso.')

@section('content')
    <section class="planning-hero" aria-labelledby="planning-title">
        <div class="container">
            <span class="eyebrow">Área privada</span>
            <h1 id="planning-title">Planeje seus objetivos</h1>
            <p>Organize metas financeiras, acompanhe seu progresso e visualize referências matemáticas de contribuição ao longo do prazo.</p>
        </div>
    </section>

    <section class="planning-section" aria-label="Metas financeiras">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success planning-feedback" role="status">{{ session('status') }}</div>
            @endif

            <aside class="planning-disclaimer" role="note">
                <span class="notice-symbol" aria-hidden="true">i</span>
                <p>O planejamento apresentado possui finalidade educacional e organizacional. Os valores de referência são cálculos matemáticos baseados nos dados informados e não constituem recomendação financeira.</p>
            </aside>

            <div class="planning-summary" aria-label="Resumo das metas">
                <article><span>Metas ativas</span><strong>{{ $summary['active'] }}</strong></article>
                <article><span>Metas atingidas</span><strong>{{ $summary['completed'] }}</strong></article>
                <article><span>Valor total das metas</span><strong>{{ App\Support\CurrencyFormatter::brl($summary['target_total']) }}</strong></article>
                <article><span>Total acumulado</span><strong>{{ App\Support\CurrencyFormatter::brl($summary['current_total']) }}</strong></article>
            </div>

            <div class="planning-layout">
                <aside class="goal-form-card" aria-labelledby="create-goal-title">
                    <span class="eyebrow">Novo objetivo</span>
                    <h2 id="create-goal-title">Criar uma meta</h2>
                    <p>Preencha os dados que você deseja usar para sua organização.</p>

                    <form method="POST" action="{{ route('planning.store') }}" novalidate>
                        @csrf
                        @include('planning.partials.form-fields')
                        <button class="btn btn-primary btn-lg w-100 mt-4" type="submit">Criar meta</button>
                    </form>
                </aside>

                <div class="goals-area" aria-labelledby="goals-title">
                    <div class="goals-heading">
                        <div><span class="eyebrow">Acompanhamento</span><h2 id="goals-title">Suas metas</h2></div>
                        <span>{{ $presentedGoals->count() }} {{ $presentedGoals->count() === 1 ? 'meta cadastrada' : 'metas cadastradas' }}</span>
                    </div>

                    @if ($presentedGoals->isEmpty())
                        <div class="goals-empty">
                            <span class="card-icon" aria-hidden="true">01</span>
                            <h3>Você ainda não possui metas cadastradas.</h3>
                            <p>Crie uma meta para começar a organizar um objetivo financeiro.</p>
                        </div>
                    @else
                        <div class="goal-list">
                            @foreach ($presentedGoals as $item)
                                @php($goal = $item['goal'])
                                @php($metrics = $item['metrics'])
                                <article class="goal-card">
                                    <header class="goal-card-header">
                                        <div>
                                            @if ($metrics['completed'])
                                                <span class="goal-status goal-status-completed">{{ $metrics['surpassed'] ? 'Meta superada' : 'Meta atingida' }}</span>
                                            @elseif ($metrics['expired'])
                                                <span class="goal-status goal-status-expired">Prazo encerrado</span>
                                            @else
                                                <span class="goal-status goal-status-active">Em andamento</span>
                                            @endif
                                            <h3>{{ $goal->name }}</h3>
                                        </div>
                                        <div class="goal-actions">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('planning.edit', $goal) }}" aria-label="Editar meta {{ $goal->name }}">Editar</a>
                                            <form method="POST" action="{{ route('planning.destroy', $goal) }}" onsubmit="return confirm('Excluir esta meta? Esta ação removerá os dados da meta.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Excluir meta {{ $goal->name }}">Excluir</button>
                                            </form>
                                        </div>
                                    </header>

                                    @if ($goal->description)<p class="goal-description">{{ $goal->description }}</p>@endif

                                    <div class="goal-progress-heading">
                                        <span>Progresso</span>
                                        <strong>{{ App\Support\CurrencyFormatter::percentage($metrics['progress_percentage']) }}</strong>
                                    </div>
                                    <div class="progress goal-progress" role="progressbar" aria-label="Progresso da meta {{ $goal->name }}" aria-valuenow="{{ $metrics['progress_bar_percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: {{ $metrics['progress_bar_percentage'] }}%"></div>
                                    </div>

                                    <dl class="goal-values">
                                        <div><dt>Valor-alvo</dt><dd>{{ App\Support\CurrencyFormatter::brl((float) $goal->target_amount) }}</dd></div>
                                        <div><dt>Valor acumulado</dt><dd>{{ App\Support\CurrencyFormatter::brl((float) $goal->current_amount) }}</dd></div>
                                        <div><dt>Valor restante</dt><dd>{{ App\Support\CurrencyFormatter::brl($metrics['remaining_amount']) }}</dd></div>
                                        <div><dt>Data-alvo</dt><dd>{{ $goal->target_date->format('d/m/Y') }}</dd></div>
                                    </dl>

                                    <div class="goal-reference">
                                        @if ($metrics['completed'])
                                            <strong>{{ $metrics['surpassed'] ? 'Meta superada' : 'Meta atingida' }}</strong>
                                            <p>O valor acumulado alcançou o valor-alvo informado.</p>
                                        @elseif ($metrics['expired'])
                                            <strong>Prazo encerrado</strong>
                                            <p>O prazo informado já passou. Você pode editar a meta e definir uma nova data se desejar.</p>
                                        @else
                                            <span>Valor mensal de referência</span>
                                            <strong>{{ App\Support\CurrencyFormatter::brl($metrics['monthly_reference']) }}</strong>
                                            <p>@if ($metrics['days_remaining'] === 0) O prazo termina hoje.@else {{ $metrics['days_remaining'] }} dias restantes.@endif Divisão por {{ $metrics['months_reference'] }} {{ $metrics['months_reference'] === 1 ? 'mês-calendário' : 'meses-calendário' }}.</p>
                                        @endif
                                    </div>

                                    <p class="goal-calculation-note">O cálculo não considera rendimentos, inflação, impostos, taxas ou imprevistos.</p>
                                    @unless ($metrics['completed'] || $metrics['expired'])<a class="goal-simulator-link" href="{{ route('simulator.index') }}">Simular cenário separado <span aria-hidden="true">→</span></a>@endunless
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
