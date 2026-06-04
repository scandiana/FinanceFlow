<?php

namespace App\Http\Controllers;

use App\Data\MockData;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return view('categorias.index', ['categorias' => MockData::categorias()]);
    }

    public function create()
    {
        return view('categorias.form', ['categoria' => null]);
    }

    public function store(Request $request)
    {
        return redirect()->route('categorias.index')->with('toast', ['type' => 'success', 'message' => 'Categoria criada (simulação).']);
    }

    public function edit(int $categoria)
    {
        $categoriaData = MockData::categoria($categoria);
        abort_unless($categoriaData, 404);

        return view('categorias.form', ['categoria' => $categoriaData]);
    }

    public function update(Request $request, int $categoria)
    {
        return redirect()->route('categorias.index')->with('toast', ['type' => 'success', 'message' => 'Categoria atualizada (simulação).']);
    }

    public function destroy(int $categoria)
    {
        return redirect()->route('categorias.index')->with('toast', ['type' => 'success', 'message' => 'Categoria excluída (simulação).']);
    }
}
