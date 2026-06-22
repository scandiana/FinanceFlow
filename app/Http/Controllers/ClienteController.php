<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClienteController extends Controller
{
    private string $apiUrl = 'http://127.0.0.1:8001/api/clients';

    public function index()
    {
        $response = Http::get($this->apiUrl);

        $clientes = $response->successful()
            ? $response->json()
            : [];

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.form', ['cliente' => null]);
    }

    public function store(Request $request)
    {
        $response = Http::post($this->apiUrl, [
            'nome' => $request->nome,
            'documento' => $request->documento,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'cidade' => $request->cidade,
            'status' => $request->status ?? 'ativo',
            'total_recebido' => $request->total_recebido ?? 0,
            'pendente' => $request->pendente ?? 0,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route('clientes.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Cliente cadastrado com sucesso.'
                ]);
        }

        return back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => 'Erro ao cadastrar cliente.'
        ]);
    }

    public function show(int $cliente)
    {
        $response = Http::get($this->apiUrl . '/' . $cliente);

        abort_unless($response->successful(), 404);

        return view('clientes.show', [
            'cliente' => $response->json(),
            'pagamentos' => [],
            'historico' => [],
        ]);
    }

    public function edit(int $cliente)
    {
        $response = Http::get($this->apiUrl . '/' . $cliente);

        abort_unless($response->successful(), 404);

        return view('clientes.form', [
            'cliente' => $response->json()
        ]);
    }

    public function update(Request $request, int $cliente)
    {
        $response = Http::put($this->apiUrl . '/' . $cliente, [
            'nome' => $request->nome,
            'documento' => $request->documento,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'cidade' => $request->cidade,
            'status' => $request->status ?? 'ativo',
            'total_recebido' => $request->total_recebido ?? 0,
            'pendente' => $request->pendente ?? 0,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route('clientes.show', $cliente)
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Cliente atualizado com sucesso.'
                ]);
        }

        return back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => 'Erro ao atualizar cliente.'
        ]);
    }

    public function destroy(int $cliente)
    {
        $response = Http::delete($this->apiUrl . '/' . $cliente);

        if ($response->successful()) {
            return redirect()
                ->route('clientes.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Cliente excluído com sucesso.'
                ]);
        }

        return redirect()
            ->route('clientes.index')
            ->with('toast', [
                'type' => 'error',
                'message' => 'Erro ao excluir cliente.'
            ]);
    }
}