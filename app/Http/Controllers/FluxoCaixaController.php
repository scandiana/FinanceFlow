<?php

namespace App\Http\Controllers;

use App\Data\MovimentacaoStore;
use App\Data\MockData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FluxoCaixaController extends Controller
{
    public function index(Request $request)
    {
        $items = MovimentacaoStore::filtrar($request);

        $page = max(1, (int) $request->get('page', 1));
        $perPage = 8;
        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => route('fluxo-caixa.index')]
        );
        $paginator->appends($request->query());

        return view('fluxo-caixa.index', [
            'movimentacoes' => $paginator,
            'resumo' => MovimentacaoStore::resumo($items),
            'categorias' => MockData::categorias(),
            'contas' => MockData::contas(),
            'filtrosAtivos' => $request->hasAny(['data_inicio', 'data_fim', 'categoria_id', 'conta_id', 'tipo', 'q']),
            'fixas' => MovimentacaoStore::fixasDefinicoes(),
        ]);
    }

    public function create(Request $request)
    {
        $tipo = $request->get('tipo', 'receita');

        return view('fluxo-caixa.form', [
            'movimentacao' => null,
            'tipo' => $tipo,
            'categorias' => collect(MockData::categorias())->where('tipo', $tipo === 'receita' ? 'receita' : 'despesa'),
            'contas' => MockData::contas(),
            'clientes' => MockData::clientes(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'categoria_id' => 'required|integer',
            'conta_id' => 'required|integer',
            'tipo' => 'required|in:receita,despesa',
        ]);

        MovimentacaoStore::create($request->only([
            'descricao', 'valor', 'data', 'categoria_id', 'conta_id', 'cliente_id', 'tipo',
        ]));

        return redirect()->route('fluxo-caixa.index')->with('toast', [
            'type' => 'success',
            'message' => 'Movimentação registrada e incluída nos relatórios.',
        ]);
    }

    public function show(string $id)
    {
        $movimentacao = MovimentacaoStore::find($id);
        abort_unless($movimentacao, 404);

        return view('fluxo-caixa.show', [
            'movimentacao' => $movimentacao,
            'ehFixa' => MovimentacaoStore::isFixa($id),
        ]);
    }

    public function edit(string $id)
    {
        if (MovimentacaoStore::isFixa($id)) {
            return redirect()->route('fluxo-caixa.show', $id)->with('toast', [
                'type' => 'info',
                'message' => 'Movimentações fixas mensais são geradas automaticamente e não podem ser editadas aqui.',
            ]);
        }

        $movimentacao = MovimentacaoStore::find($id);
        abort_unless($movimentacao, 404);

        return view('fluxo-caixa.form', [
            'movimentacao' => $movimentacao,
            'tipo' => $movimentacao['tipo'],
            'categorias' => collect(MockData::categorias())->where('tipo', $movimentacao['tipo']),
            'contas' => MockData::contas(),
            'clientes' => MockData::clientes(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        if (MovimentacaoStore::isFixa($id)) {
            return redirect()->route('fluxo-caixa.index')->with('toast', [
                'type' => 'info',
                'message' => 'Não é possível editar uma movimentação fixa mensal.',
            ]);
        }

        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'categoria_id' => 'required|integer',
            'conta_id' => 'required|integer',
        ]);

        MovimentacaoStore::update($id, $request->all());

        return redirect()->route('fluxo-caixa.show', $id)->with('toast', [
            'type' => 'success',
            'message' => 'Movimentação atualizada.',
        ]);
    }

    public function destroy(string $id)
    {
        if (MovimentacaoStore::isFixa($id) || ! MovimentacaoStore::delete($id)) {
            return redirect()->route('fluxo-caixa.index')->with('toast', [
                'type' => 'info',
                'message' => 'Esta movimentação fixa mensal não pode ser excluída individualmente.',
            ]);
        }

        return redirect()->route('fluxo-caixa.index')->with('toast', [
            'type' => 'success',
            'message' => 'Movimentação excluída.',
        ]);
    }
}
