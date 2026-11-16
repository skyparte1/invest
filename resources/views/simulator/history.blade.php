@extends('layouts.app')

@section('title', 'Minhas simulações | Invest')

@section('content')
    <section class="container py-5">
        <a href="{{ route('simulator.index') }}">Voltar ao simulador</a>
        <h1 class="my-4">Minhas simulações salvas</h1>
        @forelse ($simulations as $simulation)
            <article class="simulator-summary-card mb-3">
                <h2 class="h4">{{ $simulation->investment_name ?? 'Cenário hipotético' }}</h2>
                <p>{{ $simulation->created_at->format('d/m/Y H:i') }}</p>
                <dl class="result-list">
                    <div><dt>Valor inicial</dt><dd>{{ App\Support\CurrencyFormatter::brl($simulation->initial_amount) }}</dd></div>
                    <div><dt>Aporte mensal</dt><dd>{{ App\Support\CurrencyFormatter::brl($simulation->monthly_contribution) }}</dd></div>
                    <div><dt>Taxa anual hipotética</dt><dd>{{ App\Support\CurrencyFormatter::percentage($simulation->annual_rate) }}</dd></div>
                    <div><dt>Prazo</dt><dd>{{ $simulation->term_months }} meses</dd></div>
                    <div><dt>Saldo bruto estimado</dt><dd>{{ App\Support\CurrencyFormatter::brl($simulation->final_balance) }}</dd></div>
                </dl>
            </article>
        @empty
            <p>Você ainda não salvou simulações.</p>
        @endforelse
        {{ $simulations->links() }}
    </section>
@endsection
