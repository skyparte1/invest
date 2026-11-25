@extends('layouts.app')
@section('title', 'Editar vídeo | Administração')
@section('content')
<section class="container admin-workspace py-5">
    <h1>Vídeo complementar</h1>
    <x-admin-nav />
    <h2 class="h4 mb-3">{{ $topic }}</h2>
    <form method="POST" action="{{ route('admin.videos.update', [$module, $item->id]) }}">
        @csrf
        @method('PUT')
        @include('admin.videos.fields')
        <div class="mt-4">
            <button class="btn btn-primary" type="submit">Salvar vídeo</button>
            <a class="btn btn-link" href="{{ route('admin.videos.index', ['module' => $module]) }}">Voltar à lista</a>
        </div>
    </form>
    @if ($video->embedUrl)
        <x-educational-video :module="$module" :slug="$item->slug" :topic="$topic" :video="$item->video" />
    @endif
</section>
@endsection
