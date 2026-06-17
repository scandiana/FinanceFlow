<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — FinanceFlow</title>
    <script>
        (function () {
            const saved = localStorage.getItem('financeflow-theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-shell">
        @include('components.sidebar')
        <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

        <div class="main-wrapper">
            @include('components.navbar')

            <main class="main-content">
                @if(!\App\Data\MockData::podeEditar())
                    <div class="perfil-banner readonly">
                        Modo <strong>Visualização</strong>: ações de edição e exclusão estão ocultas. Troque o perfil no menu de usuários para simular outro acesso.
                    </div>
                @endif

                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div class="toast-container" id="toast-container" @if(session('toast')) data-toast="{{ json_encode(session('toast')) }}" @endif></div>

    @include('components.modal-confirm')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
