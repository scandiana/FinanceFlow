@extends('layouts.app')
@section('title', 'Fluxo de Caixa Consolidado')

@section('content')
<div class="page-header">
    <div><h1>Relatórios</h1><p>Fluxo de caixa consolidado</p></div>
    <form action="{{ route('relatorios.exportar-pdf') }}" method="POST">
        @csrf
        @foreach($filtros as $k => $v)
            @if($v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
        @endforeach
        <button type="submit" class="btn btn-outline">Exportar PDF</button>
    </form>
</div>
@include('components.relatorios-nav')

<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('relatorios.fluxo') }}">
            <div class="form-row">
                <div class="form-group"><label>Data início</label><input type="date" name="data_inicio" class="form-control" value="{{ $filtros['data_inicio'] ?? '' }}"></div>
                <div class="form-group"><label>Data fim</label><input type="date" name="data_fim" class="form-control" value="{{ $filtros['data_fim'] ?? '' }}"></div>
                <div class="form-group"><label>Categoria</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c['id'] }}" @selected(($filtros['categoria_id'] ?? '') == $c['id'])>{{ $c['nome'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Conta</label>
                    <select name="conta_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach($contas as $c)
                            <option value="{{ $c['id'] }}" @selected(($filtros['conta_id'] ?? '') == $c['id'])>{{ $c['nome'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="align-self:end;"><button type="submit" class="btn btn-primary">Filtrar</button></div>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    <div class="card metric-card"><p class="label">Receitas</p><p class="value text-success">{{ \App\Data\MockData::formatMoney($resumo['receitas']) }}</p></div>
    <div class="card metric-card"><p class="label">Despesas</p><p class="value text-danger">{{ \App\Data\MockData::formatMoney($resumo['despesas']) }}</p></div>
    <div class="card metric-card"><p class="label">Saldo</p><p class="value {{ $resumo['saldo'] >= 0 ? 'text-success' : 'text-danger' }}">{{ \App\Data\MockData::formatMoney($resumo['saldo']) }}</p></div>
</div>

<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body">
        <div class="chart-container">
            <canvas id="chart-relatorio-fluxo" data-chart="{{ json_encode($grafico) }}"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Tipo</th><th>Categoria</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                @forelse($movimentacoes as $m)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($m['data']) }}</td>
                        <td>
                            <a href="{{ route('fluxo-caixa.show', $m['id']) }}">{{ $m['descricao'] }}</a>
                            @if(!empty($m['recorrente']))<span class="badge badge-info">Fixa</span>@endif
                        </td>
                        <td><span class="badge {{ $m['tipo'] === 'receita' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($m['tipo']) }}</span></td>
                        <td>{{ $m['categoria'] }}</td>
                        <td class="text-right {{ $m['valor'] >= 0 ? 'text-success' : 'text-danger' }}">{{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Nenhum lançamento no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
