<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContaController extends Controller
{
    private string $apiUrl = 'http://127.0.0.1:8001/api/bank-accounts';

    public function index()
    {
        $response = Http::get($this->apiUrl);

        $contas = $response->successful()
            ? $response->json()
            : [];

        $saldoTotal = collect($contas)->sum('saldo');

        return view('contas.index', compact('contas', 'saldoTotal'));
    }

    public function show(int $conta)
    {
        $response = Http::get($this->apiUrl . '/' . $conta);

        abort_unless($response->successful(), 404);

        return view('contas.show', [
            'conta' => $response->json(),
            'movimentacoes' => [],
            'contas' => [],
        ]);
    }

    public function transferencia()
    {
        $response = Http::get($this->apiUrl);

        $contas = $response->successful()
            ? $response->json()
            : [];

        return view('contas.transferencia', compact('contas'));
    }

    public function transferir(Request $request)
    {
        return redirect()
            ->route('contas.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Transferência ainda não integrada.'
            ]);
    }
}