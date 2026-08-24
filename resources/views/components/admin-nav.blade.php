<nav class="nav nav-pills gap-2 mb-4" aria-label="Administração">
    <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">Resumo</a>
    <a class="nav-link @if(request()->routeIs('admin.conteudos.*')) active @endif" href="{{ route('admin.conteudos.index') }}">Conteúdos</a>
    <a class="nav-link @if(request()->routeIs('admin.investimentos.*')) active @endif" href="{{ route('admin.investimentos.index') }}">Investimentos</a>
    <a class="nav-link @if(request()->routeIs('admin.fontes.*')) active @endif" href="{{ route('admin.fontes.index') }}">Fontes</a>
</nav>
