<?php

namespace App\Http\Controllers;

use App\Data\MockData;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return view('clientes.index', ['clientes' => MockData::clientes()]);
    }

    public function create()
    {
        return view('clientes.form', ['cliente' => null]);
    }

    public function store(Request $request)
    {
        return redirect()->route('clientes.index')->with('toast', ['type' => 'success', 'message' => 'Cliente cadastrado (simulação).']);
    }

    public function show(int $cliente)
    {
        $clienteData = MockData::cliente($cliente);
        abort_unless($clienteData, 404);

        return view('clientes.show', [
            'cliente' => $clienteData,
            'pagamentos' => MockData::pagamentosCliente($cliente),
            'historico' => MockData::pagamentosCliente($cliente),
        ]);
    }

    public function edit(int $cliente)
    {
        $clienteData = MockData::cliente($cliente);
        abort_unless($clienteData, 404);

        return view('clientes.form', ['cliente' => $clienteData]);
    }

    public function update(Request $request, int $cliente)
    {
        return redirect()->route('clientes.show', $cliente)->with('toast', ['type' => 'success', 'message' => 'Cliente atualizado (simulação).']);
    }

    public function destroy(int $cliente)
    {
        return redirect()->route('clientes.index')->with('toast', ['type' => 'success', 'message' => 'Cliente excluído (simulação).']);
    }
}
