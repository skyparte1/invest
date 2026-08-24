@extends('layouts.app')

@section('title', 'Editar meta | Invest')
@section('meta_description', 'Atualize os dados de uma meta financeira pessoal.')

@section('content')
    <section class="goal-edit-section" aria-labelledby="edit-goal-title">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb goal-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('planning.index') }}">Planejamento</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar meta</li>
                </ol>
            </nav>

            <div class="goal-edit-card mx-auto">
                <span class="eyebrow">Atualizar objetivo</span>
                <h1 id="edit-goal-title">Editar {{ $financialGoal->name }}</h1>
                <p>Altere os valores informados ou atualize o total já acumulado.</p>

                <form method="POST" action="{{ route('planning.update', $financialGoal) }}" novalidate>
                    @csrf
                    @method('PATCH')
                    @include('planning.partials.form-fields', ['goal' => $financialGoal])
                    <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                        <button class="btn btn-primary btn-lg" type="submit">Salvar alterações</button>
                        <a class="btn btn-outline-primary btn-lg" href="{{ route('planning.index') }}">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
