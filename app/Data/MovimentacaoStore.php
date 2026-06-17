<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Armazena movimentações na sessão (protótipo) e projeta recorrências mensais fixas.
 */
class MovimentacaoStore
{
    private const SESSION_KEY = 'movimentacoes';

    private const SESSION_NEXT_ID = 'movimentacoes_next_id';

    /** @return array<int, array<string, mixed>> */
    public static function fixasDefinicoes(): array
    {
        return [
            [
                'fixa_id' => 1,
                'descricao' => 'Salários Funcionários',
                'dia' => 1,
                'valor' => 28000,
                'tipo' => 'despesa',
                'categoria_id' => 3,
                'conta_id' => 1,
                'cliente_id' => null,
            ],
            [
                'fixa_id' => 2,
                'descricao' => 'Aluguel Escritório',
                'dia' => 5,
                'valor' => 3500,
                'tipo' => 'despesa',
                'categoria_id' => 5,
                'conta_id' => 1,
                'cliente_id' => null,
            ],
            [
                'fixa_id' => 3,
                'descricao' => 'Hospedagem / Servidores Cloud',
                'dia' => 5,
                'valor' => 2400,
                'tipo' => 'despesa',
                'categoria_id' => 6,
                'conta_id' => 1,
                'cliente_id' => null,
            ],
            [
                'fixa_id' => 4,
                'descricao' => 'Mensalidade Cliente XYZ',
                'dia' => 10,
                'valor' => 15000,
                'tipo' => 'receita',
                'categoria_id' => 1,
                'conta_id' => 1,
                'cliente_id' => 1,
            ],
            [
                'fixa_id' => 5,
                'descricao' => 'Retainer Tech Solutions',
                'dia' => 15,
                'valor' => 8500,
                'tipo' => 'receita',
                'categoria_id' => 2,
                'conta_id' => 1,
                'cliente_id' => 2,
            ],
            [
                'fixa_id' => 6,
                'descricao' => 'Assinaturas de Software',
                'dia' => 15,
                'valor' => 890,
                'tipo' => 'despesa',
                'categoria_id' => 6,
                'conta_id' => 1,
                'cliente_id' => null,
            ],
            [
                'fixa_id' => 7,
                'descricao' => 'Energia e Internet',
                'dia' => 20,
                'valor' => 1200,
                'tipo' => 'despesa',
                'categoria_id' => 5,
                'conta_id' => 1,
                'cliente_id' => null,
            ],
        ];
    }

    public static function seed(): array
    {
        return [
            ['id' => 1, 'data' => '2026-06-02', 'descricao' => 'Pagamento Cliente XYZ (projeto)', 'categoria_id' => 1, 'conta_id' => 1, 'cliente_id' => 1, 'valor' => 5000, 'tipo' => 'receita', 'status' => 'concluido', 'recorrente' => false],
            ['id' => 2, 'data' => '2026-05-31', 'descricao' => 'Fornecedor ABC - Material', 'categoria_id' => 4, 'conta_id' => 1, 'cliente_id' => null, 'valor' => -5400, 'tipo' => 'despesa', 'status' => 'concluido', 'recorrente' => false],
            ['id' => 3, 'data' => '2026-05-30', 'descricao' => 'Consultoria TI - Projeto Alpha', 'categoria_id' => 2, 'conta_id' => 3, 'cliente_id' => 2, 'valor' => 8500, 'tipo' => 'receita', 'status' => 'concluido', 'recorrente' => false],
            ['id' => 4, 'data' => '2026-05-28', 'descricao' => 'Venda Produto Premium', 'categoria_id' => 1, 'conta_id' => 2, 'cliente_id' => 3, 'valor' => 12000, 'tipo' => 'receita', 'status' => 'pendente', 'recorrente' => false],
            ['id' => 5, 'data' => '2026-05-24', 'descricao' => 'Marketing Digital', 'categoria_id' => 4, 'conta_id' => 1, 'cliente_id' => null, 'valor' => -2100, 'tipo' => 'despesa', 'status' => 'concluido', 'recorrente' => false],
        ];
    }

    public static function boot(): void
    {
        if (! session()->has(self::SESSION_KEY)) {
            session([
                self::SESSION_KEY => self::enrichMany(self::seed()),
                self::SESSION_NEXT_ID => 100,
            ]);
        }
    }

    public static function all(?Request $request = null): Collection
    {
        self::boot();

        $avulsas = collect(session(self::SESSION_KEY, []));
        $fixas = collect(self::gerarFixas($request));

        return $avulsas->merge($fixas)
            ->map(fn ($m) => self::enrich($m))
            ->sortBy([
                ['data', 'desc'],
                ['id', 'desc'],
            ])
            ->values();
    }

    public static function filtrar(?Request $request = null): Collection
    {
        $items = self::all($request);

        if (! $request) {
            return $items;
        }

        if ($request->filled('data_inicio')) {
            $items = $items->filter(fn ($m) => $m['data'] >= $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $items = $items->filter(fn ($m) => $m['data'] <= $request->data_fim);
        }

        if ($request->filled('categoria_id')) {
            $items = $items->filter(fn ($m) => (int) $m['categoria_id'] === (int) $request->categoria_id);
        }

        if ($request->filled('conta_id')) {
            $items = $items->filter(fn ($m) => (int) $m['conta_id'] === (int) $request->conta_id);
        }

        if ($request->filled('tipo')) {
            $items = $items->filter(fn ($m) => $m['tipo'] === $request->tipo);
        }

        if ($q = $request->get('q')) {
            $q = mb_strtolower($q);
            $items = $items->filter(function ($m) use ($q) {
                return str_contains(mb_strtolower($m['descricao']), $q)
                    || str_contains(mb_strtolower($m['categoria']), $q)
                    || str_contains(mb_strtolower($m['conta']), $q);
            });
        }

        return $items->values();
    }

    public static function find(string|int $id): ?array
    {
        return self::all()->first(function ($m) use ($id) {
            return (string) $m['id'] === (string) $id;
        });
    }

    public static function create(array $input): array
    {
        self::boot();

        $tipo = $input['tipo'] ?? 'receita';
        $valor = abs((float) $input['valor']);
        if ($tipo === 'despesa') {
            $valor = -$valor;
        }

        $id = session(self::SESSION_NEXT_ID, 100);
        session([self::SESSION_NEXT_ID => $id + 1]);

        $mov = self::enrich([
            'id' => $id,
            'data' => $input['data'] ?? now()->toDateString(),
            'descricao' => $input['descricao'],
            'categoria_id' => (int) $input['categoria_id'],
            'conta_id' => (int) $input['conta_id'],
            'cliente_id' => ! empty($input['cliente_id']) ? (int) $input['cliente_id'] : null,
            'valor' => $valor,
            'tipo' => $tipo,
            'status' => $input['status'] ?? 'concluido',
            'recorrente' => false,
            'usuario_id' => MockData::usuarioLogado()['id'],
            'nota_fiscais' => collect($input['nota_fiscais'] ?? [])
    ->filter()
    ->map(function ($file) {

        // se vier arquivo do upload
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $name = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/nfs'), $name);

            return $name;
        }

        // fallback (caso já venha string)
        return $file;
    })
    ->values()
    ->toArray(),
        ]);

        $lista = session(self::SESSION_KEY, []);
        $lista[] = $mov;
        session([self::SESSION_KEY => $lista]);

        return $mov;
    }

    public static function update(string|int $id, array $input): ?array
    {
        if (self::isFixa($id)) {
            return null;
        }

        self::boot();
        $lista = collect(session(self::SESSION_KEY, []));
        $index = $lista->search(fn ($m) => (string) $m['id'] === (string) $id);

        if ($index === false) {
            return null;
        }

        $atual = $lista[$index];
        $tipo = $input['tipo'] ?? $atual['tipo'];
        $valor = abs((float) ($input['valor'] ?? abs($atual['valor'])));
        if ($tipo === 'despesa') {
            $valor = -$valor;
        }

        $atualizado = self::enrich(array_merge($atual, [
            'descricao' => $input['descricao'] ?? $atual['descricao'],
            'data' => $input['data'] ?? $atual['data'],
            'categoria_id' => (int) ($input['categoria_id'] ?? $atual['categoria_id']),
            'conta_id' => (int) ($input['conta_id'] ?? $atual['conta_id']),
            'cliente_id' => array_key_exists('cliente_id', $input)
                ? ($input['cliente_id'] ? (int) $input['cliente_id'] : null)
                : ($atual['cliente_id'] ?? null),
            'valor' => $valor,
            'tipo' => $tipo,
            'status' => $input['status'] ?? $atual['status'],
            'nota_fiscais' => $input['nota_fiscais'] ?? ($atual['nota_fiscais'] ?? []),      ]));

        $lista[$index] = $atualizado;
        session([self::SESSION_KEY => $lista->values()->all()]);

        return $atualizado;
    }

    public static function delete(string|int $id): bool
    {
        if (self::isFixa($id)) {
            return false;
        }

        self::boot();
        $lista = collect(session(self::SESSION_KEY, []))
            ->reject(fn ($m) => (string) $m['id'] === (string) $id)
            ->values()
            ->all();

        session([self::SESSION_KEY => $lista]);

        return true;
    }

    public static function resumo(Collection $items): array
    {
        $receitas = $items->where('tipo', 'receita')->sum(fn ($m) => abs($m['valor']));
        $despesas = $items->where('tipo', 'despesa')->sum(fn ($m) => abs($m['valor']));

        return [
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo' => $receitas - $despesas,
        ];
    }

    public static function resumoMesAtual(): array
    {
        $inicio = now()->startOfMonth()->toDateString();
        $fim = now()->endOfMonth()->toDateString();

        $items = self::filtrar(new Request([
            'data_inicio' => $inicio,
            'data_fim' => $fim,
        ]));

        return self::resumo($items);
    }

    public static function graficoReceitasDespesas(?Request $request = null): array
    {
        $items = self::filtrar($request);
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $ano = now()->year;

        $dados = [];
        for ($m = 1; $m <= 12; $m++) {
            $inicio = sprintf('%04d-%02d-01', $ano, $m);
            $fim = Carbon::parse($inicio)->endOfMonth()->toDateString();
            $doMes = $items->filter(fn ($i) => $i['data'] >= $inicio && $i['data'] <= $fim);
            $dados[] = [
                'mes' => $meses[$m - 1],
                'receitas' => (int) $doMes->where('tipo', 'receita')->sum(fn ($i) => abs($i['valor'])),
                'despesas' => (int) $doMes->where('tipo', 'despesa')->sum(fn ($i) => abs($i['valor'])),
            ];
        }

        return $dados;
    }

    public static function isFixa(string|int $id): bool
    {
        return is_string($id) && str_starts_with($id, 'fixa-');
    }

    public static function podeEditar(string|int $id): bool
    {
        return ! self::isFixa($id);
    }

    public static function podeExcluir(string|int $id): bool
    {
        return ! self::isFixa($id);
    }

    private static function periodoFixas(?Request $request): array
    {
        if ($request?->filled('data_inicio') && $request?->filled('data_fim')) {
            return [
                Carbon::parse($request->data_inicio)->startOfMonth(),
                Carbon::parse($request->data_fim)->endOfMonth(),
            ];
        }

        if ($request?->filled('data_inicio')) {
            return [
                Carbon::parse($request->data_inicio)->startOfMonth(),
                Carbon::parse($request->data_inicio)->copy()->addMonths(2)->endOfMonth(),
            ];
        }

        if ($request?->filled('data_fim')) {
            return [
                Carbon::parse($request->data_fim)->copy()->subMonths(2)->startOfMonth(),
                Carbon::parse($request->data_fim)->endOfMonth(),
            ];
        }

        return [
            now()->copy()->subMonths(5)->startOfMonth(),
            now()->copy()->addMonth()->endOfMonth(),
        ];
    }

    private static function gerarFixas(?Request $request): array
    {
        [$inicio, $fim] = self::periodoFixas($request);
        $geradas = [];

        foreach (self::fixasDefinicoes() as $def) {
            $cursor = $inicio->copy()->startOfMonth();
            while ($cursor <= $fim) {
                $dia = min($def['dia'], $cursor->daysInMonth);
                $data = $cursor->copy()->day($dia)->toDateString();

                if ($data >= $inicio->toDateString() && $data <= $fim->toDateString()) {
                    $valor = abs($def['valor']);
                    if ($def['tipo'] === 'despesa') {
                        $valor = -$valor;
                    }

                    $geradas[] = [
                        'id' => 'fixa-'.$def['fixa_id'].'-'.$cursor->format('Y-m'),
                        'data' => $data,
                        'descricao' => $def['descricao'],
                        'categoria_id' => $def['categoria_id'],
                        'conta_id' => $def['conta_id'],
                        'cliente_id' => $def['cliente_id'],
                        'valor' => $valor,
                        'tipo' => $def['tipo'],
                        'status' => 'agendado',
                        'recorrente' => true,
                        'fixa_id' => $def['fixa_id'],
                    ];
                }
                $cursor->addMonth();
            }
        }

        return $geradas;
    }

    private static function enrichMany(array $items): array
    {
        return array_map(fn ($m) => self::enrich($m), $items);
    }

    private static function enrich(array $m): array
    {
        $categoria = MockData::categoria((int) $m['categoria_id']);
        $conta = MockData::conta((int) $m['conta_id']);
        $user = MockData::usuarioLogado();

        return array_merge($m, [
            'categoria' => $categoria['nome'] ?? '—',
            'conta' => $conta['nome'] ?? '—',
            'usuario' => $user['nome'],
            'usuario_id' => $m['usuario_id'] ?? $user['id'],
            'recorrente' => $m['recorrente'] ?? false,
        ]);
    }
}
