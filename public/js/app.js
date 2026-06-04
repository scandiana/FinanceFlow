document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggleBtn = document.getElementById('sidebar-toggle');

    const openSidebar = () => {
        sidebar?.classList.add('open');
        backdrop?.classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    const closeSidebar = () => {
        sidebar?.classList.remove('open');
        backdrop?.classList.remove('open');
        document.body.style.overflow = '';
    };

    toggleBtn?.addEventListener('click', () => {
        if (sidebar?.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    backdrop?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.sidebar-nav a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });

    document.querySelectorAll('[data-modal-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-open');
            document.getElementById(id)?.classList.add('open');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-close');
            document.getElementById(id)?.classList.remove('open');
        });
    });

    document.querySelectorAll('.modal-overlay').forEach((overlay) => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('open');
            }
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((el) => {
        el.addEventListener('click', (e) => {
            const msg = el.getAttribute('data-confirm') || 'Confirma esta ação?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    initCharts();
    showSessionToast();
});

function showSessionToast() {
    const container = document.getElementById('toast-container');
    const data = container?.dataset.toast;
    if (!data || !container) return;
    try {
        const toast = JSON.parse(data);
        appendToast(toast.type || 'success', toast.message);
    } catch (_) { /* ignore */ }
}

function appendToast(type, message) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const el = document.createElement('div');
    el.className = `toast toast-${type}`;
    el.textContent = message;
    container.appendChild(el);
    setTimeout(() => el.remove(), 4000);
}

function initCharts() {
    if (typeof Chart === 'undefined') return;

    const barEl = document.getElementById('chart-receitas-despesas');
    if (barEl) {
        const data = JSON.parse(barEl.dataset.chart || '[]');
        new Chart(barEl, {
            type: 'bar',
            data: {
                labels: data.map((d) => d.mes),
                datasets: [
                    { label: 'Receitas', data: data.map((d) => d.receitas), backgroundColor: '#10b981', borderRadius: 4 },
                    { label: 'Despesas', data: data.map((d) => d.despesas), backgroundColor: '#ef4444', borderRadius: 4 },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.dataset.label}: R$ ${ctx.raw.toLocaleString('pt-BR')}`,
                        },
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            callback: (v) => 'R$ ' + Number(v).toLocaleString('pt-BR'),
                        },
                    },
                },
            },
        });
    }

    const lineEl = document.getElementById('chart-fluxo-caixa');
    if (lineEl) {
        const data = JSON.parse(lineEl.dataset.chart || '[]');
        new Chart(lineEl, {
            type: 'line',
            data: {
                labels: data.map((d) => d.mes),
                datasets: [{
                    label: 'Resultado acumulado',
                    data: data.map((d) => d.saldo),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` R$ ${ctx.raw.toLocaleString('pt-BR')}`,
                        },
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            callback: (v) => 'R$ ' + Number(v).toLocaleString('pt-BR'),
                        },
                    },
                },
            },
        });
    }

    const reportBar = document.getElementById('chart-relatorio-fluxo');
    if (reportBar) {
        const data = JSON.parse(reportBar.dataset.chart || '[]');
        new Chart(reportBar, {
            type: 'bar',
            data: {
                labels: data.map((d) => d.mes),
                datasets: [
                    { label: 'Receitas', data: data.map((d) => d.receitas), backgroundColor: '#10b981' },
                    { label: 'Despesas', data: data.map((d) => d.despesas), backgroundColor: '#ef4444' },
                ],
            },
            options: { responsive: true, maintainAspectRatio: false },
        });
    }
}
