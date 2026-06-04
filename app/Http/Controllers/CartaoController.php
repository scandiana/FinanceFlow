<?php

namespace App\Http\Controllers;

use App\Data\MockData;
use Illuminate\Http\Request;

class CartaoController extends Controller
{
    public function index()
    {
        return view('cartoes.index', ['cartoes' => MockData::cartoes()]);
    }

    public function show(int $cartao)
    {
        $cartaoData = MockData::cartao($cartao);
        abort_unless($cartaoData, 404);

        return view('cartoes.show', [
            'cartao' => $cartaoData,
            'compras' => MockData::comprasCartao($cartao),
        ]);
    }

    public function fatura(int $cartao)
    {
        $cartaoData = MockData::cartao($cartao);
        abort_unless($cartaoData, 404);

        return view('cartoes.fatura', [
            'cartao' => $cartaoData,
            'compras' => MockData::comprasCartao($cartao),
        ]);
    }

    public function createCompra(int $cartao)
    {
        $cartaoData = MockData::cartao($cartao);
        abort_unless($cartaoData, 404);

        return view('cartoes.compra-form', ['cartao' => $cartaoData]);
    }

    public function storeCompra(Request $request, int $cartao)
    {
        return redirect()->route('cartoes.show', $cartao)->with('toast', ['type' => 'success', 'message' => 'Compra registrada (simulação).']);
    }
}
