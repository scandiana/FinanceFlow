@extends('layouts.app')
@section('title', 'Clientes')

@section('breadcrumb')
<x-breadcrumb :items="[['label' => 'Clientes']]" />
@endsection

@section('content')
<div class="page-header">
    <div><h1>Clientes</h1><p>Gestão de clientes e histórico financeiro</p></div>
    @if(\App\Data\MockData::podeEditar())
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">Novo cliente</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr><th>Nome</th><th>Documento</th><th>Cidade</th><th class="text-right">Total recebido</th><th class="text-right">Pendente</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($clientes as $c)
                    <tr>
                        <td><a href="{{ route('clientes.show', $c['id']) }}">{{ $c['nome'] }}</a></td>
                        <td>{{ $c['documento'] }}</td>
                        <td>{{ $c['cidade'] }}</td>
                        <td class="text-right text-success">{{ \App\Data\MockData::formatMoney($c['total_recebido']) }}</td>
                        <td class="text-right">{{ \App\Data\MockData::formatMoney($c['pendente']) }}</td>
                        <td><span class="badge {{ $c['status'] === 'ativo' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($c['status']) }}</span></td>
                        <td class="text-center actions-inline">
                            <a href="{{ route('clientes.show', $c['id']) }}" class="btn btn-outline btn-sm">Perfil</a>
                            @if(\App\Data\MockData::podeEditar())
                                <a href="{{ route('clientes.edit', $c['id']) }}" class="btn btn-outline btn-sm">Editar</a>
                                <form action="{{ route('clientes.destroy', $c['id']) }}" method="POST" style="display:inline;">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-confirm="Excluir cliente?">Excluir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mobile-card-list">
        @foreach($clientes as $c)
            <div class="mobile-card">
                <a href="{{ route('clientes.show', $c['id']) }}" style="font-weight:500;">{{ $c['nome'] }}</a>
                <p class="text-muted" style="font-size:0.75rem;">{{ $c['documento'] }}</p>
                <div class="btn-group" style="margin-top:0.5rem;">
                    <a href="{{ route('clientes.show', $c['id']) }}" class="btn btn-outline btn-sm">Perfil</a>
                    @if(\App\Data\MockData::podeEditar())
                        <a href="{{ route('clientes.edit', $c['id']) }}" class="btn btn-outline btn-sm">Editar</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
