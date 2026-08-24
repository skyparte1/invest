@extends('layouts.app')

@section('title', 'Investimentos | Invest')
@section('meta_description', 'Catálogo educacional de modalidades de investimento, riscos e características gerais.')

@section('content')
    <section class="catalog-hero" aria-labelledby="catalog-title">
        <div class="container">
            <span class="eyebrow">Catálogo educacional</span>
            <h1 id="catalog-title">Conheça antes de investir</h1>
            <p>Compare características gerais de diferentes modalidades e entenda seus riscos, liquidez e funcionamento.</p>
        </div>
    </section>

    <section class="catalog-section" aria-labelledby="catalog-list-title">
        <div class="container">
            <h2 class="visually-hidden" id="catalog-list-title">Modalidades de investimento</h2>

            <aside class="catalog-notice" role="note">
                <span class="notice-symbol" aria-hidden="true">i</span>
                <p>As informações desta área são educacionais e apresentam características gerais. Produtos específicos podem possuir condições diferentes. A Invest não oferece recomendações.</p>
            </aside>

            <div class="catalog-controls">
                @auth
                    <a class="btn {{ $favoritesOnly ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('investments.index', array_filter(['categoria' => $selectedCategory?->slug, 'risco' => $selectedRisk, 'favoritos' => $favoritesOnly ? null : 1])) }}">{{ $favoritesOnly ? 'Ver todos' : 'Somente favoritos' }}</a>
                @endauth
                <nav class="learning-filters" aria-label="Filtrar por categoria de investimento">
                    <a class="filter-link {{ $selectedCategory ? '' : 'active' }}" href="{{ route('investments.index', array_filter(['risco' => $selectedRisk, 'favoritos' => $favoritesOnly ? 1 : null])) }}" @if (! $selectedCategory) aria-current="page" @endif>Todos</a>
                    @foreach ($categories as $category)
                        <a class="filter-link {{ $selectedCategory?->is($category) ? 'active' : '' }}" href="{{ route('investments.index', array_filter(['categoria' => $category->slug, 'risco' => $selectedRisk, 'favoritos' => $favoritesOnly ? 1 : null])) }}" @if ($selectedCategory?->is($category)) aria-current="page" @endif>{{ $category->name }}</a>
                    @endforeach
                </nav>

                <form class="risk-filter" action="{{ route('investments.index') }}" method="GET">
                    @if ($selectedCategory)<input type="hidden" name="categoria" value="{{ $selectedCategory->slug }}">@endif
                    @if ($favoritesOnly)<input type="hidden" name="favoritos" value="1">@endif
                    <label for="risk-filter">Risco didático</label>
                    <select class="form-select" id="risk-filter" name="risco">
                        <option value="">Todos os níveis</option>
                        @foreach ($riskOptions as $value => $label)
                            <option value="{{ $value }}" @selected($selectedRisk === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-primary" type="submit">Aplicar</button>
                    @if ($selectedRisk)<a href="{{ route('investments.index', array_filter(['categoria' => $selectedCategory?->slug, 'favoritos' => $favoritesOnly ? 1 : null])) }}">Limpar risco</a>@endif
                </form>
            </div>

            <p class="risk-disclaimer">A classificação de risco possui finalidade didática e resume características gerais. O risco efetivo pode variar conforme emissor, prazo, mercado, estratégia e produto específico.</p>

            @if ($investments->isEmpty())
                <div class="learning-empty" role="status"><h2>Nenhuma modalidade encontrada</h2><p>Experimente remover um dos filtros.</p></div>
            @else
                <div class="row g-4">
                    @foreach ($investments as $investment)
                        <div class="col-md-6 col-xl-4"><x-investment-catalog-card :investment="$investment" :favorite="$favoriteIds->contains($investment->id)" /></div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
