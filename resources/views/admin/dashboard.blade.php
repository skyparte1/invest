@extends('layouts.app')
@section('title', 'Administração | Invest')
@section('content')
<main class="container py-5"><h1>Administração</h1><p>Gerencie o catálogo público sem acessar dados privados dos usuários.</p><x-admin-nav />
<div class="row g-3">@foreach(['published_contents'=>'Conteúdos publicados','draft_contents'=>'Conteúdos em rascunho','published_investments'=>'Investimentos publicados','draft_investments'=>'Investimentos em rascunho','sources'=>'Fontes'] as $key=>$label)<div class="col-sm-6 col-lg"><div class="card h-100"><div class="card-body"><strong class="fs-3">{{ $counts[$key] }}</strong><p class="mb-0">{{ $label }}</p></div></div></div>@endforeach</div></main>
@endsection
