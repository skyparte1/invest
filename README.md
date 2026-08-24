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

Não versione o `.env` nem credenciais reais. Com o banco configurado, crie as tabelas e carregue o conteúdo público inicial:

```bash
php artisan migrate
php artisan db:seed
```

`php artisan db:seed` pode ser executado novamente com segurança: os seeders atualizam categorias, conteúdos, fontes, investimentos e relacionamentos existentes sem duplicá-los. Eles não criam usuários.

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

**Invest v0.9 — Fechamento técnico do MVP**

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
- módulo privado de planejamento financeiro por metas;
- criação, edição e exclusão de metas pertencentes ao usuário autenticado;
- valor-alvo e valor acumulado persistidos como valores decimais;
- progresso, valor restante, prazo e valor mensal de referência calculados no servidor;
- isolamento de metas por usuário com Policy e consultas pelo relacionamento autenticado.
- perfil privado para atualização normalizada de nome e e-mail;
- troca de senha protegida pela confirmação da senha atual;
- exclusão permanente da conta, com encerramento da sessão e remoção em cascata das metas pessoais;
- dashboard consolidado com acesso aos quatro módulos e resumo das metas ativas;
- navegação adaptada a visitantes e usuários autenticados, com indicação acessível da página atual;
- mensagens de sucesso padronizadas e páginas próprias para erros 403 e 404;
- landing page conectada a todos os módulos disponíveis no MVP.
- health check público e mínimo em `/health`;
- headers HTTP de segurança compatíveis com os recursos atuais;
- seeders públicos idempotentes, cobertos por teste de execução dupla;
- pipeline de integração contínua com PHP 8.2, Node.js 22, PHPUnit, Pint e build do Vite;
- documentação portátil para deploy em hosts PHP/Laravel compatíveis.

## Fluxo principal para demonstração

1. Acesse a landing page e explore os módulos públicos **Aprender**, **Investimentos** e **Simulador**.
2. Crie uma conta ou entre com um usuário existente.
3. No dashboard, acesse **Planejamento** e cadastre uma meta.
4. Retorne ao dashboard para visualizar o resumo de metas ativas.
5. Abra **Perfil** para atualizar nome, e-mail ou senha.
6. Termine a sessão pelo botão **Sair**.

## Segurança e privacidade

- todas as rotas de dashboard, perfil e planejamento exigem autenticação;
- alterações sensíveis utilizam métodos HTTP apropriados e proteção CSRF do Laravel;
- a senha atual é obrigatória para troca de senha e exclusão da conta;
- senhas são armazenadas apenas com o hash gerenciado pelo Laravel;
- metas são consultadas pelo relacionamento do usuário e protegidas por Policy contra acesso indevido;
- a exclusão da conta invalida a sessão, renova o token CSRF e remove somente os dados pessoais associados;
- nome e e-mail são normalizados e validados; o e-mail permanece único;
- o `.env` e credenciais reais não são versionados.

## Deploy em produção

Este fluxo é portátil entre Render, Railway, VPS Linux e outros hosts compatíveis com PHP e Laravel. Ajuste apenas os mecanismos próprios de cada infraestrutura.

1. Clone o repositório e instale as dependências PHP sem pacotes de desenvolvimento:

```bash
composer install --no-dev --optimize-autoloader
```

2. Crie o `.env` a partir do exemplo e configure valores próprios de produção:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dominio-real
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_SECURE_COOKIE=true
```

Produção utiliza MySQL ou MariaDB. SQLite em memória é utilizado somente pela suíte automatizada de testes. Não versione o `.env`, a `APP_KEY` ou credenciais reais.

3. Gere uma chave exclusiva no ambiente de produção:

```bash
php artisan key:generate
```

4. Instale exatamente as dependências registradas no lockfile e gere os assets estáticos:

```bash
npm ci
npm run build
```

Produção utiliza somente os arquivos compilados. `npm run dev` é exclusivo do desenvolvimento.

5. Execute migrations sem interação e carregue o conteúdo público inicial:

```bash
php artisan migrate --force
php artisan db:seed --force
```

`db:seed` popula categorias, conteúdos, fontes, categorias de investimento, investimentos e seus relacionamentos. Os seeders são idempotentes e não criam usuário administrativo ou conta de demonstração. Nunca execute `migrate:fresh` em produção: esse comando apaga as tabelas e os dados existentes.

6. Gere os caches de produção:

```bash
php artisan optimize
```

O document root do Apache, Nginx ou painel da hospedagem deve apontar para a pasta `public/`, nunca para a raiz do repositório. O servidor precisa encaminhar as rotas não encontradas para `public/index.php` e usar uma versão compatível do PHP.

As pastas `storage/` e `bootstrap/cache/` precisam ser graváveis pelo usuário do processo PHP. Aplique proprietário e permissões adequados à sua infraestrutura; não utilize `chmod 777`.

HTTPS deve ser habilitado na infraestrutura. Com HTTPS, mantenha `SESSION_SECURE_COOKIE=true` para que o cookie de sessão não seja enviado em conexões inseguras. `SESSION_DRIVER=file` atende uma única instância; em múltiplas instâncias, planeje uma sessão centralizada antes de escalar. Redis não é requisito deste MVP e a fila permanece síncrona.

Para limpar caches durante diagnóstico ou antes de recriá-los:

```bash
php artisan optimize:clear
```

O endpoint `GET /health` retorna apenas `{"status":"ok"}`, não consulta o banco e não revela versões, ambiente ou infraestrutura.

### Checklist de produção

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` própria e secreta
- [ ] `APP_URL` com HTTPS
- [ ] banco MySQL/MariaDB de produção configurado
- [ ] `SESSION_SECURE_COOKIE=true` em HTTPS
- [ ] migrations executadas com `--force`
- [ ] seed público executado quando necessário
- [ ] `npm run build` concluído
- [ ] `php artisan optimize` concluído
- [ ] document root apontando para `public/`
- [ ] permissões de `storage/` e `bootstrap/cache/` configuradas
- [ ] testes aprovados
- [ ] CI verde
- [ ] backup configurado pela infraestrutura

Antes de migrations futuras potencialmente destrutivas, produza e valide um backup do banco. A aplicação não implementa backup automático; essa responsabilidade pertence à infraestrutura.

## Integração contínua

O workflow [`.github/workflows/ci.yml`](.github/workflows/ci.yml) é executado em pushes e pull requests para `main`. Ele valida o `composer.json`, instala as dependências, gera uma chave temporária, verifica o estilo com Pint, executa os testes em SQLite na memória, instala o front-end com `npm ci` e compila os assets com Vite. O pipeline não realiza deploy.

## Hipóteses do simulador

O simulador aplica somente matemática financeira aos parâmetros informados pelo usuário. A taxa anual hipotética `r` é convertida para a taxa mensal equivalente:

```text
i = (1 + r)^(1/12) - 1
```

O valor inicial evolui por `PV(1+i)^n`. Os aportes são considerados ao final de cada mês e, quando `i` é diferente de zero, evoluem por `PMT × [((1+i)^n - 1) / i]`. Na taxa zero, os aportes são simplesmente multiplicados pelo número de meses.

Os valores apresentados são brutos. A simulação não considera impostos, tarifas, inflação, custos operacionais ou regras específicas de produtos. Não utiliza Selic, CDI, IPCA, cotações, APIs ou qualquer previsão de mercado, e não representa garantia de rentabilidade ou recomendação de investimento.

## Hipóteses do planejamento

O valor mensal de referência divide o valor restante pelo número de meses-calendário disponíveis até a data-alvo, incluindo o mês atual e o mês da meta. Por exemplo, uma data no mês atual corresponde a um mês de referência; uma data no mês seguinte corresponde a dois.

Esse valor é apenas uma referência matemática de organização. O cálculo não considera rentabilidade, inflação, impostos, taxas ou imprevistos e não constitui recomendação financeira. Metas concluídas derivam do valor acumulado, metas vencidas não recebem uma referência mensal futura artificial e somente o proprietário autenticado pode acessar ou alterar seus dados. Na criação, a data-alvo deve ser hoje ou futura; na edição, qualquer data válida é aceita para que uma meta cujo prazo passou continue editável e possa ser reorganizada pelo usuário.

## Política editorial

Todo conteúdo financeiro publicado deve possuir ao menos uma fonte confiável associada. Os conteúdos iniciais foram sintetizados em linguagem própria a partir de materiais oficiais do Banco Central do Brasil, Comissão de Valores Mobiliários, Tesouro Direto e Fundo Garantidor de Créditos, com registro da data de acesso. As áreas `Aprender` e `Investimentos` são públicas e possuem finalidade exclusivamente educacional e informativa.

A classificação de risco apresenta características gerais e não substitui a avaliação das condições específicas de cada produto. Ela não representa suitability, recomendação ou perfil de investidor.

Ainda não implementado:

- painel administrativo e edição de conteúdos;
- progresso, favoritos, quizzes e recursos personalizados de aprendizagem;
- comparador, carteira e acompanhamento de investimentos do usuário;
- histórico de simulações e histórico detalhado de aportes em metas;
- orçamento doméstico, receitas, despesas e transações;
- integrações com dados externos.
