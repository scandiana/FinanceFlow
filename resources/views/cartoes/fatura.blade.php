@extends('layouts.app')
@section('title', 'Fatura')

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Cartões', 'url' => route('cartoes.index')],
    ['label' => $cartao['nome'], 'url' => route('cartoes.show', $cartao['id'])],
    ['label' => 'Fatura'],
]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Fatura — {{ $cartao['nome'] }}</h1>
        <p>Vencimento: {{ \App\Data\MockData::formatDate($cartao['vencimento']) }}</p>
    </div>
    <form action="{{ route('relatorios.exportar-pdf') }}" method="POST">@csrf<button type="submit" class="btn btn-outline">Exportar PDF</button></form>
</div>

<div class="card metric-card" style="margin-bottom:1.5rem;">
    <p class="label">Valor total da fatura</p>
    <p class="value text-danger">{{ \App\Data\MockData::formatMoney($cartao['fatura_atual']) }}</p>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Lançamentos da fatura</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Parcelamento</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                @foreach($compras as $c)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($c['data']) }}</td>
                        <td>{{ $c['descricao'] }}</td>
                        <td>{{ $c['parcelas'] > 1 ? $c['parcelas'].'x de '.\App\Data\MockData::formatMoney($c['valor']/$c['parcelas']) : 'À vista' }}</td>
                        <td class="text-right">{{ \App\Data\MockData::formatMoney($c['valor']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
