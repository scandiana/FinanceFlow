@extends('layouts.app')
@section('title', 'Contas Bancárias')

@section('breadcrumb')
<x-breadcrumb :items="[['label' => 'Contas Bancárias']]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Contas Bancárias</h1>
        <p>Saldo consolidado: <strong>R$ {{ number_format($saldoTotal, 2, ',', '.') }}</strong></p>
    </div>
    <a href="{{ route('contas.transferencia') }}" class="btn btn-primary">Transferência entre contas</a>
</div>

<div class="grid grid-3" style="margin-bottom:1.5rem;">
    @foreach($contas as $conta)
        <a href="{{ route('contas.show', $conta['id']) }}" class="card link-card metric-card">
            <p class="label">{{ $conta['banco'] }}</p>
            <p style="font-weight:500;margin-top:0.25rem;">{{ $conta['nome'] }}</p>
            <p class="value" style="margin-top:0.75rem;">R$ {{ number_format($conta['saldo'], 2, ',', '.') }}</p>
            <p class="text-muted" style="font-size:0.75rem;margin-top:0.5rem;">Ag {{ $conta['agencia'] }} · Cc {{ $conta['numero'] ?? $conta['numero_conta'] ?? '-' }}</p>
        </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Resumo das contas</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Conta</th><th>Banco</th><th>Tipo</th><th class="text-right">Saldo</th><th></th></tr></thead>
            <tbody>
                @foreach($contas as $conta)
                    <tr>
                        <td>{{ $conta['nome'] }}</td>
                        <td>{{ $conta['banco'] }}</td>
                        <td>{{ ucfirst($conta['tipo']) }}</td>
                        <td class="text-right" style="font-weight:600;">{{ \App\Data\MockData::formatMoney($conta['saldo']) }}</td>
                        <td class="text-center"><a href="{{ route('contas.show', $conta['id']) }}" class="btn btn-outline btn-sm">Histórico</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
