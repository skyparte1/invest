@extends('layouts.app')

@section('title', $investment->name . ' | Invest')
@section('meta_description', $investment->short_description)

@section('content')
    <div class="article-shell investment-detail-shell">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb learning-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('investments.index') }}">Investimentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $investment->name }}</li>
                </ol>
            </nav>

            <article class="learning-article investment-article">
                <header class="article-header">
                    <a class="learning-category" href="{{ route('investments.index', ['categoria' => $investment->category->slug]) }}">{{ $investment->category->name }}</a>
                    <h1>{{ $investment->name }}</h1>
                    <p class="article-summary">{{ $investment->short_description }}</p>
                    <x-risk-badge :investment="$investment" />
                </header>

                <aside class="risk-explanation" role="note">
                    <strong>Sobre esta classificação</strong>
                    <p>A classificação de risco apresentada possui finalidade didática e resume características gerais da modalidade. O risco efetivo pode variar conforme emissor, prazo, condições de mercado, estratégia e características específicas do produto.</p>
                </aside>

                <div class="investment-sections">
                    <section class="investment-section">{!! $markdownSections['description'] !!}</section>
                    <section class="investment-section" aria-labelledby="risk-title"><h2 id="risk-title">Risco</h2>{!! $markdownSections['risk'] !!}<p class="learn-more">Aprofunde o conceito em <a href="{{ route('learn.show', 'risco-e-retorno') }}">Risco e retorno</a>.</p></section>
                    <section class="investment-section" aria-labelledby="liquidity-title"><h2 id="liquidity-title">Liquidez</h2>{!! $markdownSections['liquidity'] !!}<p class="learn-more">Aprofunde o conceito em <a href="{{ route('learn.show', 'liquidez') }}">Liquidez</a>.</p></section>
                    <section class="investment-section" aria-labelledby="profitability-title"><h2 id="profitability-title">Rentabilidade</h2>{!! $markdownSections['profitability'] !!}</section>
                    @if ($markdownSections->has('taxation'))<section class="investment-section" aria-labelledby="tax-title"><h2 id="tax-title">Tributação</h2>{!! $markdownSections['taxation'] !!}</section>@endif
                    @if ($markdownSections->has('protection'))<section class="investment-section" aria-labelledby="protection-title"><h2 id="protection-title">Proteção e mecanismos institucionais</h2>{!! $markdownSections['protection'] !!}</section>@endif
                    @if ($markdownSections->has('advantages'))<section class="investment-section" aria-labelledby="advantages-title"><h2 id="advantages-title">Características que podem ser relevantes</h2>{!! $markdownSections['advantages'] !!}</section>@endif
                    @if ($markdownSections->has('points'))<section class="investment-section attention-section" aria-labelledby="attention-title"><h2 id="attention-title">Pontos de atenção</h2>{!! $markdownSections['points'] !!}</section>@endif
                </div>

                <aside class="article-notice" role="note"><span class="notice-symbol" aria-hidden="true">i</span><p>Conteúdo educacional e informativo. Não constitui recomendação, análise de perfil ou orientação fiscal individual.</p></aside>

                <section class="article-sources" aria-labelledby="sources-title">
                    <h2 id="sources-title">Fontes e referências</h2>
                    <div class="source-list">
                        @foreach ($investment->sources as $source)
                            <article class="source-item"><span>{{ $source->institution }}</span><h3>{{ $source->title }}</h3><p>@if ($source->publication_date) Publicado em {{ $source->publication_date->format('d/m/Y') }} · @endif Acessado em {{ $source->accessed_at->format('d/m/Y') }}</p><a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer">Acessar fonte oficial <span class="visually-hidden">(abre em nova aba)</span></a></article>
                        @endforeach
                    </div>
                </section>
            </article>
        </div>
    </div>
@endsection
