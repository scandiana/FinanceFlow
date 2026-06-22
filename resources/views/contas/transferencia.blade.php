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
        @if(count($contas) < 2)
            <p class="empty-state">Cadastre pelo menos duas contas para realizar uma transferência.</p>
            <a href="{{ route('contas.index') }}" class="btn btn-outline">Voltar</a>
        @else
            <form method="POST" action="{{ route('contas.transferir') }}">
                @csrf

                <div class="form-group">
                    <label>Conta origem</label>
                    <select name="conta_origem_id" class="form-control" required>
                        <option value="">Selecione a conta origem</option>
                        @foreach($contas as $c)
                            <option value="{{ $c['id'] }}" @selected(request('origem') == $c['id'])>
                                {{ $c['nome'] }} (R$ {{ number_format($c['saldo'], 2, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Conta destino</label>
                    <select name="conta_destino_id" class="form-control" required>
                        <option value="">Selecione a conta destino</option>
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
        @endif
    </div>
</div>
@endsection