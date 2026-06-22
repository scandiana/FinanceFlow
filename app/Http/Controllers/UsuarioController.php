<?php

namespace App\Http\Controllers;

use App\Data\MockData;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuarios.index', [
            'usuarios' => MockData::usuarios(),
            'perfis' => MockData::perfis(),
        ]);
    }

    public function create()
    {
        return view('usuarios.form', ['usuario' => null, 'perfis' => MockData::perfis()]);
    }

    public function store(Request $request)
    {
        return redirect()->route('usuarios.index')->with('toast', ['type' => 'success', 'message' => 'Usuário cadastrado (simulação).']);
    }

    public function edit(int $usuario)
    {
        $usuarioData = MockData::usuario($usuario);
        abort_unless($usuarioData, 404);

        return view('usuarios.form', ['usuario' => $usuarioData, 'perfis' => MockData::perfis()]);
    }

    public function update(Request $request, int $usuario)
    {
        return redirect()->route('usuarios.index')->with('toast', ['type' => 'success', 'message' => 'Usuário atualizado (simulação).']);
    }

    public function destroy(int $usuario)
    {
        return redirect()->route('usuarios.index')->with('toast', ['type' => 'success', 'message' => 'Usuário excluído (simulação).']);
    }

    public function trocarPerfil(Request $request)
    {
        $request->validate(['perfil' => 'required|in:administrador,financeiro,visualizacao']);
        session(['perfil' => $request->perfil]);

        return redirect()->back()->with('toast', ['type' => 'info', 'message' => 'Perfil simulado alterado para: '.MockData::perfis()[$request->perfil]]);
    }
}
