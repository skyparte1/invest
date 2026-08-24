@props(['content'])

<article class="learning-card h-100">
    <span class="learning-category">{{ $content->category->name }}</span>
    <h2>{{ $content->title }}</h2>
    <p>{{ $content->summary }}</p>
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
    <a class="learning-card-link" href="{{ route('learn.show', $content->slug) }}">Ler conteúdo <span aria-hidden="true">→</span></a>
</article>
