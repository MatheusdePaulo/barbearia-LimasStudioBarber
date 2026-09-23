<header class="h-16 bg-[var(--color-preto-suave)] border-b border-[var(--color-cinza)] flex items-center justify-between px-6">
    <h2 class="text-sm font-medium text-[var(--color-cinza-claro)]">
        @yield('page-title', 'Painel')
    </h2>
    <div class="flex items-center gap-3">
        <span class="text-xs text-[var(--color-cinza-medio)]">{{ now()->format('d/m/Y') }}</span>
        <div class="w-8 h-8 rounded-full bg-[var(--color-ouro)] flex items-center justify-center text-[var(--color-preto)] text-xs font-bold">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
    </div>
</header>
