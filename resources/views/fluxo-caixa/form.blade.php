@extends('layouts.app')
@section('title', $movimentacao ? 'Editar movimentação' : 'Nova movimentação')

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Fluxo de Caixa', 'url' => route('fluxo-caixa.index')],
    ['label' => $movimentacao ? 'Editar' : ($tipo === 'receita' ? 'Nova Receita' : 'Nova Despesa')],
]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $movimentacao ? 'Editar movimentação' : ($tipo === 'receita' ? 'Nova Receita' : 'Nova Despesa') }}</h1>
    </div>
</div>

<div class="card" style="max-width:40rem;">
    <div class="card-body">
        <form method="POST" action="{{ $movimentacao ? route('fluxo-caixa.update', $movimentacao['id']) : route('fluxo-caixa.store') }}">
            @csrf
            @if($movimentacao) @method('PUT') @endif
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <div class="form-group">
                <label>Descrição</label>
                <input type="text" name="descricao" class="form-control" required value="{{ old('descricao', $movimentacao['descricao'] ?? '') }}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" class="form-control" required value="{{ old('valor', $movimentacao ? abs($movimentacao['valor']) : '') }}">
                </div>
                <div class="form-group">
                    <label>Data</label>
                    <input type="date" name="data" class="form-control" required value="{{ old('data', $movimentacao['data'] ?? date('Y-m-d')) }}">
                </div>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id" class="form-control" required>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat['id'] }}" @selected(old('categoria_id', $movimentacao['categoria_id'] ?? '') == $cat['id'])>{{ $cat['nome'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Conta bancária</label>
                <select name="conta_id" class="form-control" required>
                    @foreach($contas as $c)
                        <option value="{{ $c['id'] }}" @selected(old('conta_id', $movimentacao['conta_id'] ?? 1) == $c['id'])>{{ $c['nome'] }}</option>
                    @endforeach
                </select>
            </div>
            @if($tipo === 'receita')
                <div class="form-group">
                    <label>Cliente (opcional)</label>
                    <select name="cliente_id" class="form-control">
                        <option value="">—</option>
                        @foreach($clientes as $cl)
                            <option value="{{ $cl['id'] }}">{{ $cl['nome'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
