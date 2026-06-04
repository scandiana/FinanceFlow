<?php

namespace App\Http\Controllers;

use App\Data\MovimentacaoStore;
use App\Data\MockData;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function receitas(Request $request)
    {
        $request->merge(['tipo' => 'receita']);
        $movimentacoes = MovimentacaoStore::filtrar($request)->where('tipo', 'receita');

        return view('relatorios.receitas', [
            'movimentacoes' => $movimentacoes,
            'total' => $movimentacoes->sum(fn ($m) => abs($m['valor'])),
            'categorias' => MockData::categorias(),
            'contas' => MockData::contas(),
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id']),
        ]);
    }

    public function despesas(Request $request)
    {
        $request->merge(['tipo' => 'despesa']);
        $movimentacoes = MovimentacaoStore::filtrar($request)->where('tipo', 'despesa');

        return view('relatorios.despesas', [
            'movimentacoes' => $movimentacoes,
            'total' => $movimentacoes->sum(fn ($m) => abs($m['valor'])),
            'categorias' => MockData::categorias(),
            'contas' => MockData::contas(),
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id']),
        ]);
    }

    public function fluxo(Request $request)
    {
        $movimentacoes = MovimentacaoStore::filtrar($request);

        return view('relatorios.fluxo', [
            'movimentacoes' => $movimentacoes,
            'grafico' => MovimentacaoStore::graficoReceitasDespesas($request),
            'categorias' => MockData::categorias(),
            'contas' => MockData::contas(),
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id']),
            'resumo' => MovimentacaoStore::resumo($movimentacoes),
        ]);
    }

    public function exportarPdf(Request $request)
    {
        return redirect()->back()->with('toast', [
            'type' => 'info',
            'message' => 'Exportação PDF simulada — '.MovimentacaoStore::filtrar($request)->count().' lançamento(s) seriam incluídos.',
        ]);
    }
}
