# FinanceFlow — Gestão Financeira Empresarial

Interface web completa (camada de apresentação) para sistema de gestão financeira, baseada no wireframe **FinanceFlow**. Desenvolvida em **Laravel** com padrão **MVC**, dados simulados e rotas funcionais — pronta para integração futura com MySQL, Models e APIs REST.

## Requisitos

- PHP 8.2+
- Composer
- Extensão PHP `fileinfo` recomendada (ou `composer install --ignore-platform-req=ext-fileinfo`)

## Instalação

```bash
composer install
cp .env.example .env   # se necessário
php artisan key:generate
php artisan serve
```

Acesse: http://127.0.0.1:8000/dashboard

## O que está implementado

- **Dashboard** — indicadores, gráficos (Chart.js), vencimentos, alertas, contas e movimentações
- **Fluxo de caixa** — listagem, filtros, busca, paginação, CRUD simulado
- **Contas bancárias** — cards, detalhes, histórico, transferências
- **Cartões** — faturas, compras, parcelamentos
- **Clientes** — CRUD e perfil com histórico financeiro
- **Categorias** — CRUD (receita/despesa)
- **Relatórios** — receitas, despesas, fluxo consolidado, exportação PDF simulada
- **Usuários** — listagem, cadastro, edição, perfis e restrições visuais simuladas

## O que NÃO está implementado (por design)

- Persistência em banco de dados
- Autenticação real
- Regras de negócio definitivas
- Geração real de PDF

## Estrutura para integração futura

```
app/
├── Data/MockData.php          # Substituir por Models + seeders
├── Http/Controllers/          # Conectar a services/repositories
└── Models/                    # (criar) User, Cliente, Conta, etc.

resources/views/
├── layouts/
├── components/
├── dashboard/
├── fluxo-caixa/
├── contas/
├── cartoes/
├── clientes/
├── categorias/
├── relatorios/
└── usuarios/

public/css/app.css
public/js/app.js
routes/web.php
```

### Nomenclatura de campos (MySQL)

`id`, `nome`, `descricao`, `valor`, `saldo`, `categoria_id`, `cliente_id`, `conta_id`, `usuario_id`, `created_at`, `updated_at`

## Simular perfis de acesso

No menu do usuário (canto superior direito), altere **Simular perfil**:

- **Administrador** — acesso total
- **Financeiro** — edição sem gestão de usuários
- **Visualização** — somente leitura (botões de edição ocultos)

## Responsividade

- Desktop: sidebar fixa
- Tablet: sidebar compacta
- Mobile: menu hambúrguer, tabelas com cards empilhados

## Licença

Projeto de demonstração / protótipo de interface.
