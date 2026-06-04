@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Visão geral da sua gestão financeira</p>
    </div>
    <select class="form-control" style="width:auto;" onchange="location.href='{{ route('dashboard') }}'">
        <option>Este mês</option>
        <option>Últimos 3 meses</option>
        <option>Últimos 6 meses</option>
        <option>Este ano</option>
    </select>
</div>

@foreach($alertas as $alerta)
    <div class="alert alert-warning">
        <div style="flex:1;">
            <p class="alert-title">{{ $alerta['titulo'] }}</p>
            <p class="alert-text">{{ $alerta['mensagem'] }}</p>
        </div>
        @php
            $url = isset($alerta['params'])
                ? route($alerta['link'], $alerta['params'])
                : route($alerta['link']);
        @endphp
        <a href="{{ $url }}" class="btn btn-outline btn-sm">Ver detalhes</a>
    </div>
@endforeach

<div class="grid grid-4" style="margin-bottom:1.5rem;">
    <x-metric-card label="Saldo em Caixa" :value="\App\Data\MockData::formatMoney($resumo['saldo_total'])" href="{{ route('contas.index') }}" footer="<span class='text-success'>+{$resumo['variacao_saldo']}%</span> <span class='text-muted'>vs mês anterior</span>" />
    <x-metric-card label="Receitas do Mês" :value="\App\Data\MockData::formatMoney($resumo['receitas_mes'])" variant="success" href="{{ route('relatorios.receitas') }}" footer="<span class='text-success'>+{$resumo['variacao_receitas']}%</span> <span class='text-muted'>vs mês anterior</span>" />
    <x-metric-card label="Despesas do Mês" :value="\App\Data\MockData::formatMoney($resumo['despesas_mes'])" variant="danger" href="{{ route('relatorios.despesas') }}" footer="<span class='text-danger'>+{$resumo['variacao_despesas']}%</span> <span class='text-muted'>vs mês anterior</span>" />
    <x-metric-card label="Resultado" :value="\App\Data\MockData::formatMoney($resumo['resultado'])" variant="success" href="{{ route('relatorios.fluxo') }}" footer="<span class='text-muted'>Margem:</span> <strong>{$resumo['margem']}%</strong>" />
</div>

<div class="grid grid-2" style="margin-bottom:1.5rem;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Receitas x Despesas</span>
            <a href="{{ route('relatorios.fluxo') }}" class="btn btn-outline btn-sm">Ver relatório</a>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="chart-receitas-despesas" data-chart="{{ json_encode($graficoBarras) }}"></canvas>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Próximos Vencimentos</span>
            <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline btn-sm">Ver todos</a>
        </div>
        <div class="card-body" style="padding:0;">
            @foreach($vencimentos as $v)
                <a href="{{ $v['tipo'] === 'cartao' ? route('cartoes.fatura', 1) : route('fluxo-caixa.index') }}" style="display:flex;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid #e5e7eb;color:inherit;">
                    <div>
                        <p style="font-weight:500;font-size:0.875rem;">{{ $v['descricao'] }}</p>
                        <p class="text-muted" style="font-size:0.75rem;">Vence em {{ \App\Data\MockData::formatDate($v['data_vencimento']) }}</p>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-weight:600;">{{ \App\Data\MockData::formatMoney($v['valor']) }}</p>
                        <span class="badge {{ $v['status'] === 'pendente' ? 'badge-warning' : 'badge-info' }}">{{ $v['status'] === 'pendente' ? 'Pendente' : 'Agendado' }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="grid" style="grid-template-columns:1fr 2fr;gap:1.5rem;margin-bottom:1.5rem;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Contas Bancárias</span>
            <a href="{{ route('contas.index') }}" class="btn btn-outline btn-sm">Ver todas</a>
        </div>
        <div class="card-body" style="padding:0;">
            @foreach($contas as $c)
                <a href="{{ route('contas.show', $c['id']) }}" style="display:block;padding:1rem 1.5rem;border-bottom:1px solid #e5e7eb;color:inherit;">
                    <p style="font-weight:500;font-size:0.875rem;">{{ $c['nome'] }}</p>
                    <p class="text-muted" style="font-size:0.75rem;">{{ $c['banco'] }}</p>
                    <p style="font-weight:600;margin-top:0.5rem;">{{ \App\Data\MockData::formatMoney($c['saldo']) }}</p>
                </a>
            @endforeach
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Evolução do Fluxo de Caixa</span>
            <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline btn-sm">Fluxo de caixa</a>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="chart-fluxo-caixa" data-chart="{{ json_encode($graficoLinha) }}"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Últimas Movimentações</span>
        <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline btn-sm">Ver todas</a>
    </div>
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th class="text-right">Valor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimentacoes as $m)
                    <tr>
                        <td>{{ \App\Data\MockData::formatDate($m['data']) }}</td>
                        <td>{{ $m['descricao'] }}</td>
                        <td>{{ $m['categoria'] }}</td>
                        <td class="text-right {{ $m['valor'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}
                        </td>
                        <td class="text-center"><a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="btn btn-outline btn-sm">Detalhes</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mobile-card-list">
        @foreach($movimentacoes as $m)
            <a href="{{ route('fluxo-caixa.show', $m['id']) }}" class="mobile-card" style="display:block;color:inherit;">
                <p style="font-weight:500;">{{ $m['descricao'] }}</p>
                <p class="text-muted" style="font-size:0.75rem;">{{ \App\Data\MockData::formatDate($m['data']) }} · {{ $m['categoria'] }}</p>
                <p class="{{ $m['valor'] >= 0 ? 'text-success' : 'text-danger' }}" style="font-weight:600;margin-top:0.5rem;">{{ \App\Data\MockData::formatMoney(abs($m['valor'])) }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
