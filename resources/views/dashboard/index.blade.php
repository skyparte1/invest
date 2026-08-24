@extends('layouts.app')

@section('title', 'Dashboard | Invest')
@section('meta_description', 'Sua área educacional na plataforma Invest.')

@section('content')
    <section class="dashboard-hero" aria-labelledby="dashboard-title">
        <div class="container">
            <span class="eyebrow">Sua área educacional</span>
            <h1 id="dashboard-title">Olá, {{ $firstName }}.</h1>
            <p>Continue sua jornada financeira.</p>
        </div>
    </section>

    <section class="dashboard-content" aria-labelledby="dashboard-resources-title">
        <div class="container">
            <div class="section-heading mb-4">
                <h2 id="dashboard-resources-title">Continue sua jornada</h2>
                <p>Todos os recursos do MVP estão disponíveis para você explorar.</p>
            </div>

            <div class="dashboard-goals-summary" role="status">
                @if ($goalSummary['active'] > 0)
                    <p>Você possui <strong>{{ $goalSummary['active'] }} {{ $goalSummary['active'] === 1 ? 'meta ativa' : 'metas ativas' }}</strong>.</p>
                    <a href="{{ route('planning.index') }}">Acompanhar planejamento <span aria-hidden="true">→</span></a>
                @else
                    <p>Você ainda não possui metas ativas.</p>
                    <a href="{{ route('planning.index') }}">Criar uma meta <span aria-hidden="true">→</span></a>
                @endif
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true">01</span><span class="badge text-bg-light">Disponível</span><h3>Aprenda sobre finanças</h3><p>Construa sua base com conteúdos de educação financeira.</p><a class="dashboard-card-link stretched-link" href="{{ route('learn.index') }}">Explorar conteúdos <span aria-hidden="true">→</span></a></article></div>
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true">02</span><span class="badge text-bg-light">Disponível</span><h3>Conheça investimentos</h3><p>Entenda características, riscos e diferenças entre modalidades.</p><a class="dashboard-card-link stretched-link" href="{{ route('investments.index') }}">Explorar investimentos <span aria-hidden="true">→</span></a></article></div>
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true">03</span><span class="badge text-bg-light">Disponível</span><h3>Simule cenários</h3><p>Visualize como parâmetros hipotéticos podem influenciar uma aplicação.</p><a class="dashboard-card-link stretched-link" href="{{ route('simulator.index') }}">Simular cenário <span aria-hidden="true">→</span></a></article></div>
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true">04</span><span class="badge text-bg-light">Disponível</span><h3>Organize seus objetivos</h3><p>Planeje metas e acompanhe sua organização financeira.</p><a class="dashboard-card-link stretched-link" href="{{ route('planning.index') }}">Planejar objetivos <span aria-hidden="true">→</span></a></article></div>
            </div>

            <div class="dashboard-notice" role="note">
                <span class="notice-symbol" aria-hidden="true">i</span>
                <p>A Invest possui finalidade educacional e informativa e não realiza recomendações individualizadas de investimento.</p>
            </div>
        </div>
    </section>
@endsection
