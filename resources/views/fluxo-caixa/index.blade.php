@extends('layouts.app')
@section('title', 'Fluxo de Caixa')

@section('breadcrumb')
<x-breadcrumb :items="[['label' => 'Fluxo de Caixa']]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Fluxo de Caixa</h1>
        <p>Gerencie todas as movimentações financeiras</p>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('filtros-panel').classList.toggle('hidden')">Filtros</button>
        <form action="{{ route('relatorios.exportar-pdf') }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn btn-outline">Exportar</button></form>
        @if(\App\Data\MockData::podeEditar())
            <a href="{{ route('fluxo-caixa.create', ['tipo' => 'receita']) }}" class="btn btn-success">Nova Receita</a>
            <a href="{{ route('fluxo-caixa.create', ['tipo' => 'despesa']) }}" class="btn btn-danger">Nova Despesa</a>
        @endif
    </div>
</div>

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    <div class="card metric-card"><p class="label">Total de Receitas</p><p class="value text-success">{{ \App\Data\MockData::formatMoney($resumo['receitas']) }}</p></div>
    <div class="card metric-card"><p class="label">Total de Despesas</p><p class="value text-danger">{{ \App\Data\MockData::formatMoney($resumo['despesas']) }}</p></div>
    <div class="card metric-card"><p class="label">Saldo do Período</p><p class="value {{ $resumo['saldo'] >= 0 ? 'text-success' : 'text-danger' }}">{{ \App\Data\MockData::formatMoney($resumo['saldo']) }}</p></div>
</div>

@if($filtrosAtivos ?? false)
    <div class="alert alert-info" style="margin-bottom:1rem;">
        Filtros ativos — {{ $movimentacoes->total() }} resultado(s) no período selecionado.
        <a href="{{ route('fluxo-caixa.index') }}" style="margin-left:0.5rem;">Limpar filtros</a>
    </div>
@endif

<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <span class="card-title">Movimentações fixas mensais</span>
        <span class="text-muted" style="font-size:0.75rem;">Geradas automaticamente (salários, servidores, clientes recorrentes)</span>
    </div>
    <div class="card-body" style="padding:0;">
        @foreach($fixas as $f)
            <div style="display:flex;justify-content:space-between;padding:0.75rem 1.5rem;border-bottom:1px solid #e5e7eb;font-size:0.875rem;">
                <span>{{ $f['descricao'] }} <span class="badge badge-info">dia {{ $f['dia'] }}</span></span>
                <span class="{{ $f['tipo'] === 'receita' ? 'text-success' : 'text-danger' }}">{{ \App\Data\MockData::formatMoney($f['valor']) }}/mês</span>
            </div>
        @endforeach
    </div>
</div>

<div id="filtros-panel" class="card {{ ($filtrosAtivos ?? false) ? '' : 'hidden' }}" style="margin-bottom:1.5rem;">
    <div class="card-body">
        <h3 style="font-size:0.875rem;margin-bottom:1rem;">Filtrar Movimentações</h3>
        <form method="GET" action="{{ route('fluxo-caixa.index') }}">
            <div class="form-row">
                <div class="form-group"><label>Data Inicial</label><input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}"></div>
                <div class="form-group"><label>Data Final</label><input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}"></div>
                <div class="form-group"><label>Categoria</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat['id'] }}" @selected(request('categoria_id') == $cat['id'])>{{ $cat['nome'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Conta</label>
                    <select name="conta_id" class="form-control">
                        <option value="">Todas</option>
                        @foreach($contas as $c)
                            <option value="{{ $c['id'] }}" @selected(request('conta_id') == $c['id'])>{{ $c['nome'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding-bottom:0;">
        <form method="GET" action="{{ route('fluxo-caixa.index') }}">
            @include('components.filter-hidden')
            <input type="search" name="q" class="form-control" placeholder="Buscar por descrição, categoria ou conta..." value="{{ request('q') }}">
        </form>
    </div>
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>Data</th><th>Descrição</th><th>Categoria</th><th>Conta</th><th>Tipo</th>
                    <th class="text-right">Valor</th><th class="text-center">Status</th>
                    <th class="text-center">NF</th> <!-- Nova coluna adicionada aqui -->
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimentacoes as $m)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($m['data']) }}</td>
                        <td>
                            <a href="{{ route('fluxo-caixa.show', $m['id']) }}">{{ $m['descricao'] }}</a>
                            @if(!empty($m['recorrente']))
                                <span class="badge badge-info" style="margin-left:0.25rem;">Fixa mensal</span>
                            @endif
                        </td>
                        <td>{{ $m['categoria'] }}</td>
                        <td>{{ $m['conta'] }}</td>
                        <td><span class="badge {{ $m['tipo'] === 'receita' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($m['tipo']) }}</span></td>
                        <td class="text-right {{ $m['valor'] >= 0 ? 'text-success' : 'text-danger' }}">{{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}</td>
                        <td class="text-center"><span class="badge badge-muted">{{ ucfirst($m['status']) }}</span></td>
                        <td class="text-center">
                            @if(!empty($m['nota_fiscal']))
                                <a href="{{ asset('uploads/nfs/' . $m['nota_fiscal']) }}" target="_blank" title="Ver Nota Fiscal" style="font-size: 1.1rem; text-decoration: none; cursor: pointer;">
                                    📎
                                </a>
                            @else
                                <span class="text-muted" style="font-size: 0.85rem;" title="Nenhuma nota fiscal anexada">—</span>
                            @endif
                        </td>
                        <td class="text-center actions-inline">
                            <a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="btn btn-outline btn-sm">Ver</a>
                            @if(\App\Data\MovimentacaoStore::podeEditar($m['id']))
                                <a href="{{ route('fluxo-caixa.edit', $m['id']) }}" class="btn btn-outline btn-sm">Editar</a>
                                <form action="{{ route('fluxo-caixa.destroy', $m['id']) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-confirm="Excluir esta movimentação?">Excluir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="empty-state">Nenhuma movimentação encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @foreach($movimentacoes as $m)
            <div class="mobile-card">
                <p style="font-weight:500;"><a href="{{ route('fluxo-caixa.show', $m['id']) }}">{{ $m['descricao'] }}</a></p>
                <p class="text-muted" style="font-size:0.75rem;">{{ \App\Data\MockData::formatDate($m['data']) }}</p>
                <p class="{{ $m['valor'] >= 0 ? 'text-success' : 'text-danger' }}" style="font-weight:600;">{{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}</p>
                <div class="btn-group" style="margin-top:0.5rem;">
                    <a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="btn btn-outline btn-sm">Ver</a>
                    @if(\App\Data\MovimentacaoStore::podeEditar($m['id']))
                        <a href="{{ route('fluxo-caixa.edit', $m['id']) }}" class="btn btn-outline btn-sm">Editar</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @if($movimentacoes->hasPages())
        <div class="pagination">
            @if($movimentacoes->onFirstPage())<span>&laquo;</span>@else<a href="{{ $movimentacoes->previousPageUrl() }}">&laquo;</a>@endif
            @foreach(range(1, $movimentacoes->lastPage()) as $p)
                @if($p == $movimentacoes->currentPage())<span class="active">{{ $p }}</span>@else<a href="{{ $movimentacoes->url($p) }}">{{ $p }}</a>@endif
            @endforeach
            @if($movimentacoes->hasMorePages())<a href="{{ $movimentacoes->nextPageUrl() }}">&raquo;</a>@else<span>&raquo;</span>@endif
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>.hidden{display:none!important;}</style>
@endpush
