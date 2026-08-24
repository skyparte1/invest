@props(['investment', 'favorite' => false])

<article class="catalog-card h-100">
    <span class="learning-category">{{ $investment->category->name }}</span>
    @auth @if ($favorite)<span class="badge text-bg-warning">Favorito</span>@endif @endauth
    <h2>{{ $investment->name }}</h2>
    <p>{{ $investment->short_description }}</p>
    <x-risk-badge :investment="$investment" />
    <a class="learning-card-link" href="{{ route('investments.show', $investment->slug) }}">Conhecer modalidade <span aria-hidden="true">→</span></a>
</article>
