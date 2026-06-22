@extends('layouts.app')
@section('title', 'Nova compra')

@section('content')
<div class="page-header">
    <div><h1>Nova compra — {{ $cartao['nome'] }}</h1></div>
</div>
<div class="card" style="max-width:32rem;">
    <div class="card-body">
        <form method="POST" action="{{ route('cartoes.compras.store', $cartao['id']) }}">
            @csrf
            <div class="form-group"><label>Descrição</label><input type="text" name="descricao" class="form-control" required></div>
            <div class="form-row">
                <div class="form-group"><label>Valor</label><input type="number" step="0.01" name="valor" class="form-control" required></div>
                <div class="form-group"><label>Parcelas</label><input type="number" name="parcelas" class="form-control" value="1" min="1" max="12"></div>
            </div>
            <div class="form-group"><label>Data</label><input type="date" name="data" class="form-control" value="{{ date('Y-m-d') }}"></div>
            <div class="form-group">
                     <label>Categoria</label>
                     <select name="categoria_id" class="form-control" required>
                         <option value="">Selecione uma categoria</option>
                         @foreach($categorias as $cat)
                             <option value="{{ $cat['id'] }}">{{ $cat['nome'] }}</option>
                         @endforeach
                                     </select>
                    </div>

                <div class="form-group">
                     <label>Conta bancária</label>
                     <select name="conta_id" class="form-control" required>
                                         <option value="">Selecione uma conta</option>
                                         @foreach($contas as $c)
                             <option value="{{ $c['id'] }}">{{ $c['nome'] }}</option>
                         @endforeach
                     </select>
                    </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Registrar compra</button>
                <a href="{{ route('cartoes.show', $cartao['id']) }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
