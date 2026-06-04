@extends('layouts.app')
@section('title', 'Transferência')

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Contas', 'url' => route('contas.index')],
    ['label' => 'Transferência'],
]" />
@endsection

@section('content')
<div class="page-header">
    <div><h1>Transferência entre contas</h1><p>Transfira valores entre suas contas bancárias</p></div>
</div>

<div class="card" style="max-width:32rem;">
    <div class="card-body">
        <form method="POST" action="{{ route('contas.transferir') }}">
            @csrf
            <div class="form-group">
                <label>Conta origem</label>
                <select name="conta_origem_id" class="form-control" required>
                    @foreach($contas as $c)
                        <option value="{{ $c['id'] }}" @selected(request('origem') == $c['id'])>{{ $c['nome'] }} ({{ \App\Data\MockData::formatMoney($c['saldo']) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Conta destino</label>
                <select name="conta_destino_id" class="form-control" required>
                    @foreach($contas as $c)
                        <option value="{{ $c['id'] }}">{{ $c['nome'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Valor (R$)</label>
                <input type="number" step="0.01" name="valor" class="form-control" required min="0.01">
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <input type="text" name="descricao" class="form-control" value="Transferência entre contas">
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Confirmar transferência</button>
                <a href="{{ route('contas.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
