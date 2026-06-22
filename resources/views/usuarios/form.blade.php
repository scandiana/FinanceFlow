@extends('layouts.app')
@section('title', $usuario ? 'Editar usuário' : 'Novo usuário')

@section('content')
<div class="page-header"><h1>{{ $usuario ? 'Editar usuário' : 'Novo usuário' }}</h1></div>
<div class="card" style="max-width:32rem;">
    <div class="card-body">
        <form method="POST" action="{{ $usuario ? route('usuarios.update', $usuario['id']) : route('usuarios.store') }}">
            @csrf
            @if($usuario) @method('PUT') @endif
            <div class="form-group"><label>Nome</label><input type="text" name="nome" class="form-control" required value="{{ old('nome', $usuario['nome'] ?? '') }}"></div>
            <div class="form-group"><label>E-mail</label><input type="email" name="email" class="form-control" required value="{{ old('email', $usuario['email'] ?? '') }}"></div>
            <div class="form-group"><label>Perfil</label>
                <select name="perfil" class="form-control">
                    @foreach($perfis as $key => $label)
                        <option value="{{ $key }}" @selected(old('perfil', $usuario['perfil'] ?? 'financeiro') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>Status</label>
                <select name="status" class="form-control">
                    <option value="ativo" @selected(($usuario['status'] ?? 'ativo') === 'ativo')>Ativo</option>
                    <option value="inativo" @selected(($usuario['status'] ?? '') === 'inativo')>Inativo</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
