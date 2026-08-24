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

**Invest v1.0 — Progresso educacional, favoritos e administração de conteúdo**

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
- progresso educacional privado, com marcação reversível, filtros e resumo acessível no dashboard;
- favoritos privados de investimentos, com filtro combinável por categoria e risco;
- painel administrativo protegido para conteúdos, investimentos e fontes;
- publicação condicionada à associação de ao menos uma fonte oficial;
- exclusão de fontes vinculadas bloqueada para preservar a integridade editorial;
- permissões administrativas explícitas, sem criação automática de administrador.

## Administração

Nenhum usuário administrativo é criado por seed, cadastro ou configuração fixa. Após criar uma conta normalmente, promova-a manualmente em um ambiente controlado:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'email-do-administrador@example.com')->firstOrFail();
$user->forceFill(['is_admin' => true])->save();
```

O campo `is_admin` não é preenchível em massa e não é aceito pelos formulários de cadastro ou perfil. A área `/admin` exige autenticação e autorização administrativa; usuários comuns recebem HTTP 403.

Conteúdos e investimentos em rascunho podem ser salvos sem fontes. Para publicar, o servidor exige pelo menos uma fonte existente. A remoção de uma fonte já vinculada a qualquer conteúdo ou investimento é recusada com uma mensagem clara. Títulos novos geram slugs automaticamente; conflitos recebem sufixos numéricos previsíveis.

Progresso e favoritos pertencem exclusivamente ao usuário autenticado. Progresso representa somente a conclusão de conteúdos publicados; favoritos são itens salvos para consulta posterior e não constituem carteira, recomendação ou acompanhamento de posição.

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

## Deploy temporário no Railway

Este é um fluxo de demonstração em uma única instância. O Railway utiliza **Railpack** para detectar o `composer.json` e o arquivo `artisan`: ele instala as dependências PHP, detecta o `package.json`, instala as dependências npm, executa o script `npm run build`, configura `public/` como document root e inicia o Laravel com FrankenPHP na porta fornecida pela plataforma. Não é necessário configurar Docker, Procfile, `railway.json`, build command ou start command.

O servidor detectado pelo Railpack já escuta no endereço e na variável `PORT` exigidos pelo Railway. Não defina uma porta fixa e não crie manualmente a variável `PORT`.

### 1. Criar os serviços

1. No Railway, crie um projeto vazio.
2. Adicione um serviço a partir do repositório GitHub da Invest.
3. No mesmo projeto, adicione o template oficial **MySQL**. Não escolha PostgreSQL.
4. No serviço da aplicação, gere um domínio em **Settings → Networking → Public Networking**.
5. Mantenha uma única réplica para esta demonstração temporária.

### 2. Gerar a APP_KEY

No computador local, execute:

```bash
php artisan key:generate --show
```

Copie o valor completo retornado, começando por `base64:`, para a variável `APP_KEY` do serviço da aplicação. Não coloque essa chave no GitHub, no README ou no `.env.example`.

### 3. Configurar variáveis

Adicione estas variáveis no serviço da aplicação:

```dotenv
APP_NAME=Invest
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dominio-gerado.up.railway.app
APP_KEY=base64:CHAVE_GERADA_FORA_DO_REPOSITORIO
APP_TIMEZONE=America/Sao_Paulo
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
CACHE_STORE=file
QUEUE_CONNECTION=sync

TRUSTED_PROXIES=*
RAILPACK_NODE_VERSION=22
RAILPACK_SKIP_MIGRATIONS=true
```

`MySQL` deve ser substituído pelo nome exato do serviço de banco caso ele tenha sido renomeado. Use referências de variáveis do Railway, não copie credenciais para o repositório. O mapeamento é direto: `MYSQLHOST` para `DB_HOST`, `MYSQLPORT` para `DB_PORT`, `MYSQLDATABASE` para `DB_DATABASE`, `MYSQLUSER` para `DB_USERNAME` e `MYSQLPASSWORD` para `DB_PASSWORD`.

Depois que o domínio público for gerado, ajuste `APP_URL` para a URL HTTPS exata. `TRUSTED_PROXIES=*` é definido apenas no Railway: o serviço não fica diretamente exposto, e essa configuração permite que o Laravel reconheça os headers `X-Forwarded-*` enviados pelo proxy da plataforma. Localmente, a variável permanece vazia e o comportamento anterior é preservado.

`SESSION_DRIVER=file` e `CACHE_STORE=file` são suficientes para uma única instância temporária. Sessões podem ser perdidas em reinícios ou novos deploys porque o filesystem do serviço é efêmero; os dados importantes — usuários, metas, progresso, favoritos, conteúdos, investimentos e fontes — permanecem no MySQL. Não há uploads persistentes nesta versão.

### 4. Configurar build, pre-deploy e health check

Deixe **Build Command** e **Start Command** vazios para usar a detecção do Railpack. Em **Pre-deploy Command**, configure:

```bash
php artisan migrate --force && php artisan db:seed --force
```

`RAILPACK_SKIP_MIGRATIONS=true` evita que o startup automático repita migrations e seed. O pre-deploy executa depois do build e antes de disponibilizar a nova versão; se falhar, o deployment não prossegue. Nunca use `migrate:fresh`.

Configure o **Healthcheck Path** como:

```text
/health
```

O endpoint retorna somente `{"status":"ok"}` e não consulta o banco. O Railpack também executa as otimizações do Laravel e serve os arquivos gerados pelo Vite em `public/build`.

### 5. Validar a demonstração

Após o deployment ficar ativo:

1. abra `https://DOMINIO/health` e confirme HTTP 200 com `{"status":"ok"}`;
2. teste `/`, `/aprender`, `/investimentos`, `/simulador`, `/login` e `/register`;
3. cadastre um usuário pela interface;
4. valide dashboard, progresso, favoritos, planejamento, perfil, logout e uma nova sessão;
5. confirme no navegador que CSS, Bootstrap, JavaScript, Chart.js e o menu móvel carregam sem erros 404;
6. confirme que a navegação permanece em HTTPS e que formulários com CSRF funcionam.

Para uma demonstração somente como usuário comum, nenhum passo adicional é necessário. Para testar a administração, instale e autentique a Railway CLI, vincule o projeto e entre no container em execução:

```bash
railway ssh
php artisan tinker
```

No Tinker, promova a conta criada pela interface:

```php
$user = App\Models\User::where('email', 'EMAIL_DA_CONTA')->firstOrFail();
$user->forceFill(['is_admin' => true])->save();
```

Não crie usuário ou administrador por seed. Depois, acesse `/admin` e valide conteúdos, investimentos e fontes.

### 6. Encerrar depois da apresentação

Se os dados precisarem ser preservados, produza um backup antes de remover serviços. Para interromper custos e acesso público após a demonstração, remova o serviço da aplicação e o serviço MySQL — ou exclua integralmente o projeto temporário pelo painel do Railway. A remoção do banco elimina usuários e demais dados persistidos e deve ser tratada como definitiva.

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

- quizzes e outros recursos personalizados de aprendizagem;
- comparador, carteira e acompanhamento de investimentos do usuário;
- histórico de simulações e histórico detalhado de aportes em metas;
- orçamento doméstico, receitas, despesas e transações;
- integrações com dados externos.
