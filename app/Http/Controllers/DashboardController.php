<?php

namespace App\Http\Controllers;

use App\Data\MovimentacaoStore;
use App\Data\MockData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mesAtual = MovimentacaoStore::filtrar(new Request([
            'data_inicio' => now()->startOfMonth()->toDateString(),
            'data_fim' => now()->endOfMonth()->toDateString(),
        ]));

        return view('dashboard.index', [
            'resumo' => MockData::dashboardResumo(),
            'movimentacoes' => $mesAtual->take(5)->all(),
            'vencimentos' => MockData::vencimentos(),
            'alertas' => MockData::alertas(),
            'contas' => MockData::contas(),
            'graficoBarras' => MovimentacaoStore::graficoReceitasDespesas(),
            'graficoLinha' => MockData::graficoFluxoCaixa(),
        ]);
    }
}
