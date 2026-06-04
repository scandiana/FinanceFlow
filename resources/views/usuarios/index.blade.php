@extends('layouts.app')
@section('title', 'Usuários')

@section('content')
<div class="page-header">
    <div><h1>Usuários e Permissões</h1><p>Gerencie acessos e perfis do sistema</p></div>
    @if(\App\Data\MockData::podeAdmin())
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Novo usuário</a>
    @endif
</div>

@if(!\App\Data\MockData::podeAdmin())
    <div class="perfil-banner">Apenas administradores podem cadastrar ou excluir usuários. Seu perfil atual: <strong>{{ \App\Data\MockData::perfis()[session('perfil','administrador')] }}</strong></div>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Status</th><th>Cadastro</th><th></th></tr></thead>
            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u['nome'] }}</td>
                        <td>{{ $u['email'] }}</td>
                        <td><span class="badge badge-info">{{ $perfis[$u['perfil']] ?? $u['perfil'] }}</span></td>
                        <td><span class="badge {{ $u['status'] === 'ativo' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($u['status']) }}</span></td>
                        <td>{{ \App\Data\MockData::formatDate($u['created_at']) }}</td>
                        <td class="text-center">
                            @if(\App\Data\MockData::podeAdmin())
                                <a href="{{ route('usuarios.edit', $u['id']) }}" class="btn btn-outline btn-sm">Editar</a>
                                @if($u['id'] !== 1)
                                    <form action="{{ route('usuarios.destroy', $u['id']) }}" method="POST" style="display:inline;">@csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Excluir usuário?">Excluir</button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top:1.5rem;">
    <div class="card-header"><span class="card-title">Perfis de acesso</span></div>
    <div class="card-body" style="font-size:0.875rem;">
        <ul style="padding-left:1.25rem;display:grid;gap:0.5rem;">
            <li><strong>Administrador</strong> — acesso total, gestão de usuários</li>
            <li><strong>Financeiro</strong> — cadastros e movimentações</li>
            <li><strong>Visualização</strong> — apenas consulta (simulado na interface)</li>
        </ul>
    </div>
</div>
@endsection
