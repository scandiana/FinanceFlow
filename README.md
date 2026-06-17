# FinanceFlow

Sistema web de gestão financeira empresarial desenvolvido em Laravel, projetado para centralizar o controle de receitas, despesas, contas bancárias, cartões, clientes e relatórios gerenciais.

O projeto foi construído seguindo o padrão MVC e possui uma arquitetura preparada para integração com banco de dados MySQL, APIs REST e futuras regras de negócio.

---

## Visão Geral

O FinanceFlow tem como objetivo fornecer uma interface moderna para acompanhamento financeiro corporativo, permitindo:

* Controle de fluxo de caixa
* Gestão de contas bancárias
* Gestão de cartões
* Cadastro de clientes
* Organização por categorias financeiras
* Relatórios gerenciais
* Dashboard consolidado de indicadores

---

## Tecnologias Utilizadas

### Backend

* PHP 8.3
* Laravel 13
* Eloquent ORM
* API REST

### Frontend

* Blade Templates
* JavaScript
* Chart.js
* CSS Responsivo

### Banco de Dados

* MySQL

### Arquitetura

* MVC (Model / View / Controller)
* Controllers
* Models
* Migrations
* API Layer

---

## Funcionalidades

### Dashboard

* Resumo financeiro
* Indicadores principais
* Gráficos de receitas e despesas
* Alertas financeiros
* Próximos vencimentos

### Fluxo de Caixa

* Registro de movimentações
* Filtros por período
* Busca por descrição
* Controle de receitas e despesas

### Contas Bancárias

* Cadastro de contas
* Histórico de movimentações
* Consulta de saldo
* Transferências

### Cartões

* Controle de cartões
* Compras registradas
* Parcelamentos
* Faturas

### Clientes

* Cadastro
* Consulta
* Histórico financeiro

### Categorias

* Organização de receitas
* Organização de despesas

### Relatórios

* Fluxo consolidado
* Receitas por período
* Despesas por período
* Exportação futura

---

## Estrutura do Projeto

```text
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
├── Data/
├── Providers/

database/
├── migrations/
├── seeders/

resources/
├── views/
├── css/
└── js/

routes/
├── web.php
└── api.php
```

---

## Instalação

### Pré-requisitos

* PHP 8.3+
* Composer
* MySQL
* Node.js (opcional para build de assets)

### Passos

```bash
git clone <repositorio>

cd FinanceFlow

composer install

cp .env.example .env

php artisan key:generate
```

Configure o banco de dados no arquivo `.env`.

Execute as migrations:

```bash
php artisan migrate
```

Inicie o servidor:

```bash
php artisan serve
```

Acesse:

```text
http://127.0.0.1:8000
```

---

## API

O projeto possui estrutura preparada para APIs REST.

Principais recursos:

* Transactions
* Categories
* Clients
* Cards
* Bank Accounts

As rotas encontram-se em:

```text
routes/api.php
```

---

## Status do Projeto

### Implementado

* Estrutura Laravel
* Models
* Migrations
* Dashboard
* Fluxo de Caixa
* Clientes
* Categorias
* Cartões
* Contas Bancárias
* Relatórios

### Em Desenvolvimento

* Autenticação
* Controle de permissões
* Exportação de PDF
* Integração completa com banco
* Regras de negócio avançadas

---

## Roadmap

### Versão 1.0

* Autenticação
* Controle de usuários
* Integração total com banco

### Versão 1.1

* Exportação PDF
* Dashboard avançado
* Indicadores financeiros

---

## Licença

Projeto acadêmico e demonstrativo desenvolvido para fins educacionais e de prototipação.

---
