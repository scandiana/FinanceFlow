@extends('layouts.app')
@section('title', $conta['nome'])

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Contas', 'url' => route('contas.index')],
    ['label' => $conta['nome']],
]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $conta['nome'] }}</h1>
        <p>{{ $conta['banco'] }} · Ag {{ $conta['agencia'] }} · {{ $conta['numero'] }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('contas.transferencia') }}?origem={{ $conta['id'] }}" class="btn btn-primary">Transferir</a>
        <a href="{{ route('contas.index') }}" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card metric-card" style="margin-bottom:1.5rem;">
    <p class="label">Saldo atual</p>
    <p class="value">{{ \App\Data\MockData::formatMoney($conta['saldo']) }}</p>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Histórico de movimentações</span></div>
    <div class="table-wrap desktop-table">
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Categoria</th><th class="text-right">Valor</th><th></th></tr></thead>
            <tbody>
                @forelse($movimentacoes as $m)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($m['data']) }}</td>
                        <td>{{ $m['descricao'] }}</td>
                        <td>{{ $m['categoria'] }}</td>
                        <td class="text-right {{ $m['valor'] >= 0 ? 'text-success' : 'text-danger' }}">{{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}</td>
                        <td><a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="btn btn-outline btn-sm">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Sem movimentações nesta conta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
