<aside class="w-64 bg-[var(--color-preto-suave)] border-r border-[var(--color-cinza)] flex flex-col"
       x-data="{ open: true }">

    <div class="p-6 border-b border-[var(--color-cinza)]">
        <h1 class="font-[var(--font-display)] text-[var(--color-ouro)] text-xl font-bold leading-tight">
            Lima's<br><span class="text-[var(--color-branco)] text-sm font-normal tracking-widest uppercase">Studio Barber</span>
        </h1>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        @php
            $links = [
                ['route' => 'admin.dashboard',         'icon' => '▪', 'label' => 'Dashboard'],
                ['route' => 'admin.agendamentos.index', 'icon' => '▪', 'label' => 'Agendamentos'],
                ['route' => 'admin.clientes.index',     'icon' => '▪', 'label' => 'Clientes'],
                ['route' => 'admin.aniversariantes',    'icon' => '▪', 'label' => 'Aniversariantes'],
                ['route' => 'admin.servicos.index',     'icon' => '▪', 'label' => 'Serviços'],
                ['route' => 'admin.produtos.index',     'icon' => '▪', 'label' => 'Produtos'],
                ['route' => 'admin.cupons.index',       'icon' => '▪', 'label' => 'Cupons'],
                ['route' => 'admin.avaliacoes.index',   'icon' => '▪', 'label' => 'Avaliações'],
                ['route' => 'admin.financeiro',         'icon' => '▪', 'label' => 'Financeiro'],
                ['route' => 'admin.configuracoes.index','icon' => '▪', 'label' => 'Configurações'],
            ];
        @endphp

        @foreach($links as $link)
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-2 rounded text-sm transition-colors
                      {{ request()->routeIs($link['route']) || request()->routeIs($link['route'].'*')
                         ? 'bg-[var(--color-ouro)] text-[var(--color-preto)] font-semibold'
                         : 'text-[var(--color-cinza-claro)] hover:text-[var(--color-branco)] hover:bg-[var(--color-cinza)]' }}">
                <span class="text-xs">{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-[var(--color-cinza)]">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full text-left text-sm text-[var(--color-cinza-claro)] hover:text-red-400 transition-colors px-3 py-2">
                Sair
            </button>
        </form>
    </div>
</aside>
