<?php

namespace App\Http\Controllers;

use App\Data\MockData;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function index()
    {
        return view('contas.index', [
            'contas' => MockData::contas(),
            'saldoTotal' => collect(MockData::contas())->sum('saldo'),
        ]);
    }

    public function show(int $conta)
    {
        $contaData = MockData::conta($conta);
        abort_unless($contaData, 404);

        return view('contas.show', [
            'conta' => $contaData,
            'movimentacoes' => MockData::movimentacoesConta($conta),
            'contas' => MockData::contas(),
        ]);
    }

    public function transferencia()
    {
        return view('contas.transferencia', ['contas' => MockData::contas()]);
    }

    public function transferir(Request $request)
    {
        return redirect()->route('contas.index')->with('toast', ['type' => 'success', 'message' => 'Transferência realizada com sucesso (simulação).']);
    }
}
