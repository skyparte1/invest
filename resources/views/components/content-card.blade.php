@props(['content', 'completed' => null])

<article class="learning-card h-100">
    <span class="learning-category">{{ $content->category->name }}</span>
    @auth @if (! is_null($completed))<span class="badge {{ $completed ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $completed ? 'Concluído' : 'Pendente' }}</span>@endif @endauth
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
