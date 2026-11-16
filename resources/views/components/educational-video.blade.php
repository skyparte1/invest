<section class="educational-video" aria-labelledby="video-title">
    <h2 id="video-title">Vídeo complementar</h2>
    @if ($embedUrl)
        <p>Aprofunde seus conhecimentos com este conteúdo educativo.</p>
        @if ($author)<p>{{ $author }}</p>@endif
        <div class="ratio ratio-16x9">
            <iframe src="{{ $embedUrl }}" title="Vídeo complementar sobre {{ $topic }}"
                    loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        <p class="mt-2"><a href="{{ $watchUrl }}" target="_blank" rel="noopener noreferrer">Assistir no YouTube<span class="visually-hidden"> (abre em nova aba)</span></a></p>
    @else
        <p>Vídeo em preparação.</p>
    @endif
</section>
