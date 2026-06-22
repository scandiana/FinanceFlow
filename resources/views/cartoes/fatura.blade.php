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
        <p>Vencimento: dia {{ $cartao['dia_vencimento'] ?? '-' }}</p>
    </div>
    <form action="{{ route('relatorios.exportar-pdf') }}" method="POST">@csrf<button type="submit" class="btn btn-outline">Exportar PDF</button></form>
</div>

<div class="card metric-card" style="margin-bottom:1.5rem;">
    <p class="label">Valor total da fatura</p>
    @php
    $faturaAtual = $cartao['limite_usado'] ?? 0;
    @endphp

    <p class="value text-danger">
        R$ {{ number_format($faturaAtual, 2, ',', '.') }}
    </p>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Lançamentos da fatura</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Parcelamento</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                @forelse($compras as $c)
    <tr>
        <td>
            {{ \Carbon\Carbon::parse($c['data'])->format('d/m/Y') }}
        </td>

        <td>
            {{ $c['descricao'] }}
        </td>

        <td>
            @php
                $parcelas = 1;
            @endphp

            {{ $parcelas > 1 ? $parcelas.'x' : 'À vista' }}
        </td>

        <td class="text-right">
            R$ {{ number_format($c['valor'], 2, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="empty-state">
            Nenhuma compra registrada neste cartão.
        </td>
    </tr>
@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
