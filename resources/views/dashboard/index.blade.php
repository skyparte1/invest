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
            <h2 class="dashboard-section-title">Sua atividade</h2>
            <div class="activity-grid">
                <section class="activity-card">
                    <h3>Progresso educacional</h3>
                    <p><strong>{{ $learningSummary['completed'] }} de {{ $learningSummary['total'] }} conteúdos</strong> concluídos</p>
                    <div class="progress" role="progressbar" aria-label="Progresso educacional" aria-valuenow="{{ $learningSummary['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $learningSummary['percentage'] }}%"></div>
                    </div>
                    <a href="{{ route('learn.index') }}">Continuar aprendendo <span aria-hidden="true">→</span></a>
                </section>
                <section class="activity-card">
                    <h3>Planejamento</h3>
                    @if ($goalSummary['active'] > 0)
                        <p>Você possui <strong>{{ $goalSummary['active'] }} {{ $goalSummary['active'] === 1 ? 'meta ativa' : 'metas ativas' }}</strong></p>
                        <a href="{{ route('planning.index') }}">Acompanhar planejamento <span aria-hidden="true">→</span></a>
                    @else
                        <p>Você ainda não possui metas ativas.</p>
                        <a href="{{ route('planning.index') }}">Criar uma meta <span aria-hidden="true">→</span></a>
                    @endif
                </section>
                <section class="activity-card">
                    <h3>Favoritos</h3>
                    <p><strong>{{ $favoriteCount }} {{ $favoriteCount === 1 ? 'favorito salvo' : 'favoritos salvos' }}</strong></p>
                    <a href="{{ route('investments.index', ['favoritos' => 1]) }}">Ver favoritos <span aria-hidden="true">→</span></a>
                </section>
            </div>

            <h2 class="dashboard-section-title" id="dashboard-resources-title">Explore os módulos</h2>
            <div class="row g-4">
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true"><x-icon name="learn" /></span><span class="badge text-bg-light">Disponível</span><h3>Aprenda sobre finanças</h3><p>Construa sua base com conteúdos de educação financeira.</p><a class="dashboard-card-link stretched-link" href="{{ route('learn.index') }}">Explorar conteúdos <span aria-hidden="true">→</span></a></article></div>
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true"><x-icon name="investments" /></span><span class="badge text-bg-light">Disponível</span><h3>Conheça investimentos</h3><p>Entenda características, riscos e diferenças entre modalidades.</p><a class="dashboard-card-link stretched-link" href="{{ route('investments.index') }}">Explorar investimentos <span aria-hidden="true">→</span></a></article></div>
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true"><x-icon name="simulator" /></span><span class="badge text-bg-light">Disponível</span><h3>Simule cenários</h3><p>Visualize como parâmetros hipotéticos podem influenciar uma aplicação.</p><a class="dashboard-card-link stretched-link" href="{{ route('simulator.index') }}">Simular cenário <span aria-hidden="true">→</span></a></article></div>
                <div class="col-md-6 col-xl-3"><article class="dashboard-card dashboard-card-active h-100"><span class="card-icon" aria-hidden="true"><x-icon name="planning" /></span><span class="badge text-bg-light">Disponível</span><h3>Organize seus objetivos</h3><p>Planeje metas e acompanhe sua organização financeira.</p><a class="dashboard-card-link stretched-link" href="{{ route('planning.index') }}">Planejar objetivos <span aria-hidden="true">→</span></a></article></div>
            </div>

            <div class="dashboard-notice" role="note">
                <span class="notice-symbol" aria-hidden="true">i</span>
                <p>A Invest possui finalidade educacional e informativa e não realiza recomendações individualizadas de investimento.</p>
            </div>
        </div>
    </section>
@endsection
