@extends('layouts.app')

@section('title', 'Perfil | Invest')
@section('meta_description', 'Atualize os dados e a senha da sua conta Invest.')

@section('content')
    <section class="profile-hero" aria-labelledby="profile-title">
        <div class="container">
            <span class="eyebrow">Sua conta</span>
            <h1 id="profile-title">Perfil</h1>
            <p>Gerencie seus dados de acesso e as configurações da sua conta.</p>
        </div>
    </section>

    <section class="profile-section" aria-label="Configurações do perfil">
        <div class="container profile-layout">
            <article class="profile-card" aria-labelledby="profile-data-title">
                <h2 id="profile-data-title">Dados pessoais</h2>
                <p>Use um nome e um e-mail válidos para identificar sua conta.</p>

                <form method="POST" action="{{ route('profile.update') }}" novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label" for="name">Nome</label>
                        <input class="form-control @error('name', 'updateProfile') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" maxlength="100" autocomplete="name" required @error('name', 'updateProfile') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name', 'updateProfile')<div class="invalid-feedback" id="name-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="email">E-mail</label>
                        <input class="form-control @error('email', 'updateProfile') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required @error('email', 'updateProfile') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email', 'updateProfile')<div class="invalid-feedback" id="email-error">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary" type="submit">Salvar dados</button>
                </form>
            </article>

            <article class="profile-card" aria-labelledby="profile-password-title">
                <h2 id="profile-password-title">Alterar senha</h2>
                <p>Confirme sua senha atual antes de definir uma nova.</p>

                <form method="POST" action="{{ route('profile.password.update') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="current_password">Senha atual</label>
                        <input class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" type="password" autocomplete="current-password" required @error('current_password', 'updatePassword') aria-invalid="true" aria-describedby="current-password-error" @enderror>
                        @error('current_password', 'updatePassword')<div class="invalid-feedback" id="current-password-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Nova senha</label>
                        <input class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" type="password" minlength="8" autocomplete="new-password" required @error('password', 'updatePassword') aria-invalid="true" aria-describedby="password-error" @enderror>
                        @error('password', 'updatePassword')<div class="invalid-feedback" id="password-error">{{ $message }}</div>@enderror
                        @unless($errors->updatePassword->has('password'))<div class="form-text">Use pelo menos 8 caracteres.</div>@endunless
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Confirmar nova senha</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" required>
                    </div>

                    <button class="btn btn-primary" type="submit">Atualizar senha</button>
                </form>
            </article>

            <article class="profile-card profile-danger" aria-labelledby="profile-delete-title">
                <h2 id="profile-delete-title">Excluir conta</h2>
                <p>Esta ação é permanente e também excluirá todas as suas metas financeiras. Os conteúdos educacionais públicos permanecem na plataforma.</p>

                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Excluir sua conta permanentemente? Esta ação não pode ser desfeita.')" novalidate>
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label class="form-label" for="delete_password">Senha atual para confirmar</label>
                        <input class="form-control @error('password', 'deleteAccount') is-invalid @enderror" id="delete_password" name="password" type="password" autocomplete="current-password" required @error('password', 'deleteAccount') aria-invalid="true" aria-describedby="delete-password-error" @enderror>
                        @error('password', 'deleteAccount')<div class="invalid-feedback" id="delete-password-error">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-outline-danger" type="submit">Excluir minha conta</button>
                </form>
            </article>
        </div>
    </section>
@endsection
