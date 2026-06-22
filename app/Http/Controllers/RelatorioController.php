<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RelatorioController extends Controller
{
    private string $transactionsApiUrl = 'http://127.0.0.1:8001/api/transactions';
    private string $categoriesApiUrl = 'http://127.0.0.1:8001/api/categories';
    private string $accountsApiUrl = 'http://127.0.0.1:8001/api/bank-accounts';

    public function receitas(Request $request)
    {
        $movimentacoes = $this->filtrarMovimentacoes($request)
            ->where('tipo', 'receita')
            ->values();

        return view('relatorios.receitas', [
            'movimentacoes' => $movimentacoes,
            'total' => $movimentacoes->sum('valor'),
            'categorias' => $this->getJson($this->categoriesApiUrl),
            'contas' => $this->getJson($this->accountsApiUrl),
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id']),
        ]);
    }

    public function despesas(Request $request)
    {
        $movimentacoes = $this->filtrarMovimentacoes($request)
            ->where('tipo', 'despesa')
            ->values();

        return view('relatorios.despesas', [
            'movimentacoes' => $movimentacoes,
            'total' => $movimentacoes->sum('valor'),
            'categorias' => $this->getJson($this->categoriesApiUrl),
            'contas' => $this->getJson($this->accountsApiUrl),
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id']),
        ]);
    }

    public function fluxo(Request $request)
    {
        $movimentacoes = $this->filtrarMovimentacoes($request);

        $receitas = $movimentacoes->where('tipo', 'receita')->sum('valor');
        $despesas = $movimentacoes->where('tipo', 'despesa')->sum('valor');

        return view('relatorios.fluxo', [
            'movimentacoes' => $movimentacoes,
            'grafico' => [],
            'categorias' => $this->getJson($this->categoriesApiUrl),
            'contas' => $this->getJson($this->accountsApiUrl),
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id']),
            'resumo' => [
                'receitas' => $receitas,
                'despesas' => $despesas,
                'saldo' => $receitas - $despesas,
            ],
        ]);
    }

    public function exportarPdf(Request $request)
    {
        return redirect()->route('relatorios.impressao', $request->all());
    }

    public function impressao(Request $request)
    {
        $movimentacoes = $this->filtrarMovimentacoes($request);

        if ($request->filled('tipo')) {
            $movimentacoes = $movimentacoes
                ->where('tipo', $request->tipo)
                ->values();
        }

        $receitas = $movimentacoes->where('tipo', 'receita')->sum('valor');
        $despesas = $movimentacoes->where('tipo', 'despesa')->sum('valor');

        return view('relatorios.impressao', [
            'tipo' => $request->tipo ?? 'fluxo',
            'movimentacoes' => $movimentacoes,
            'resumo' => [
                'receitas' => $receitas,
                'despesas' => $despesas,
                'saldo' => $receitas - $despesas,
            ],
            'filtros' => $request->only(['data_inicio', 'data_fim', 'categoria_id', 'conta_id', 'tipo']),
        ]);
    }

    private function filtrarMovimentacoes(Request $request)
    {
        return collect($this->getJson($this->transactionsApiUrl))
            ->filter(function ($m) use ($request) {
                if ($request->filled('data_inicio') && ($m['data'] ?? '') < $request->data_inicio) {
                    return false;
                }

                if ($request->filled('data_fim') && ($m['data'] ?? '') > $request->data_fim) {
                    return false;
                }

                if ($request->filled('categoria_id') && (string)($m['categoria_id'] ?? '') !== (string)$request->categoria_id) {
                    return false;
                }

                if ($request->filled('conta_id') && (string)($m['conta_id'] ?? '') !== (string)$request->conta_id) {
                    return false;
                }

                return true;
            })
            ->sortByDesc('data')
            ->values();
    }

    private function getJson(string $url): array
    {
        $response = Http::get($url);

        return $response->successful()
            ? $response->json()
            : [];
    }
}