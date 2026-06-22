<div class="btn-group" style="margin-bottom:1.5rem;">
    <a href="{{ route('relatorios.receitas') }}" class="btn {{ request()->routeIs('relatorios.receitas') ? 'btn-primary' : 'btn-outline' }}">Receitas</a>
    <a href="{{ route('relatorios.despesas') }}" class="btn {{ request()->routeIs('relatorios.despesas') ? 'btn-primary' : 'btn-outline' }}">Despesas</a>
    <a href="{{ route('relatorios.fluxo') }}" class="btn {{ request()->routeIs('relatorios.fluxo') ? 'btn-primary' : 'btn-outline' }}">Fluxo consolidado</a>
</div>
