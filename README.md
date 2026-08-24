# Invest

A Invest é uma plataforma web de educação financeira voltada principalmente a iniciantes. Seu objetivo é reunir conteúdos e ferramentas que ajudem o usuário a compreender finanças pessoais e investimentos sem oferecer recomendações individualizadas.

## Tecnologias

- PHP 8.2.12
- Laravel Framework 12.67.0
- MySQL; o ambiente local possui MariaDB 10.4.32 do XAMPP, compatível com o protocolo MySQL
- Bootstrap 5.3.8
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

**Invest v0.3 — Autenticação e Dashboard**

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
- limitação de tentativas excessivas de login.

Ainda não implementado:

- módulos educacionais e catálogo funcional de investimentos;
- simulador e planejamento financeiro;
- tabelas e regras de negócio específicas da Invest;
- integrações com dados externos.
