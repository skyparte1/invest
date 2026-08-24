<footer class="site-footer">
    <div class="container">
        <div class="row gy-4 align-items-start">
            <div class="col-lg-4">
                <a class="navbar-brand footer-brand" href="{{ url('/#inicio') }}"><span class="brand-mark" aria-hidden="true">I</span>Invest</a>
                <p>Educação financeira clara para decisões mais conscientes.</p>
            </div>
            <div class="col-lg-8">
                <nav class="footer-nav" aria-label="Navegação do rodapé">
                    <a href="{{ url('/#inicio') }}">Sobre</a><a href="{{ route('learn.index') }}">Educação financeira</a><a href="{{ url('/#investimentos') }}">Investimentos</a><a href="#">Termos</a><a href="#">Privacidade</a><a href="#academic-note">Projeto acadêmico</a>
                </nav>
            </div>
        </div>
        <div class="footer-bottom" id="academic-note">
            <small>Projeto acadêmico desenvolvido para Trabalho de Conclusão de Curso.</small>
            <small>&copy; <span id="current-year">{{ date('Y') }}</span> Invest.</small>
        </div>
    </div>
</footer>
