@extends('layouts.app')
@section('title', $cliente['nome'])

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Clientes', 'url' => route('clientes.index')],
    ['label' => $cliente['nome']],
]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $cliente['nome'] }}</h1>
        <p>{{ $cliente['documento'] }} · {{ $cliente['cidade'] }}</p>
    </div>
    <div class="btn-group">
        @if(\App\Data\MockData::podeEditar())
            <a href="{{ route('clientes.edit', $cliente['id']) }}" class="btn btn-primary">Editar</a>
        @endif
        <a href="{{ route('clientes.index') }}" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    <div class="card metric-card"><p class="label">Total recebido</p><p class="value text-success">{{ \App\Data\MockData::formatMoney($cliente['total_recebido']) }}</p></div>
    <div class="card metric-card"><p class="label">Pendente</p><p class="value">{{ \App\Data\MockData::formatMoney($cliente['pendente']) }}</p></div>
    <div class="card metric-card"><p class="label">Status</p><p class="value" style="font-size:1rem;"><span class="badge badge-success">{{ ucfirst($cliente['status']) }}</span></p></div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header"><span class="card-title">Dados cadastrais</span></div>
        <div class="card-body" style="font-size:0.875rem;display:grid;gap:0.75rem;">
            <p><span class="text-muted">E-mail:</span> {{ $cliente['email'] }}</p>
            <p><span class="text-muted">Telefone:</span> {{ $cliente['telefone'] }}</p>
            <p><span class="text-muted">Cidade:</span> {{ $cliente['cidade'] }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><span class="card-title">Pagamentos registrados</span></div>
        <div class="card-body" style="padding:0;">
            @forelse($pagamentos as $p)
                <a href="{{ route('fluxo-caixa.show', $p['id']) }}" style="display:flex;justify-content:space-between;padding:0.75rem 1.5rem;border-bottom:1px solid #e5e7eb;color:inherit;">
                    <span>{{ $p['descricao'] }}</span>
                    <span class="text-success">{{ \App\Data\MockData::formatMoney($p['valor']) }}</span>
                </a>
            @empty
                <p class="empty-state" style="padding:2rem;">Nenhum pagamento registrado.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
