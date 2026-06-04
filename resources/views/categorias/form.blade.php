@extends('layouts.app')
@section('title', $categoria ? 'Editar categoria' : 'Nova categoria')

@section('content')
<div class="page-header"><h1>{{ $categoria ? 'Editar categoria' : 'Nova categoria' }}</h1></div>
<div class="card" style="max-width:32rem;">
    <div class="card-body">
        <form method="POST" action="{{ $categoria ? route('categorias.update', $categoria['id']) : route('categorias.store') }}">
            @csrf
            @if($categoria) @method('PUT') @endif
            <div class="form-group"><label>Nome</label><input type="text" name="nome" class="form-control" required value="{{ old('nome', $categoria['nome'] ?? '') }}"></div>
            <div class="form-group"><label>Tipo</label>
                <select name="tipo" class="form-control">
                    <option value="receita" @selected(($categoria['tipo'] ?? '') === 'receita')>Receita</option>
                    <option value="despesa" @selected(($categoria['tipo'] ?? 'despesa') === 'despesa')>Despesa</option>
                </select>
            </div>
            <div class="form-group"><label>Descrição</label><textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $categoria['descricao'] ?? '') }}</textarea></div>
            <div class="form-group"><label>Cor</label><input type="color" name="cor" value="{{ old('cor', $categoria['cor'] ?? '#2563eb') }}"></div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
