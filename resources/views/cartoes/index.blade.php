@extends('layouts.app')
@section('title', 'Cartões')

@section('breadcrumb')
<x-breadcrumb :items="[['label' => 'Cartões']]" />
@endsection

@section('content')
<div class="page-header">
    <div><h1>Cartões de Crédito</h1><p>Controle de faturas, parcelamentos e compras</p></div>
</div>

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    @forelse($cartoes as $cartao)
        <div class="card" style="padding:1.5rem;">
            <div style="display:flex;justify-content:space-between;">
                <div>
                    <p style="font-weight:600;">{{ $cartao['nome'] }}</p>
                    <p class="text-muted" style="font-size:0.75rem;">{{ $cartao['bandeira'] ?? 'Cartão' }} · Final {{ $cartao['final'] ?? $cartao['final_cartao'] ?? '-' }}</p>
                </div>
                <span class="badge badge-success">{{ ucfirst($cartao['status']) }}</span>
            </div>
            <p style="margin-top:1rem;font-size:0.875rem;">Fatura atual</p>
            <p style="font-size:1.25rem;font-weight:600;">R$ {{ number_format($cartao['fatura_atual'] ?? $cartao['limite_usado'] ?? 0, 2, ',', '.') }}</p>
            <p class="text-muted" style="font-size:0.75rem;">dia {{ $cartao['vencimento'] ?? $cartao['dia_vencimento'] ?? '-' }}</p>
            <p class="text-muted" style="font-size:0.75rem;margin-top:0.5rem;">R$ {{ number_format($cartao['limite'], 2, ',', '.') }}</p>
            <div class="btn-group" style="margin-top:1rem;">
                <a href="{{ route('cartoes.show', $cartao['id']) }}" class="btn btn-outline btn-sm">Detalhes</a>
                <a href="{{ route('cartoes.fatura', $cartao['id']) }}" class="btn btn-primary btn-sm">Fatura</a>
                <a href="{{ route('cartoes.compras.create', $cartao['id']) }}" class="btn btn-success btn-sm">Nova compra</a>
            </div>
        </div>
    @empty
       <div class="card">
           <p class="empty-state">Nenhum cartão cadastrado.</p>
       </div>
    @endforelse
</div>
@endsection
