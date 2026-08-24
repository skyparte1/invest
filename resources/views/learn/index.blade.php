@extends('layouts.app')

@section('title', 'Aprender | Invest')
@section('meta_description', 'Conteúdos de educação financeira claros e fundamentados em fontes oficiais.')

@section('content')
    <section class="learning-hero" aria-labelledby="learning-title">
        <div class="container">
            <span class="eyebrow">Educação financeira</span>
            <h1 id="learning-title">Aprenda no seu ritmo</h1>
            <p>Construa uma base financeira sólida com conteúdos claros e fundamentados em fontes confiáveis.</p>
        </div>
    </section>

    <section class="learning-section" aria-labelledby="learning-list-title">
        <div class="container">
            <h2 class="visually-hidden" id="learning-list-title">Conteúdos educacionais</h2>

            <nav class="learning-filters" aria-label="Filtrar conteúdos por categoria">
                <a class="filter-link {{ $selectedCategory ? '' : 'active' }}" href="{{ route('learn.index') }}" @if (! $selectedCategory) aria-current="page" @endif>Todos</a>
                @foreach ($categories as $category)
                    <a class="filter-link {{ $selectedCategory?->is($category) ? 'active' : '' }}" href="{{ route('learn.index', ['categoria' => $category->slug]) }}" @if ($selectedCategory?->is($category)) aria-current="page" @endif>{{ $category->name }}</a>
                @endforeach
            </nav>

            @if ($selectedCategory)
                <div class="learning-selection">
                    <p><strong>{{ $selectedCategory->name }}</strong></p>
                    @if ($selectedCategory->description)<p>{{ $selectedCategory->description }}</p>@endif
                </div>
            @endif

            @if ($contents->isEmpty())
                <div class="learning-empty" role="status">
                    <h2>Nenhum conteúdo publicado nesta categoria</h2>
                    <p>Novos materiais serão incluídos após revisão das fontes.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($contents as $content)
                        <div class="col-md-6 col-xl-4"><x-content-card :content="$content" /></div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
