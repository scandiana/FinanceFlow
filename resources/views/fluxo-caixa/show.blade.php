@extends('layouts.app')

@section('title', 'Movimentação')

@section('breadcrumb')
<x-breadcrumb :items="[
    ['label' => 'Fluxo de Caixa', 'url' => route('fluxo-caixa.index')],
    ['label' => 'Detalhes'],
]" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $movimentacao['descricao'] }}</h1>
        <p>
            {{ \App\Data\MockData::formatDate($movimentacao['data']) }}
            · {{ $movimentacao['categoria'] }}
        </p>
    </div>

    <div class="btn-group">
        <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline">Voltar</a>

        @if(\App\Data\MovimentacaoStore::podeEditar($movimentacao['id']))
            <a href="{{ route('fluxo-caixa.edit', $movimentacao['id']) }}" class="btn btn-primary">Editar</a>

            <form action="{{ route('fluxo-caixa.destroy', $movimentacao['id']) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" data-confirm="Excluir movimentação?">
                    Excluir
                </button>
            </form>
        @else
            <span class="badge badge-info">Movimentação fixa mensal (gerada automaticamente)</span>
        @endif
    </div>
</div>

<div class="grid grid-2">

    {{-- DETALHES --}}
    <div class="card">
        <div class="card-body">
            <dl style="display:grid;gap:1rem;">

                <div>
                    <dt class="text-muted" style="font-size:0.75rem;">Valor</dt>
                    <dd style="font-size:1.5rem;font-weight:600;"
                        class="{{ $movimentacao['valor'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ \App\Data\MockData::formatMoney(abs($movimentacao['valor'])) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-muted" style="font-size:0.75rem;">Tipo</dt>
                    <dd>
                        <span class="badge {{ $movimentacao['tipo'] === 'receita' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($movimentacao['tipo']) }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-muted" style="font-size:0.75rem;">Status</dt>
                    <dd>{{ ucfirst($movimentacao['status']) }}</dd>
                </div>

                <div>
                    <dt class="text-muted" style="font-size:0.75rem;">Conta</dt>
                    <dd>
                        <a href="{{ route('contas.show', $movimentacao['conta_id']) }}">
                            {{ $movimentacao['conta'] }}
                        </a>
                    </dd>
                </div>

                @if($movimentacao['cliente_id'])
                <div>
                    <dt class="text-muted" style="font-size:0.75rem;">Cliente</dt>
                    <dd>
                        <a href="{{ route('clientes.show', $movimentacao['cliente_id']) }}">
                            Ver cliente
                        </a>
                    </dd>
                </div>
                @endif

                <div>
                    <dt class="text-muted" style="font-size:0.75rem;">Registrado por</dt>
                    <dd>{{ $movimentacao['usuario'] }}</dd>
                </div>

            </dl>
        </div>
    </div>

    {{-- DOCUMENTOS --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Documentos Anexados</span>
        </div>

        <div class="card-body">

            @php
                $arquivos = $movimentacao['nota_fiscais'] ?? [];

                if (!is_array($arquivos)) {
                    $arquivos = [$arquivos];
                }
            @endphp

            @if(!empty($arquivos))
            @dd($movimentacao['nota_fiscais'])
        
                @foreach($arquivos as $arquivo)
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:10px;">
                        <span style="font-size:2rem;">📄</span>

                        <div>
                            <p style="margin:0;font-weight:500;">Documento anexado</p>

                            <a href="{{ asset('uploads/nfs/' . $arquivo) }}"
                               target="_blank"
                               style="color:#0066cc;text-decoration:underline;font-size:0.875rem;">
                                Abrir arquivo
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted" style="margin:0;font-size:0.875rem;">
                    Nenhum documento anexado.
                </p>
            @endif

        </div>
    </div>

</div>
@endsection