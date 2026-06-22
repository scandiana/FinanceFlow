@extends('layouts.app')
@section('title', 'Categorias')

@section('content')
<div class="page-header">
    <div><h1>Categorias</h1><p>Organize receitas e despesas por categoria</p></div>
    @if(\App\Data\MockData::podeEditar())
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">Nova categoria</a>
    @endif
</div>

<div class="grid grid-2">
    @foreach(['receita' => 'Receitas', 'despesa' => 'Despesas'] as $tipo => $titulo)
        <div class="card">
            <div class="card-header"><span class="card-title">{{ $titulo }}</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Nome</th><th>Descrição</th><th></th></tr></thead>
                    <tbody>
                        @foreach(collect($categorias)->where('tipo', $tipo) as $cat)
                            <tr>
                                <td>
                                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $cat['cor'] }};margin-right:6px;"></span>
                                    {{ $cat['nome'] }}
                                </td>
                                <td class="text-muted" style="font-size:0.8125rem;">{{ $cat['descricao'] }}</td>
                                <td class="text-center">
                                    @if(\App\Data\MockData::podeEditar())
                                        <a href="{{ route('categorias.edit', $cat['id']) }}" class="btn btn-outline btn-sm">Editar</a>
                                        <form action="{{ route('categorias.destroy', $cat['id']) }}" method="POST" style="display:inline;">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Excluir categoria?">Excluir</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
