@php
    $menu = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ['route' => 'fluxo-caixa.index', 'label' => 'Fluxo de Caixa', 'icon' => 'fluxo'],
        ['route' => 'contas.index', 'label' => 'Contas Bancárias', 'icon' => 'contas'],
        ['route' => 'cartoes.index', 'label' => 'Cartões', 'icon' => 'cartoes'],
        ['route' => 'clientes.index', 'label' => 'Clientes', 'icon' => 'clientes'],
        ['route' => 'categorias.index', 'label' => 'Categorias', 'icon' => 'categorias'],
        ['route' => 'relatorios.receitas', 'label' => 'Relatórios', 'icon' => 'relatorios', 'match' => 'relatorios.*'],
        ['route' => 'usuarios.index', 'label' => 'Usuários e Permissões', 'icon' => 'usuarios'],
    ];
@endphp
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h1>FinanceFlow</h1>
        <p>Gestão Empresarial</p>
    </div>
    <nav class="sidebar-nav">
        @foreach($menu as $item)
            @php
                $active = request()->routeIs($item['match'] ?? $item['route']);
            @endphp
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'active' : '' }}">
                @include('components.icons.' . $item['icon'])
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="sidebar-footer">
        <div class="plan-box">
            <strong>Plano Professional</strong>
            <span>15 dias restantes</span>
            <a href="{{ route('usuarios.index') }}" class="btn btn-primary btn-sm" style="margin-top:0.5rem;width:100%;justify-content:center;">Gerenciar plano</a>
        </div>
    </div>
</aside>
