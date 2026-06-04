@extends('layouts.app')
@section('title', $cartao['nome'])

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Cartões', 'url' => route('cartoes.index')],
    ['label' => $cartao['nome']],
]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $cartao['nome'] }}</h1>
        <p>{{ $cartao['bandeira'] }} **** {{ $cartao['final'] }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('cartoes.fatura', $cartao['id']) }}" class="btn btn-primary">Ver fatura</a>
        @if(\App\Data\MockData::podeEditar())
            <a href="{{ route('cartoes.compras.create', $cartao['id']) }}" class="btn btn-success">Nova compra</a>
        @endif
        <a href="{{ route('cartoes.index') }}" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    <div class="card metric-card"><p class="label">Fatura atual</p><p class="value">{{ \App\Data\MockData::formatMoney($cartao['fatura_atual']) }}</p></div>
    <div class="card metric-card"><p class="label">Limite</p><p class="value">{{ \App\Data\MockData::formatMoney($cartao['limite']) }}</p></div>
    <div class="card metric-card"><p class="label">Disponível</p><p class="value text-success">{{ \App\Data\MockData::formatMoney($cartao['limite'] - $cartao['fatura_atual']) }}</p></div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Histórico de compras</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Parcelas</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                @foreach($compras as $c)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($c['data']) }}</td>
                        <td>{{ $c['descricao'] }}</td>
                        <td>{{ $c['parcelas'] }}x</td>
                        <td class="text-right">{{ \App\Data\MockData::formatMoney($c['valor']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
