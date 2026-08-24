@extends('layouts.app')

@section('title', 'Acesso não autorizado | Invest')

@section('content')
    <section class="error-section" aria-labelledby="error-title">
        <div class="container text-center">
            <span class="error-code" aria-hidden="true">403</span>
            <h1 id="error-title">Você não tem acesso a este conteúdo.</h1>
            <p>Volte para uma área disponível da plataforma.</p>
            <a class="btn btn-primary btn-lg" href="{{ auth()->check() ? route('dashboard') : route('home') }}">{{ auth()->check() ? 'Ir para o dashboard' : 'Voltar ao início' }}</a>
        </div>
    </section>
@endsection
