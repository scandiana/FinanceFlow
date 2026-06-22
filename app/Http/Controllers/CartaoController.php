<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartaoController extends Controller
{
    private string $apiUrl = 'http://127.0.0.1:8001/api/cards';
    private string $transactionsApiUrl = 'http://127.0.0.1:8001/api/transactions';
    private string $categoriesApiUrl = 'http://127.0.0.1:8001/api/categories';
    private string $accountsApiUrl = 'http://127.0.0.1:8001/api/bank-accounts';

    public function index()
    {
        $response = Http::get($this->apiUrl);

        $cartoes = $response->successful()
            ? $response->json()
            : [];

        return view('cartoes.index', compact('cartoes'));
    }

    public function show(int $cartao)
    {
        $response = Http::get($this->apiUrl . '/' . $cartao);

        abort_unless($response->successful(), 404);

        $cartaoData = $response->json();

        return view('cartoes.show', [
            'cartao' => $cartaoData,
            'compras' => $cartaoData['transactions'] ?? [],
        ]);
    }

    public function fatura(int $cartao)
    {
        $response = Http::get($this->apiUrl . '/' . $cartao);

        abort_unless($response->successful(), 404);

        $cartaoData = $response->json();

        return view('cartoes.fatura', [
            'cartao' => $cartaoData,
            'compras' => $cartaoData['transactions'] ?? [],
        ]);
    }

    public function createCompra(int $cartao)
    {
        $response = Http::get($this->apiUrl . '/' . $cartao);

        abort_unless($response->successful(), 404);

        $categorias = collect($this->getJson($this->categoriesApiUrl))
            ->where('tipo', 'despesa')
            ->values();

        return view('cartoes.compra-form', [
            'cartao' => $response->json(),
            'categorias' => $categorias,
            'contas' => $this->getJson($this->accountsApiUrl),
        ]);
    }

    public function storeCompra(Request $request, int $cartao)
{
    $request->validate([
        'descricao' => 'required|string|max:255',
        'valor' => 'required|numeric|min:0.01',
        'parcelas' => 'nullable|integer|min:1|max:12',
        'data' => 'required|date',
        'categoria_id' => 'required|integer',
        'conta_id' => 'required|integer',
    ]);

    $cardResponse = Http::get($this->apiUrl . '/' . $cartao);

    if (! $cardResponse->successful()) {
        return back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => 'Erro ao buscar dados do cartão.'
        ]);
    }

    $cardData = $cardResponse->json();

    $compras = collect($cardData['transactions'] ?? []);
    $faturaAtual = $compras->sum(fn ($compra) => (float) ($compra['valor'] ?? 0));
    $limite = (float) ($cardData['limite'] ?? 0);
    $valorCompra = (float) $request->valor;
    $disponivel = $limite - $faturaAtual;

    if ($valorCompra > $disponivel) {
        return back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => 'Compra não registrada: valor acima do limite disponível. Disponível: R$ ' . number_format($disponivel, 2, ',', '.')
        ]);
    }

    $response = Http::post($this->transactionsApiUrl, [
        'tipo' => 'despesa',
        'descricao' => $request->descricao,
        'valor' => $valorCompra,
        'data' => $request->data,
        'categoria_id' => $request->categoria_id,
        'conta_id' => $request->conta_id,
        'card_id' => $cartao,
        'observacoes' => 'Compra no cartão em ' . ($request->parcelas ?? 1) . 'x',
    ]);

    if ($response->successful()) {
        return redirect()
            ->route('cartoes.show', $cartao)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Compra registrada com sucesso.'
            ]);
    }

    return back()->withInput()->with('toast', [
        'type' => 'error',
        'message' => 'Erro ao registrar compra.'
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