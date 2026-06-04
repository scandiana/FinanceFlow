<?php

namespace App\Data;

/**
 * Dados simulados para a camada de apresentação.
 * Substituir por Models Eloquent na integração futura.
 */
class MockData
{
    public static function usuarioLogado(): array
    {
        return [
            'id' => 1,
            'nome' => 'João Silva',
            'email' => 'joao.silva@financeflow.com.br',
            'perfil' => session('perfil', 'administrador'),
            'iniciais' => 'JS',
        ];
    }

    public static function perfis(): array
    {
        return [
            'administrador' => 'Administrador',
            'financeiro' => 'Financeiro',
            'visualizacao' => 'Visualização',
        ];
    }

    public static function usuarios(): array
    {
        return [
            ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao.silva@financeflow.com.br', 'perfil' => 'administrador', 'status' => 'ativo', 'created_at' => '2024-01-15'],
            ['id' => 2, 'nome' => 'Maria Santos', 'email' => 'maria.santos@financeflow.com.br', 'perfil' => 'financeiro', 'status' => 'ativo', 'created_at' => '2024-03-20'],
            ['id' => 3, 'nome' => 'Carlos Oliveira', 'email' => 'carlos.oliveira@financeflow.com.br', 'perfil' => 'financeiro', 'status' => 'ativo', 'created_at' => '2024-06-10'],
            ['id' => 4, 'nome' => 'Ana Costa', 'email' => 'ana.costa@financeflow.com.br', 'perfil' => 'visualizacao', 'status' => 'ativo', 'created_at' => '2025-01-08'],
            ['id' => 5, 'nome' => 'Pedro Lima', 'email' => 'pedro.lima@financeflow.com.br', 'perfil' => 'visualizacao', 'status' => 'inativo', 'created_at' => '2025-02-14'],
        ];
    }

    public static function usuario(int $id): ?array
    {
        return collect(self::usuarios())->firstWhere('id', $id);
    }

    public static function categorias(): array
    {
        return [
            ['id' => 1, 'nome' => 'Vendas', 'tipo' => 'receita', 'descricao' => 'Receitas de vendas de produtos e serviços', 'cor' => '#10b981'],
            ['id' => 2, 'nome' => 'Serviços', 'tipo' => 'receita', 'descricao' => 'Prestação de serviços', 'cor' => '#3b82f6'],
            ['id' => 3, 'nome' => 'Pessoal', 'tipo' => 'despesa', 'descricao' => 'Salários e encargos', 'cor' => '#ef4444'],
            ['id' => 4, 'nome' => 'Fornecedores', 'tipo' => 'despesa', 'descricao' => 'Pagamentos a fornecedores', 'cor' => '#f59e0b'],
            ['id' => 5, 'nome' => 'Infraestrutura', 'tipo' => 'despesa', 'descricao' => 'Aluguel, energia, internet', 'cor' => '#8b5cf6'],
            ['id' => 6, 'nome' => 'Tecnologia', 'tipo' => 'despesa', 'descricao' => 'Software e equipamentos', 'cor' => '#06b6d4'],
        ];
    }

    public static function categoria(int $id): ?array
    {
        return collect(self::categorias())->firstWhere('id', $id);
    }

    public static function clientes(): array
    {
        return [
            ['id' => 1, 'nome' => 'Empresa XYZ Ltda', 'documento' => '12.345.678/0001-90', 'email' => 'contato@xyz.com.br', 'telefone' => '(11) 3456-7890', 'cidade' => 'São Paulo', 'status' => 'ativo', 'total_recebido' => 125000, 'pendente' => 15000],
            ['id' => 2, 'nome' => 'Tech Solutions SA', 'documento' => '98.765.432/0001-10', 'email' => 'financeiro@techsolutions.com', 'telefone' => '(21) 2345-6789', 'cidade' => 'Rio de Janeiro', 'status' => 'ativo', 'total_recebido' => 89000, 'pendente' => 8500],
            ['id' => 3, 'nome' => 'Comércio ABC', 'documento' => '11.222.333/0001-44', 'email' => 'abc@comercio.com.br', 'telefone' => '(31) 9876-5432', 'cidade' => 'Belo Horizonte', 'status' => 'ativo', 'total_recebido' => 45000, 'pendente' => 0],
            ['id' => 4, 'nome' => 'Indústria DEF', 'documento' => '55.666.777/0001-88', 'email' => 'def@industria.com', 'telefone' => '(41) 3333-4444', 'cidade' => 'Curitiba', 'status' => 'inativo', 'total_recebido' => 32000, 'pendente' => 12000],
        ];
    }

    public static function cliente(int $id): ?array
    {
        return collect(self::clientes())->firstWhere('id', $id);
    }

    public static function contas(): array
    {
        return [
            ['id' => 1, 'nome' => 'Conta Corrente Principal', 'banco' => 'Banco do Brasil', 'agencia' => '1234-5', 'numero' => '56789-0', 'tipo' => 'corrente', 'saldo' => 125400.00],
            ['id' => 2, 'nome' => 'Conta Poupança', 'banco' => 'Itaú', 'agencia' => '0987', 'numero' => '12345-6', 'tipo' => 'poupanca', 'saldo' => 45000.00],
            ['id' => 3, 'nome' => 'Conta Internacional', 'banco' => 'Santander', 'agencia' => '4567', 'numero' => '89012-3', 'tipo' => 'corrente', 'saldo' => 18900.00],
        ];
    }

    public static function conta(int $id): ?array
    {
        return collect(self::contas())->firstWhere('id', $id);
    }

    public static function cartoes(): array
    {
        return [
            ['id' => 1, 'nome' => 'Cartão Corporativo', 'bandeira' => 'Visa', 'final' => '4532', 'limite' => 50000, 'fatura_atual' => 12400, 'vencimento' => '2026-06-05', 'status' => 'ativo'],
            ['id' => 2, 'nome' => 'Cartão Compras', 'bandeira' => 'Mastercard', 'final' => '8871', 'limite' => 30000, 'fatura_atual' => 5680, 'vencimento' => '2026-06-12', 'status' => 'ativo'],
            ['id' => 3, 'nome' => 'Cartão Viagens', 'bandeira' => 'Amex', 'final' => '2209', 'limite' => 20000, 'fatura_atual' => 3200, 'vencimento' => '2026-06-18', 'status' => 'ativo'],
        ];
    }

    public static function cartao(int $id): ?array
    {
        return collect(self::cartoes())->firstWhere('id', $id);
    }

    public static function movimentacoes(): array
    {
        return MovimentacaoStore::all()->all();
    }

    public static function movimentacao(string|int $id): ?array
    {
        return MovimentacaoStore::find($id);
    }

    public static function movimentacoesConta(int $contaId): array
    {
        return MovimentacaoStore::filtrar(new \Illuminate\Http\Request(['conta_id' => $contaId]))->all();
    }

    public static function comprasCartao(int $cartaoId): array
    {
        $all = [
            1 => [
                ['id' => 1, 'data' => '2026-05-28', 'descricao' => 'Passagens Aéreas', 'valor' => 4200, 'parcelas' => 1],
                ['id' => 2, 'data' => '2026-05-20', 'descricao' => 'Equipamentos TI', 'valor' => 5600, 'parcelas' => 3],
                ['id' => 3, 'data' => '2026-05-15', 'descricao' => 'Hospedagem Evento', 'valor' => 2600, 'parcelas' => 1],
            ],
            2 => [
                ['id' => 4, 'data' => '2026-05-25', 'descricao' => 'Material Escritório', 'valor' => 890, 'parcelas' => 1],
                ['id' => 5, 'data' => '2026-05-18', 'descricao' => 'Software Licenças', 'valor' => 4790, 'parcelas' => 2],
            ],
            3 => [
                ['id' => 6, 'data' => '2026-05-22', 'descricao' => 'Viagem Comercial', 'valor' => 3200, 'parcelas' => 1],
            ],
        ];

        return $all[$cartaoId] ?? [];
    }

    public static function pagamentosCliente(int $clienteId): array
    {
        return MovimentacaoStore::filtrar(new \Illuminate\Http\Request())
            ->filter(fn ($m) => ($m['cliente_id'] ?? null) === $clienteId && $m['tipo'] === 'receita')
            ->values()
            ->all();
    }

    public static function vencimentos(): array
    {
        return [
            ['id' => 1, 'descricao' => 'Fatura Cartão Corporativo', 'valor' => 12400, 'data_vencimento' => '2026-06-05', 'status' => 'pendente', 'tipo' => 'cartao'],
            ['id' => 2, 'descricao' => 'Fornecedor DEF', 'valor' => 7800, 'data_vencimento' => '2026-06-07', 'status' => 'pendente', 'tipo' => 'despesa'],
            ['id' => 3, 'descricao' => 'Software Assinatura', 'valor' => 890, 'data_vencimento' => '2026-06-10', 'status' => 'agendado', 'tipo' => 'despesa'],
        ];
    }

    public static function alertas(): array
    {
        return [
            ['id' => 1, 'tipo' => 'warning', 'titulo' => '3 vencimentos próximos', 'mensagem' => 'Você tem R$ 21.090,00 em pagamentos vencendo nos próximos 7 dias.', 'link' => 'fluxo-caixa.index'],
            ['id' => 2, 'tipo' => 'info', 'titulo' => 'Meta de receita', 'mensagem' => 'Você atingiu 78% da meta de receitas do mês.', 'link' => 'relatorios.receitas'],
            ['id' => 3, 'tipo' => 'danger', 'titulo' => 'Saldo baixo', 'mensagem' => 'Conta Internacional com saldo abaixo do limite configurado.', 'link' => 'contas.show', 'params' => ['conta' => 3]],
        ];
    }

    public static function graficoReceitasDespesas(): array
    {
        return MovimentacaoStore::graficoReceitasDespesas();
    }

    public static function graficoFluxoCaixa(): array
    {
        $grafico = self::graficoReceitasDespesas();
        $acumulado = 0;

        return collect($grafico)->map(function ($m) use (&$acumulado) {
            $acumulado += $m['receitas'] - $m['despesas'];

            return ['mes' => $m['mes'], 'saldo' => $acumulado];
        })->all();
    }

    public static function dashboardResumo(): array
    {
        $mes = MovimentacaoStore::resumoMesAtual();
        $resultado = $mes['saldo'];
        $margem = $mes['receitas'] > 0 ? round(($resultado / $mes['receitas']) * 100, 1) : 0;

        return [
            'saldo_total' => collect(self::contas())->sum('saldo'),
            'receitas_mes' => $mes['receitas'],
            'despesas_mes' => $mes['despesas'],
            'resultado' => $resultado,
            'margem' => $margem,
            'variacao_saldo' => 12.5,
            'variacao_receitas' => 8.3,
            'variacao_despesas' => 5.2,
        ];
    }

    public static function formatMoney(float $value): string
    {
        return 'R$ '.number_format($value, 2, ',', '.');
    }

    public static function formatDate(string $date): string
    {
        return \Carbon\Carbon::parse($date)->format('d/m/Y');
    }

    public static function podeEditar(): bool
    {
        return in_array(session('perfil', 'administrador'), ['administrador', 'financeiro'], true);
    }

    public static function podeAdmin(): bool
    {
        return session('perfil', 'administrador') === 'administrador';
    }
}
