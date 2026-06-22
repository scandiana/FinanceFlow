<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class FluxoCaixaController extends Controller
{
    private string $apiUrl = 'http://127.0.0.1:8001/api/transactions';
    private string $categoriesApiUrl = 'http://127.0.0.1:8001/api/categories';
    private string $accountsApiUrl = 'http://127.0.0.1:8001/api/bank-accounts';
    private string $clientsApiUrl = 'http://127.0.0.1:8001/api/clients';

    public function index(Request $request)
    {
        $response = Http::get($this->apiUrl, $request->query());

        $items = collect($response->successful() ? $response->json() : []);

        $page = max(1, (int) $request->get('page', 1));
        $perPage = 8;

        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => route('fluxo-caixa.index')]
        );

        $paginator->appends($request->query());

        $receitas = $items->where('tipo', 'receita')->sum('valor');
        $despesas = $items->where('tipo', 'despesa')->sum('valor');

        return view('fluxo-caixa.index', [
            'movimentacoes' => $paginator,
            'resumo' => [
                'receitas' => $receitas,
                'despesas' => $despesas,
                'saldo' => $receitas - $despesas,
            ],
            'categorias' => $this->getJson($this->categoriesApiUrl),
            'contas' => $this->getJson($this->accountsApiUrl),
            'filtrosAtivos' => $request->hasAny(['data_inicio', 'data_fim', 'categoria_id', 'conta_id', 'tipo', 'q']),
            'fixas' => [],
        ]);
    }

    public function create(Request $request)
    {
        $tipo = $request->get('tipo', 'receita');

        $categorias = collect($this->getJson($this->categoriesApiUrl))
            ->where('tipo', $tipo === 'receita' ? 'receita' : 'despesa')
            ->values();

        

        return view('fluxo-caixa.form', [
            'movimentacao' => null,
            'tipo' => $tipo,
            'categorias' => $categorias,
            'contas' => $this->getJson($this->accountsApiUrl),
            'clientes' => $this->getJson($this->clientsApiUrl),
        ]);
    }

    public function store(Request $request)
{
    $http = Http::asMultipart();

    if ($request->hasFile('nota_fiscal')) {
        $file = $request->file('nota_fiscal');

        $http = $http->attach(
            'nota_fiscal',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        );
    }

    $response = $http->post($this->apiUrl, [
        'descricao' => $request->descricao,
        'valor' => $request->valor,
        'data' => $request->data,
        'categoria_id' => $request->categoria_id,
        'conta_id' => $request->conta_id,
        'cliente_id' => $request->cliente_id,
        'tipo' => $request->tipo,
        'observacoes' => $request->observacoes,
    ]); 

        

    if ($response->successful()) {
        return redirect()
            ->route('fluxo-caixa.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Movimentação registrada com sucesso.'
            ]);
    }

    return back()->withInput()->with('toast', [
        'type' => 'error',
        'message' => 'Erro ao registrar movimentação.'
    ]);
}

    public function show(string $id)
    {
        $response = Http::get($this->apiUrl . '/' . $id);

        abort_unless($response->successful(), 404);

        return view('fluxo-caixa.show', [
            'movimentacao' => $response->json(),
            'ehFixa' => false,
        ]);
    }

    public function edit(string $id)
    {
        $response = Http::get($this->apiUrl . '/' . $id);

        abort_unless($response->successful(), 404);

        $movimentacao = $response->json();

        $categorias = collect($this->getJson($this->categoriesApiUrl))
            ->where('tipo', $movimentacao['tipo'])
            ->values();

        return view('fluxo-caixa.form', [
            'movimentacao' => $movimentacao,
            'tipo' => $movimentacao['tipo'],
            'categorias' => $categorias,
            'contas' => $this->getJson($this->accountsApiUrl),
            'clientes' => $this->getJson($this->clientsApiUrl),
        ]);
    }

   public function update(Request $request, string $id)
{
    $dados = [
        'descricao' => $request->descricao,
        'valor' => $request->valor,
        'data' => $request->data,
        'categoria_id' => $request->categoria_id,
        'conta_id' => $request->conta_id,
        'tipo' => $request->tipo,
        'observacoes' => $request->observacoes,
        'cliente_id' => $request->filled('cliente_id') ? $request->cliente_id : null,
        'card_id' => $request->filled('card_id') ? $request->card_id : null,
    ];

    $response = Http::put($this->apiUrl . '/' . $id, $dados);

    

    if ($response->successful()) {
        return redirect()
            ->route('fluxo-caixa.show', $id)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Movimentação atualizada.'
            ]);
    }

    return back()->withInput()->with('toast', [
        'type' => 'error',
        'message' => 'Erro ao atualizar movimentação.'
    ]);
}

    public function destroy(string $id)
    {
        $response = Http::delete($this->apiUrl . '/' . $id);

        if ($response->successful()) {
            return redirect()
                ->route('fluxo-caixa.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Movimentação excluída.'
                ]);
        }

        return redirect()
            ->route('fluxo-caixa.index')
            ->with('toast', [
                'type' => 'error',
                'message' => 'Erro ao excluir movimentação.'
            ]);
    }

    private function getJson(string $url): array
    {
        $response = Http::get($url);

        return $response->successful()
            ? $response->json()
            : [];
    }
}