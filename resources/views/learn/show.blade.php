@extends('layouts.app')

@section('title', $content->title . ' | Invest')
@section('meta_description', $content->summary)

@section('content')
    <div class="article-shell">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb learning-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('learn.index') }}">Aprender</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $content->title }}</li>
                </ol>
            </nav>

            <article class="learning-article">
                <header class="article-header">
                    <a class="learning-category" href="{{ route('learn.index', ['categoria' => $content->category->slug]) }}">{{ $content->category->name }}</a>
                    <h1>{{ $content->title }}</h1>
                    <p class="article-summary">{{ $content->summary }}</p>
                    <div class="learning-meta" aria-label="Informações do conteúdo">
                        <span>
                            @switch($content->difficulty)
                                @case('intermediate') Intermediário @break
                                @case('advanced') Avançado @break
                                @default Iniciante
                            @endswitch
                        </span>
                        @if ($content->estimated_minutes)
                            <span aria-label="Tempo estimado de leitura: {{ $content->estimated_minutes }} minutos">{{ $content->estimated_minutes }} min de leitura</span>
                        @endif
                    </div>
                </header>

                <div class="article-body">
                    {!! $bodyHtml !!}
                </div>

                <aside class="article-notice" role="note" aria-label="Aviso educacional">
                    <span class="notice-symbol" aria-hidden="true">i</span>
                    <p>Conteúdo educacional e informativo. Não constitui recomendação individualizada de investimento.</p>
                </aside>

                <section class="article-sources" aria-labelledby="sources-title">
                    <h2 id="sources-title">Fontes e referências</h2>
                    <div class="source-list">
                        @foreach ($content->sources as $source)
                            <article class="source-item">
                                <span>{{ $source->institution }}</span>
                                <h3>{{ $source->title }}</h3>
                                <p>
                                    @if ($source->publication_date)
                                        Publicado em {{ $source->publication_date->format('d/m/Y') }} ·
                                    @endif
                                    Acessado em {{ $source->accessed_at->format('d/m/Y') }}
                                </p>
                                <a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer">Acessar fonte oficial <span class="visually-hidden">(abre em nova aba)</span></a>
                            </article>
                        @endforeach
                    </div>
                </section>
            </article>

            @if ($relatedContents->isNotEmpty())
                <section class="related-section" aria-labelledby="related-title">
                    <div class="section-heading"><h2 id="related-title">Continue aprendendo</h2><p>Outros conteúdos da mesma categoria.</p></div>
                    <div class="row g-4">
                        @foreach ($relatedContents as $relatedContent)
                            <div class="col-md-6 col-xl-4"><x-content-card :content="$relatedContent" /></div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
