# Invest

A Invest é uma plataforma web de educação financeira voltada principalmente a iniciantes. Seu objetivo é reunir conteúdos e ferramentas que ajudem o usuário a compreender finanças pessoais e investimentos sem oferecer recomendações individualizadas.

## Tecnologias

- PHP 8.2.12
- Laravel Framework 12.67.0
- MySQL; o ambiente local possui MariaDB 10.4.32 do XAMPP, compatível com o protocolo MySQL
- Bootstrap 5.3.8
- Chart.js 4.5.1
- JavaScript ES6+
- Vite 7.3.6
- Node.js 22.16.0 e npm 10.9.2

## Requisitos

- PHP 8.2 ou superior, com as extensões exigidas pelo Laravel
- Composer 2
- MySQL ou MariaDB compatível
- Node.js e npm

Neste ambiente, PHP e o cliente MySQL/MariaDB são fornecidos pelo XAMPP em `C:\xampp`. Se esses executáveis não estiverem no `PATH`, utilize os caminhos completos ou adicione-os ao `PATH` do terminal.

## Instalação

Instale as dependências PHP:

```bash
composer install
```

Crie o arquivo de ambiente a partir do exemplo. No Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Em sistemas Unix:

```bash
cp .env.example .env
```

Gere a chave local da aplicação e instale os pacotes front-end:

```bash
php artisan key:generate
npm install
```

Crie no MySQL um banco chamado `invest`. Depois, informe no `.env` as credenciais reais do seu ambiente:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invest
DB_USERNAME=
DB_PASSWORD=
```

Não versione o `.env` nem credenciais reais. Com o banco configurado, execute:

```bash
php artisan migrate
```

Para recriar o banco local com as categorias, fontes e conteúdos educacionais demonstrativos:

```bash
php artisan migrate:fresh --seed
```

Esse comando apaga os dados existentes no banco configurado antes de recriar as tabelas. Use-o somente em um ambiente local descartável.

Para desenvolvimento, mantenha dois terminais abertos:

```bash
php artisan serve
```

```bash
npm run dev
```

Para gerar os assets de produção:

```bash
npm run build
```

## Testes

```bash
php artisan test
```

Os testes automatizados utilizam SQLite apenas em memória e isoladamente. A aplicação local e os ambientes implantados estão configurados para MySQL.

## Status

**Invest v0.6 — Simulador Educacional**

Disponível nesta versão:

- aplicação Laravel com rota pública `/`;
- landing page da v0.1 migrada para Blade;
- layout global, navbar e footer reutilizáveis;
- Bootstrap integrado ao Vite pelo npm;
- assets CSS e JavaScript organizados em `resources`;
- configuração preparada para MySQL;
- migrations padrão de usuários, cache e filas;
- Model `User` padrão utilizado pela autenticação;
- cadastro com validação e normalização de dados;
- login, opção “Lembrar de mim” e logout seguro;
- sessão autenticada e dashboard protegido;
- navbar adaptada para visitantes e usuários autenticados;
- limitação de tentativas excessivas de login;
- área pública `Aprender`, com filtros por categoria;
- categorias e conteúdos educacionais determinísticos;
- páginas individuais com Markdown convertido de forma segura;
- fontes e referências institucionais associadas a cada conteúdo publicado;
- conteúdos relacionados da mesma categoria;
- seeders educacionais para um ambiente demonstrável.
- catálogo público de modalidades de investimento;
- categorias e filtros combináveis por categoria e risco;
- páginas individuais com funcionamento, risco, liquidez e rentabilidade conceitual;
- classificação didática de risco sempre acompanhada de texto explicativo;
- fontes oficiais associadas pelo relacionamento `investment ↔ source`;
- integração do catálogo com landing page, navbar, footer e dashboard.
- simulador público de crescimento de capital com parâmetros definidos pelo usuário;
- cálculo de juros compostos e aportes mensais ao final de cada período;
- resumo textual com total investido, saldo e rendimento estimados;
- gráfico de evolução mensal com total investido e saldo estimado;
- contexto educacional opcional por modalidade publicada, sem preenchimento automático da taxa.

## Hipóteses do simulador

O simulador aplica somente matemática financeira aos parâmetros informados pelo usuário. A taxa anual hipotética `r` é convertida para a taxa mensal equivalente:

```text
i = (1 + r)^(1/12) - 1
```

O valor inicial evolui por `PV(1+i)^n`. Os aportes são considerados ao final de cada mês e, quando `i` é diferente de zero, evoluem por `PMT × [((1+i)^n - 1) / i]`. Na taxa zero, os aportes são simplesmente multiplicados pelo número de meses.

Os valores apresentados são brutos. A simulação não considera impostos, tarifas, inflação, custos operacionais ou regras específicas de produtos. Não utiliza Selic, CDI, IPCA, cotações, APIs ou qualquer previsão de mercado, e não representa garantia de rentabilidade ou recomendação de investimento.

## Política editorial

Todo conteúdo financeiro publicado deve possuir ao menos uma fonte confiável associada. Os conteúdos iniciais foram sintetizados em linguagem própria a partir de materiais oficiais do Banco Central do Brasil, Comissão de Valores Mobiliários, Tesouro Direto e Fundo Garantidor de Créditos, com registro da data de acesso. As áreas `Aprender` e `Investimentos` são públicas e possuem finalidade exclusivamente educacional e informativa.

A classificação de risco apresenta características gerais e não substitui a avaliação das condições específicas de cada produto. Ela não representa suitability, recomendação ou perfil de investidor.

Ainda não implementado:

- painel administrativo e edição de conteúdos;
- progresso, favoritos, quizzes e recursos personalizados de aprendizagem;
- comparador, carteira e acompanhamento de investimentos do usuário;
- histórico de simulações e planejamento financeiro;
- tabelas e regras de negócio específicas da Invest;
- integrações com dados externos.
