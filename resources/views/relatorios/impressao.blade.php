<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório FinanceFlow</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 40px;
        }

        .acoes {
            margin-bottom: 24px;
        }

        .btn {
            padding: 8px 14px;
            border: 1px solid #111827;
            background: #fff;
            cursor: pointer;
            border-radius: 6px;
            margin-right: 8px;
        }

        h1 {
            margin-bottom: 4px;
        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 24px 0;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
        }

        .label {
            font-size: 12px;
            color: #6b7280;
            margin: 0 0 6px;
        }

        .value {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .success {
            color: #166534;
        }

        .danger {
            color: #991b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            font-size: 13px;
        }

        th, td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f9fafb;
        }

        .right {
            text-align: right;
        }

        @media print {
            .acoes {
                display: none;
            }

            body {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<div class="acoes">
    <button class="btn" onclick="window.print()">Salvar como PDF</button>
    <button class="btn" onclick="history.back()">Voltar</button>
</div>

<h1>Relatório FinanceFlow</h1>
<p class="muted">
    Tipo: {{ ucfirst($tipo) }} · Gerado em {{ now()->format('d/m/Y H:i') }}
</p>

<div class="cards">
    <div class="card">
        <p class="label">Receitas</p>
        <p class="value success">R$ {{ number_format($resumo['receitas'], 2, ',', '.') }}</p>
    </div>

    <div class="card">
        <p class="label">Despesas</p>
        <p class="value danger">R$ {{ number_format($resumo['despesas'], 2, ',', '.') }}</p>
    </div>

    <div class="card">
        <p class="label">Saldo</p>
        <p class="value {{ $resumo['saldo'] >= 0 ? 'success' : 'danger' }}">
            R$ {{ number_format($resumo['saldo'], 2, ',', '.') }}
        </p>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th>Tipo</th>
            <th>Categoria</th>
            <th>Conta</th>
            <th class="right">Valor</th>
        </tr>
    </thead>

    <tbody>
        @forelse($movimentacoes as $m)
            <tr>
                <td>{{ \Carbon\Carbon::parse($m['data'])->format('d/m/Y') }}</td>
                <td>{{ $m['descricao'] }}</td>
                <td>{{ ucfirst($m['tipo']) }}</td>
                <td>{{ $m['categoria']['nome'] ?? '-' }}</td>
                <td>{{ $m['conta']['nome'] ?? '-' }}</td>
                <td class="right">
                    R$ {{ number_format(abs($m['valor']), 2, ',', '.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Nenhuma movimentação encontrada.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>