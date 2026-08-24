@extends('layouts.app')

@section('title', 'Entrar | Invest')
@section('meta_description', 'Entre na sua conta da Invest para acessar o dashboard educacional.')

@section('content')
    <section class="auth-section" aria-labelledby="login-title">
        <div class="container">
            <div class="auth-card mx-auto">
                <div class="auth-heading text-center">
                    <span class="eyebrow">Bem-vindo de volta</span>
                    <h1 id="login-title">Entre na sua conta</h1>
                    <p>Continue sua jornada de educação financeira.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">E-mail</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<div class="invalid-feedback" id="email-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Senha</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="current-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        @error('password')<div class="invalid-feedback" id="password-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                        <label class="form-check-label" for="remember">Lembrar de mim</label>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" type="submit">Entrar</button>
                </form>

                <p class="auth-alternative text-center">Ainda não tem uma conta? <a href="{{ route('register') }}">Criar conta</a></p>
            </div>
        </div>
    </section>
@endsection
