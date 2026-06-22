<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private string $transactionsApiUrl = 'http://127.0.0.1:8001/api/transactions';
    private string $accountsApiUrl = 'http://127.0.0.1:8001/api/bank-accounts';

    public function index(Request $request)
    {
        $movimentacoes = collect($this->getJson($this->transactionsApiUrl));
        $contas = collect($this->getJson($this->accountsApiUrl));

        $mesAtual = $movimentacoes->filter(function ($m) {
            $data = $m['data'] ?? null;

            if (!$data) {
                return false;
            }

            return $data >= now()->startOfMonth()->toDateString()
                && $data <= now()->endOfMonth()->toDateString();
        });

        $receitas = $mesAtual->where('tipo', 'receita')->sum('valor');
        $despesas = $mesAtual->where('tipo', 'despesa')->sum('valor');
        $saldo = $receitas - $despesas;

        return view('dashboard.index', [
            'resumo' => [
                'receitas' => $receitas,
                'despesas' => $despesas,
                'saldo' => $saldo,
                'clientes' => 0,
            ],
            'movimentacoes' => $mesAtual
             ->sortByDesc('data')
             ->take(5)
             ->values()
             ->all(),
            'vencimentos' => [],
            'alertas' => [],
            'contas' => $contas->values()->all(),
            'graficoBarras' => [],
            'graficoLinha' => [],
        ]);
    }

    private function getJson(string $url): array
    {
        $response = Http::get($url);

        return $response->successful()
            ? $response->json()
            : [];
    }
}