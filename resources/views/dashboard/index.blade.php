@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Visão geral da sua gestão financeira</p>
    </div>
</div>

@php
    $saldoTotal = collect($contas)->sum('saldo');
    $receitas = $resumo['receitas'] ?? 0;
    $despesas = $resumo['despesas'] ?? 0;
    $saldo = $resumo['saldo'] ?? 0;
@endphp

<div class="grid grid-4" style="margin-bottom:1.5rem;">
    <x-metric-card
        label="Saldo em Caixa"
        :value="'R$ ' . number_format($saldoTotal, 2, ',', '.')"
        href="{{ route('contas.index') }}"
    />

    <x-metric-card
        label="Receitas do Mês"
        :value="'R$ ' . number_format($receitas, 2, ',', '.')"
        variant="success"
        href="{{ route('relatorios.receitas') }}"
    />

    <x-metric-card
        label="Despesas do Mês"
        :value="'R$ ' . number_format($despesas, 2, ',', '.')"
        variant="danger"
        href="{{ route('relatorios.despesas') }}"
    />

    <x-metric-card
        label="Resultado"
        :value="'R$ ' . number_format($saldo, 2, ',', '.')"
        variant="{{ $saldo >= 0 ? 'success' : 'danger' }}"
        href="{{ route('relatorios.fluxo') }}"
    />
</div>

<div class="grid" style="grid-template-columns:1fr 2fr;gap:1.5rem;margin-bottom:1.5rem;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Contas Bancárias</span>
            <a href="{{ route('contas.index') }}" class="btn btn-outline btn-sm">Ver todas</a>
        </div>

        <div class="card-body" style="padding:0;">
            @forelse($contas as $c)
                <a href="{{ route('contas.show', $c['id']) }}" style="display:block;padding:1rem 1.5rem;border-bottom:1px solid #e5e7eb;color:inherit;">
                    <p style="font-weight:500;font-size:0.875rem;">{{ $c['nome'] }}</p>
                    <p class="text-muted" style="font-size:0.75rem;">{{ $c['banco'] }}</p>
                    <p style="font-weight:600;margin-top:0.5rem;">
                        R$ {{ number_format($c['saldo'], 2, ',', '.') }}
                    </p>
                </a>
            @empty
                <p class="empty-state">Nenhuma conta cadastrada.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Resumo do Fluxo de Caixa</span>
            <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline btn-sm">Fluxo de caixa</a>
        </div>

        <div class="card-body">
            <p class="text-muted" style="font-size:0.875rem;">
                Este resumo usa as movimentações reais cadastradas no banco de dados.
            </p>

            <div style="display:grid;gap:0.75rem;margin-top:1rem;">
                <p><strong>Receitas:</strong> R$ {{ number_format($receitas, 2, ',', '.') }}</p>
                <p><strong>Despesas:</strong> R$ {{ number_format($despesas, 2, ',', '.') }}</p>
                <p><strong>Resultado:</strong> R$ {{ number_format($saldo, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Últimas Movimentações</span>
        <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline btn-sm">Ver todas</a>
    </div>

    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th class="text-right">Valor</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($movimentacoes as $m)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($m['data'])->format('d/m/Y') }}</td>
                        <td>{{ $m['descricao'] }}</td>
                        <td>{{ $m['categoria']['nome'] ?? '-' }}</td>
                        <td class="text-right {{ $m['tipo'] === 'receita' ? 'text-success' : 'text-danger' }}">
                            R$ {{ number_format(abs($m['valor']), 2, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="btn btn-outline btn-sm">Detalhes</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Nenhuma movimentação cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @foreach($movimentacoes as $m)
            <a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="mobile-card" style="display:block;color:inherit;">
                <p style="font-weight:500;">{{ $m['descricao'] }}</p>
                <p class="text-muted" style="font-size:0.75rem;">
                    {{ \Carbon\Carbon::parse($m['data'])->format('d/m/Y') }}
                    · {{ $m['categoria']['nome'] ?? '-' }}
                </p>
                <p class="{{ $m['tipo'] === 'receita' ? 'text-success' : 'text-danger' }}" style="font-weight:600;margin-top:0.5rem;">
                    R$ {{ number_format(abs($m['valor']), 2, ',', '.') }}
                </p>
            </a>
        @endforeach
    </div>
</div>
@endsection