<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoriaController extends Controller
{
    private string $apiUrl = 'http://127.0.0.1:8001/api/categories';

    public function index()
    {
        $response = Http::get($this->apiUrl);

        $categorias = $response->successful()
            ? $response->json()
            : [];

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.form', ['categoria' => null]);
    }

    public function store(Request $request)
    {
        $response = Http::post($this->apiUrl, [
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'descricao' => $request->descricao,
            'cor' => $request->cor,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route('categorias.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Categoria criada com sucesso.'
                ]);
        }

        return back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => 'Erro ao criar categoria.'
        ]);
    }

    public function edit(int $categoria)
    {
        $response = Http::get($this->apiUrl . '/' . $categoria);

        abort_unless($response->successful(), 404);

        return view('categorias.form', [
            'categoria' => $response->json()
        ]);
    }

    public function update(Request $request, int $categoria)
    {
        $response = Http::put($this->apiUrl . '/' . $categoria, [
            'nome' => $request->nome,
            'tipo' => $request->tipo,
            'descricao' => $request->descricao,
            'cor' => $request->cor,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route('categorias.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Categoria atualizada com sucesso.'
                ]);
        }

        return back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => 'Erro ao atualizar categoria.'
        ]);
    }

    public function destroy(int $categoria)
    {
        $response = Http::delete($this->apiUrl . '/' . $categoria);

        if ($response->successful()) {
            return redirect()
                ->route('categorias.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Categoria excluída com sucesso.'
                ]);
        }

        return redirect()
            ->route('categorias.index')
            ->with('toast', [
                'type' => 'error',
                'message' => 'Erro ao excluir categoria.'
            ]);
    }
}