<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — FinanceFlow</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="login-page">

<div class="login-wrapper">

    {{-- Painel Esquerdo: Formulário --}}
    <div class="login-left">
        <div class="login-card">

            {{-- Logo --}}
            <div class="login-logo">
                <div class="login-logo__icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <span class="login-logo__text">
                    <span class="login-logo__light">Finance</span><strong class="login-logo__bold">Flow</strong>
                </span>
            </div>

            {{-- Cabeçalho --}}
            <div class="login-heading">
                <h1>Bem-vindo de volta</h1>
                <p>Por favor, insira seus dados.</p>
            </div>

            {{-- Erros --}}
            @if ($errors->any())
                <div class="login-error">{{ $errors->first() }}</div>
            @endif

            {{-- Formulário --}}
            <form action="{{ route('login.submit') }}" method="POST" novalidate>
                @csrf

                <div class="login-field">
                    <label for="email">E-mail</label>
                    <div class="login-input-wrap">
                        <svg class="login-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <input type="email" id="email" name="email"
                               placeholder="Digite seu e-mail"
                               value="{{ old('email') }}"
                               autocomplete="email">
                    </div>
                </div>

                <div class="login-field">
                    <label for="password">Senha</label>
                    <div class="login-input-wrap">
                        <svg class="login-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="password" name="senha"
                               placeholder="••••••••"
                               autocomplete="current-password">
                        <button type="button" class="login-toggle-password" onclick="togglePassword(this)" aria-label="Mostrar senha">
                            <svg class="eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-hide" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="login-remember">
                    <label class="login-checkbox">
                        <input type="checkbox" name="remember" value="1">
                        <span class="login-checkbox__box"></span>
                        <span>Lembrar acesso</span>
                    </label>
                    <button type="button" class="login-forgot">Esqueceu a senha?</button>
                </div>

                <button type="submit" class="login-btn">Entrar</button>
            </form>

            <div class="login-disclaimer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <p>Acesso corporativo restrito. Apenas e-mails autorizados e previamente cadastrados pelos administradores têm acesso.</p>
            </div>
        </div>

        <p class="login-footer">© FinanceFlow 2025</p>
    </div>

    {{-- Painel Direito: Gráfico 3D Isométrico Animado --}}
    <div class="login-right" aria-hidden="true">
    <iframe 
        src="/chart/chart.html" 
        frameborder="0" 
        scrolling="no"
        style="width:100%;height:100%;border:none;overflow:hidden;"
    ></iframe>
</div>
</div>


<script>
function togglePassword(btn) {
    const input = btn.closest('.login-input-wrap').querySelector('input');
    const show  = btn.querySelector('.eye-show');
    const hide  = btn.querySelector('.eye-hide');
    if (input.type === 'password') {
        input.type = 'text';
        show.style.display = 'none';
        hide.style.display = 'block';
    } else {
        input.type = 'password';
        show.style.display = 'block';
        hide.style.display = 'none';
    }
}

document.querySelectorAll('.login-input-wrap input').forEach(input => {
    input.addEventListener('focus', () => input.closest('.login-input-wrap').classList.add('is-focused'));
    input.addEventListener('blur',  () => input.closest('.login-input-wrap').classList.remove('is-focused'));
});
</script>
</body>
</html>