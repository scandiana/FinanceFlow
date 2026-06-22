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
        <p><p>{{ $cartao['bandeira'] ?? 'Cartão' }} **** {{ $cartao['final'] ?? $cartao['final_cartao'] ?? '-' }}/p></p>
    </div>
    <div class="btn-group">
        <a href="{{ route('cartoes.fatura', $cartao['id']) }}" class="btn btn-primary">Ver fatura</a>
        <a href="{{ route('cartoes.compras.create', $cartao['id']) }}" class="btn btn-success">Nova compra</a>
        <a href="{{ route('cartoes.index') }}" class="btn btn-outline">Voltar</a>
    </div>
</div>

@php
    $compras = $compras ?? [];

    $faturaAtual = collect($compras)->sum(function ($c) {
        return $c['valor'] ?? 0;
    });

    $limite = $cartao['limite'] ?? 0;

    $disponivel = $limite - $faturaAtual;
@endphp

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    <div class="card metric-card">
        <p class="label">Fatura atual</p>
        <p class="value">
            R$ {{ number_format($faturaAtual, 2, ',', '.') }}
        </p>
    </div>

    <div class="card metric-card">
        <p class="label">Limite</p>
        <p class="value">
            R$ {{ number_format($limite, 2, ',', '.') }}
        </p>
    </div>

    <div class="card metric-card">
         <p class="label">Disponível</p>
        <p class="value {{ $disponivel >= 0 ? 'text-success' : 'text-danger' }}">
            R$ {{ number_format($disponivel, 2, ',', '.') }}
        </p>
    </div>
</div>
        <table>
            <thead><tr><th>Data</th><th>Descrição</th><th>Parcelas</th><th class="text-right">Valor</th></tr></thead>
            <tbody>
                @foreach($compras as $c)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($c['data']) }}</td>
                        <td>{{ $c['descricao'] }}</td>
                        <td>{{ isset($c['parcelas']) && $c['parcelas'] > 1 ? $c['parcelas'].'x' : 'À vista' }}</td>
                        <td class="text-right">{{ \App\Data\MockData::formatMoney($c['valor']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
