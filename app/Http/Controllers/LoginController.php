<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    private array $usuariosAutorizados = [
        [
            'id' => 1,
            'nome' => 'Admin',
            'email' => 'admin@financeflow.com',
            'senha' => 'admin123',
            'perfil' => 'administrador',
            'iniciais' => 'AD',
        ],
        [
            'id' => 2,
            'nome' => 'Financeiro',
            'email' => 'financeiro@financeflow.com',
            'senha' => '123456',
            'perfil' => 'financeiro',
            'iniciais' => 'FN',
        ],
        [
            'id' => 3,
            'nome' => 'Visualizador',
            'email' => 'viewer@financeflow.com',
            'senha' => '123456',
            'perfil' => 'visualizacao',
            'iniciais' => 'VZ',
        ],
    ];

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $senha = $request->input('senha');

        $usuario = collect($this->usuariosAutorizados)
            ->firstWhere(fn ($u) => $u['email'] === $email && $u['senha'] === $senha);

        if ($usuario) {
            session([
                'usuario_logado' => [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'perfil' => $usuario['perfil'],
                    'iniciais' => $usuario['iniciais'],
                ],
                'perfil' => $usuario['perfil'],
            ]);

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'E-mail ou senha inválidos.']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }
}
