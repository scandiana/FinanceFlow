@extends('layouts.app')
@section('title', 'Relatório de Despesas')

@section('content')
<div class="page-header">
    <div><h1>Relatórios</h1><p>Relatório de despesas (inclui despesas fixas mensais)</p></div>
    <form action="{{ route('relatorios.exportar-pdf') }}" method="POST">
        @csrf
        @foreach($filtros as $k => $v)
            @if($v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
        @endforeach
        <input type="hidden" name="tipo" value="despesa">
        <button type="submit" class="btn btn-outline">Exportar PDF</button>
    </form>
</div>
@include('components.relatorios-nav')

<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('relatorios.despesas') }}">
            <div class="form-row">
                <div class="form-group"><label>Data início</label><input type="date" name="data_inicio" class="form-control" value="{{ $filtros['data_inicio'] ?? '' }}"></div>
                <div class="form-group"><label>Data fim</label><input type="date" name="data_fim" class="form-control" value="{{ $filtros['data_fim'] ?? '' }}"></div>
                <div class="form-group"><label>Categoria</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach(collect($categorias)->where('tipo','despesa') as $c)
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

<div class="card metric-card" style="margin-bottom:1.5rem;"><p class="label">Total de despesas</p><p class="value text-danger">{{ \App\Data\MockData::formatMoney($total) }}</p><p class="text-muted" style="font-size:0.75rem;margin-top:0.25rem;">{{ $movimentacoes->count() }} lançamento(s)</p></div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Categoria</th><th>Conta</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                @forelse($movimentacoes as $m)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($m['data']) }}</td>
                        <td>
                            <a href="{{ route('fluxo-caixa.show', $m['id']) }}">{{ $m['descricao'] }}</a>
                            @if(!empty($m['recorrente']))<span class="badge badge-info">Fixa</span>@endif
                        </td>
                        <td>{{ $m['categoria'] }}</td>
                        <td>{{ $m['conta'] }}</td>
                        <td class="text-right text-danger">{{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Nenhuma despesa no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
