@extends('layouts.app')

@section('title', 'Criar conta | Invest')
@section('meta_description', 'Crie sua conta gratuita na Invest e comece sua jornada de educação financeira.')

@section('content')
    <section class="auth-section" aria-labelledby="register-title">
        <div class="container">
            <div class="auth-card mx-auto">
                <div class="auth-heading text-center">
                    <span class="eyebrow">Comece sua jornada</span>
                    <h1 id="register-title">Crie sua conta</h1>
                    <p>Cadastre-se para acessar sua área educacional.</p>
                </div>

                <form method="POST" action="{{ route('register.store') }}" data-disable-on-submit novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="name">Nome</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" maxlength="100" autocomplete="name" required autofocus @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name')<div class="invalid-feedback" id="name-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">E-mail</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<div class="invalid-feedback" id="email-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Senha</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" minlength="8" autocomplete="new-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        @error('password')<div class="invalid-feedback" id="password-error">{{ $message }}</div>@enderror
                        @unless($errors->has('password'))<div class="form-text">Use pelo menos 8 caracteres.</div>@endunless
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Confirmar senha</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" type="submit">Criar conta</button>
                </form>

                <p class="auth-alternative text-center">Já possui uma conta? <a href="{{ route('login') }}">Entrar</a></p>
            </div>
        </div>
    </section>
@endsection
