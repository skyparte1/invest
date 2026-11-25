@extends('layouts.app')
@section('title', 'Vídeos | Administração')
@section('content')
<section class="container admin-workspace py-5">
    <h1>Vídeos complementares</h1>
    <x-admin-nav />
    <nav class="learning-filters" aria-label="Módulo">
        @foreach (['learn' => 'Aprender', 'investments' => 'Investimentos'] as $key => $label)
            <a @class(['filter-link', 'active' => $key === $module]) @if ($key === $module) aria-current="page" @endif href="{{ route('admin.videos.index', ['module' => $key]) }}">{{ $label }}</a>
        @endforeach
    </nav>
    <ul class="list-group mb-3">
        @forelse ($items as $item)
            <li class="list-group-item"><a href="{{ route('admin.videos.edit', [$module, $item->id]) }}">{{ $module === 'learn' ? $item->title : $item->name }}</a></li>
        @empty
            <li class="list-group-item">Nenhum item cadastrado neste módulo.</li>
        @endforelse
    </ul>
    {{ $items->links() }}
</section>
@endsection
