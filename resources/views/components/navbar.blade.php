<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-lg" aria-label="Navegação principal">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/#inicio') }}" aria-label="Invest — página inicial"><span class="brand-mark" aria-hidden="true">I</span>Invest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir menu de navegação"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-3 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('learn.index') }}" @if (request()->routeIs('learn.*')) aria-current="page" @endif>Aprender</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('investments.index') }}" @if (request()->routeIs('investments.*')) aria-current="page" @endif>Investimentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('simulator.index') }}" @if (request()->routeIs('simulator.*')) aria-current="page" @endif>Simulador</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('planning.index') }}" @if (request()->routeIs('planning.*')) aria-current="page" @endif>Planejamento</a></li>
                </ul>
                <div class="nav-actions d-flex flex-column flex-lg-row gap-2">
                    @guest
                        <a class="btn btn-link" href="{{ route('login') }}">Entrar</a>
                        <a class="btn btn-primary" href="{{ route('register') }}">Criar conta</a>
                    @else
                        <a class="btn btn-link" href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Dashboard</a>
                        <a class="btn btn-link" href="{{ route('profile.edit') }}" @if (request()->routeIs('profile.*')) aria-current="page" @endif>Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-primary w-100" type="submit">Sair</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>
