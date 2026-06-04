@extends('layouts.app')
@section('title', $cliente ? 'Editar cliente' : 'Novo cliente')

@section('content')
<div class="page-header"><h1>{{ $cliente ? 'Editar cliente' : 'Novo cliente' }}</h1></div>
<div class="card" style="max-width:40rem;">
    <div class="card-body">
        <form method="POST" action="{{ $cliente ? route('clientes.update', $cliente['id']) : route('clientes.store') }}">
            @csrf
            @if($cliente) @method('PUT') @endif
            <div class="form-group"><label>Nome / Razão social</label><input type="text" name="nome" class="form-control" required value="{{ old('nome', $cliente['nome'] ?? '') }}"></div>
            <div class="form-row">
                <div class="form-group"><label>CNPJ/CPF</label><input type="text" name="documento" class="form-control" value="{{ old('documento', $cliente['documento'] ?? '') }}"></div>
                <div class="form-group"><label>Telefone</label><input type="text" name="telefone" class="form-control" value="{{ old('telefone', $cliente['telefone'] ?? '') }}"></div>
            </div>
            <div class="form-group"><label>E-mail</label><input type="email" name="email" class="form-control" value="{{ old('email', $cliente['email'] ?? '') }}"></div>
            <div class="form-group"><label>Cidade</label><input type="text" name="cidade" class="form-control" value="{{ old('cidade', $cliente['cidade'] ?? '') }}"></div>
            <div class="form-group"><label>Status</label>
                <select name="status" class="form-control">
                    <option value="ativo" @selected(($cliente['status'] ?? 'ativo') === 'ativo')>Ativo</option>
                    <option value="inativo" @selected(($cliente['status'] ?? '') === 'inativo')>Inativo</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
