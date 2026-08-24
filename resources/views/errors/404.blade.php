@extends('layouts.app')

@section('title', 'Página não encontrada | Invest')

@section('content')
    <section class="error-section" aria-labelledby="error-title">
        <div class="container text-center">
            <span class="error-code" aria-hidden="true">404</span>
            <h1 id="error-title">Página não encontrada.</h1>
            <p>O endereço informado não existe ou não está mais disponível.</p>
            <a class="btn btn-primary btn-lg" href="{{ route('home') }}">Voltar ao início</a>
        </div>
    </section>
@endsection
