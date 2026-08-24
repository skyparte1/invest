@extends('layouts.app')

@section('content')
    <section class="hero" id="inicio">
        <div class="container"><div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="eyebrow">Educação financeira descomplicada</span>
                <h1>Aprenda a investir com mais consciência.</h1>
                <p class="hero-lead">Educação financeira, investimentos e ferramentas práticas em um único lugar.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 mt-4"><a class="btn btn-primary btn-lg" href="{{ auth()->check() ? route('dashboard') : route('register') }}">Começar agora</a><a class="btn btn-outline-primary btn-lg" href="#como-funciona">Conhecer a plataforma</a></div>
                <p class="hero-note">Conhecimento para apoiar suas escolhas, no seu ritmo e sem complicação.</p>
            </div>
            <div class="col-lg-5" aria-hidden="true">
                <div class="hero-visual">
                    <div class="visual-card visual-card-main"><span class="visual-label">Sua jornada</span><strong>Conhecimento que cresce com você</strong><div class="progress-steps"><span class="active"></span><span class="active"></span><span></span><span></span></div></div>
                    <div class="visual-card visual-card-small"><span class="visual-icon">✓</span><span><strong>Passo a passo</strong><small>Conteúdo claro e prático</small></span></div>
                    <div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div>
                </div>
            </div>
        </div></div>
    </section>

    <section class="section" id="aprender" aria-labelledby="beneficios-title">
        <div class="container">
            <div class="section-heading text-center"><span class="eyebrow">Tudo em um só lugar</span><h2 id="beneficios-title">Construa uma base financeira mais sólida</h2><p>Do primeiro conceito ao planejamento dos seus objetivos, a Invest acompanha cada etapa do aprendizado.</p></div>
            <div class="row g-4">
                <div class="col-sm-6 col-lg-3"><article class="benefit-card h-100"><span class="card-icon" aria-hidden="true">01</span><h3>Aprenda</h3><p>Conteúdos de educação financeira desenvolvidos para iniciantes.</p></article></div>
                <div class="col-sm-6 col-lg-3"><article class="benefit-card h-100"><span class="card-icon" aria-hidden="true">02</span><h3>Conheça</h3><p>Entenda diferentes modalidades de investimento, riscos e características.</p></article></div>
                <div class="col-sm-6 col-lg-3"><article class="benefit-card h-100"><span class="card-icon" aria-hidden="true">03</span><h3>Simule</h3><p>Visualize cenários hipotéticos de investimento por meio de ferramentas interativas.</p></article></div>
                <div class="col-sm-6 col-lg-3"><article class="benefit-card h-100"><span class="card-icon" aria-hidden="true">04</span><h3>Planeje</h3><p>Organize sua vida financeira e estabeleça objetivos.</p></article></div>
            </div>
        </div>
    </section>

    <section class="section section-soft" id="como-funciona" aria-labelledby="steps-title">
        <div class="container">
            <div class="section-heading"><span class="eyebrow">Como funciona</span><h2 id="steps-title">Uma jornada simples, feita para você avançar</h2></div>
            <ol class="journey row g-4 list-unstyled">
                <li class="col-md-6 col-lg-3"><div class="journey-step"><span>1</span><div><h3>Aprenda</h3><p>Comece pelos conceitos essenciais.</p></div></div></li>
                <li class="col-md-6 col-lg-3"><div class="journey-step"><span>2</span><div><h3>Conheça</h3><p>Explore opções e características.</p></div></div></li>
                <li class="col-md-6 col-lg-3"><div class="journey-step"><span>3</span><div><h3>Simule</h3><p>Observe cenários hipotéticos.</p></div></div></li>
                <li class="col-md-6 col-lg-3"><div class="journey-step"><span>4</span><div><h3>Planeje</h3><p>Transforme conhecimento em objetivos.</p></div></div></li>
            </ol>
        </div>
    </section>

    <section class="section" id="investimentos" aria-labelledby="investments-title">
        <div class="container">
            <div class="row align-items-end mb-5 g-3"><div class="col-lg-7"><span class="eyebrow">Explore possibilidades</span><h2 id="investments-title">Conheça diferentes tipos de investimento</h2></div><div class="col-lg-5"><p class="section-intro">Conteúdos objetivos para ajudar você a compreender como cada modalidade funciona.</p></div></div>
            <div class="investment-grid">
                <article class="investment-card"><span>Renda fixa</span><h3>Poupança</h3><p>Conheça suas principais características.</p></article>
                <article class="investment-card"><span>Renda fixa</span><h3>Tesouro Selic</h3><p>Entenda o funcionamento desta modalidade.</p></article>
                <article class="investment-card"><span>Renda fixa</span><h3>CDB</h3><p>Aprenda os conceitos básicos desse investimento.</p></article>
                <article class="investment-card"><span>Renda fixa</span><h3>LCI/LCA</h3><p>Explore características e pontos de atenção.</p></article>
                <article class="investment-card"><span>Fundos</span><h3>Fundos de investimento</h3><p>Compreenda sua estrutura e funcionamento.</p></article>
                <article class="investment-card"><span>Renda variável</span><h3>Fundos imobiliários</h3><p>Conheça seus conceitos essenciais.</p></article>
                <article class="investment-card"><span>Renda variável</span><h3>Ações</h3><p>Descubra os fundamentos desta modalidade.</p></article>
            </div>
        </div>
    </section>

    <section class="section pt-0" aria-labelledby="notice-title"><div class="container"><div class="education-notice"><span class="notice-symbol" aria-hidden="true">i</span><div><h2 id="notice-title">Informação para escolhas mais conscientes</h2><p>A Invest é uma plataforma de caráter educacional e informativo. As informações e simulações apresentadas não representam recomendação de investimento ou garantia de rentabilidade.</p></div></div></div></section>
    <section class="cta-section" id="planejamento" aria-labelledby="cta-title"><div class="container text-center" id="conta"><span class="eyebrow eyebrow-light">Seu próximo passo</span><h2 id="cta-title">Comece sua jornada financeira.</h2><p>Aprenda conceitos, explore investimentos e desenvolva uma relação mais consciente com o seu dinheiro.</p><a class="btn btn-light btn-lg" href="{{ auth()->check() ? route('dashboard') : route('register') }}">Criar conta gratuitamente</a></div></section>
@endsection
