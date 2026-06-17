@php $user = \App\Data\MockData::usuarioLogado(); @endphp
<header class="navbar">
    <button type="button" class="hamburger" id="sidebar-toggle" aria-label="Abrir menu">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <form class="navbar-search" action="{{ route('fluxo-caixa.index') }}" method="GET">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="search" name="q" placeholder="Buscar movimentações, clientes, categorias..." value="{{ request('q') }}">
    </form>

    <div class="navbar-actions">
        <button type="button" class="theme-toggle" id="theme-toggle" aria-label="Alternar tema" title="Alternar tema">
            <svg class="icon-light" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v2m0 14v2m9-9h-2M5 12H3m9-6.5L9.5 9.5m5 5L14.5 14.5M9.5 9.5 12 12l2.5-2.5m-5 5L12 12l2.5 2.5"/><circle cx="12" cy="12" r="3.5"/></svg>
            <svg class="icon-dark" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
        </button>

        <a href="{{ route('fluxo-caixa.index') }}" class="btn btn-outline btn-sm" title="Notificações">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </a>

        <details class="user-menu-details">
            <summary class="user-btn">
                <div class="avatar">{{ $user['iniciais'] }}</div>
                <div style="text-align:left;">
                    <div style="font-size:0.875rem;font-weight:500;">{{ $user['nome'] }}</div>
                    <div style="font-size:0.75rem;color:#6b7280;">{{ \App\Data\MockData::perfis()[$user['perfil']] ?? $user['perfil'] }}</div>
                </div>
                <svg width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </summary>
            <div class="dropdown user-dropdown-panel">
                <a href="{{ route('usuarios.index') }}">Meu perfil</a>
                <a href="{{ route('usuarios.index') }}">Configurações</a>
                <hr style="border:none;border-top:1px solid #e5e7eb;margin:0.25rem 0;">
                <form action="{{ route('usuarios.trocar-perfil') }}" method="POST" class="perfil-form">
                    @csrf
                    <label for="perfil-select">Simular perfil</label>
                    <select id="perfil-select" name="perfil" class="form-control">
                        @foreach(\App\Data\MockData::perfis() as $key => $label)
                            <option value="{{ $key }}" @selected(session('perfil', 'administrador') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin-top:0.5rem;width:100%;">Aplicar perfil</button>
                </form>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="danger" style="background:none;border:none;cursor:pointer;padding:0;font-size:inherit;color:inherit;width:100%;text-align:left;">Sair</button>
                </form>
            </div>
        </details>
    </div>
</header>
