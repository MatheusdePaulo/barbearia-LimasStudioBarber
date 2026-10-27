@php
    use App\Http\Controllers\ClienteController;
    $statusLabel = [
        'confirmed' => ['Confirmado', 'text-green-400 bg-green-500/10 border-green-500/20'],
        'pending'   => ['Aguardando pagamento', 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20'],
        'finished'  => ['Concluído', 'text-zinc-300 bg-zinc-800 border-zinc-700'],
        'client_canceled' => ['Cancelado', 'text-zinc-400 bg-zinc-800 border-zinc-700'],
        'canceled'  => ['Faltou', 'text-red-400 bg-red-500/10 border-red-500/20'],
    ];
    $pagamentoLabel = fn ($a) => match ($a->payment_type) {
        'presencial' => 'Pagar no dia · R$ ' . number_format($a->valor_total, 2, ',', '.'),
        'full'       => 'Pago no site · R$ ' . number_format($a->deposit_amount, 2, ',', '.'),
        default      => $a->deposit_amount > 0 ? 'Pix · R$ ' . number_format($a->deposit_amount, 2, ',', '.') : null,
    };
@endphp
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha conta | Lima's Studio Barber</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[#0A0A0A] text-white min-h-screen antialiased">

<header class="border-b border-zinc-800/80 bg-[#0A0A0A]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        <a href="{{ url('/') }}"><img src="{{ asset('images/Logo-Limas.webp') }}" alt="Lima's Studio Barber" class="h-10 w-auto"></a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-zinc-500 hover:text-white text-[10px] font-black uppercase tracking-widest transition-colors">
                <i class="fas fa-arrow-right-from-bracket mr-1"></i> Sair
            </button>
        </form>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 sm:px-6 py-10 space-y-10" x-data="{ editando: {{ $errors->any() ? 'true' : 'false' }} }">

    {{-- SAUDAÇÃO --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
        <div>
            <p class="text-[#C9A84C] text-[10px] font-black uppercase tracking-[0.3em] mb-2">Minha conta</p>
            <h1 class="text-3xl font-black italic uppercase tracking-tighter">Olá, {{ \Illuminate\Support\Str::of($user->name)->before(' ') }}</h1>
        </div>
        <a href="{{ route('appointments.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-[#C9A84C] hover:bg-[#E2C97E] text-black font-black uppercase text-[11px] tracking-[0.2em] px-6 py-4 rounded-2xl transition-all">
            <i class="fas fa-calendar-plus"></i> Agendar horário
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-2xl text-xs font-bold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-2xl text-xs font-bold">{{ session('error') }}</div>
    @endif

    {{-- PRÓXIMOS HORÁRIOS --}}
    <section>
        <h2 class="text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic mb-4">Próximos horários</h2>

        @forelse($proximos as $a)
            <div class="bg-[#141414] border border-zinc-800 rounded-3xl p-6 mb-4 border-l-[6px] border-l-[#C9A84C]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-black uppercase text-lg text-white">{{ $a->service_label }}</p>
                        <p class="text-[#C9A84C] font-bold mt-1">
                            {{ \Carbon\Carbon::parse($a->date)->locale('pt_BR')->translatedFormat('l, d/m') }} às {{ \Illuminate\Support\Str::of($a->time)->substr(0, 5) }}
                        </p>
                        @if($pagamentoLabel($a))
                            <p class="text-zinc-500 text-xs mt-2">{{ $pagamentoLabel($a) }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 px-3 py-1.5 rounded-full text-[9px] font-black uppercase border {{ $statusLabel[$a->status][1] ?? 'text-zinc-400 border-zinc-700' }}">
                        {{ $statusLabel[$a->status][0] ?? $a->status }}
                    </span>
                </div>

                <div class="mt-5 pt-4 border-t border-zinc-800/80 flex flex-wrap items-center gap-4">
                    @if($a->status === 'pending' && $a->payment_url)
                        <a href="{{ URL::signedRoute('appointments.pagamento', $a) }}" class="text-[#C9A84C] text-[10px] font-black uppercase tracking-widest hover:underline">Pagar agora</a>
                    @endif
                    @if(ClienteController::podeCancelar($a))
                        <form method="POST" action="{{ route('cliente.cancelar', $a) }}"
                              onsubmit="return confirm('Cancelar esse horário?')">
                            @csrf
                            <button type="submit" class="text-red-400/80 hover:text-red-400 text-[10px] font-black uppercase tracking-widest">Cancelar horário</button>
                        </form>
                    @elseif($a->status === 'confirmed')
                        <a href="https://wa.me/5585985430142" target="_blank" rel="noopener"
                           class="text-zinc-500 hover:text-zinc-300 text-[10px] font-black uppercase tracking-widest">Precisa remarcar? Fale no WhatsApp</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-[#141414] border border-dashed border-zinc-800 rounded-3xl p-8 text-center">
                <p class="text-zinc-500 text-sm">Você não tem horários marcados.</p>
                <a href="{{ route('appointments.create') }}" class="inline-block mt-3 text-[#C9A84C] text-[11px] font-black uppercase tracking-widest hover:underline">Agendar agora</a>
            </div>
        @endforelse
    </section>

    {{-- HISTÓRICO --}}
    @if($historico->isNotEmpty())
        <section>
            <h2 class="text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic mb-4">Histórico</h2>
            <div class="bg-[#141414] border border-zinc-800 rounded-3xl divide-y divide-zinc-800/80">
                @foreach($historico as $a)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-zinc-200 truncate">{{ $a->service_label }}</p>
                            <p class="text-zinc-500 text-xs mt-0.5">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }} às {{ \Illuminate\Support\Str::of($a->time)->substr(0, 5) }}</p>
                        </div>
                        @php $st = in_array($a->status, ['confirmed', 'pending']) ? 'finished' : $a->status; @endphp
                        <span class="shrink-0 px-3 py-1 rounded-full text-[9px] font-black uppercase border {{ $statusLabel[$st][1] ?? 'text-zinc-400 border-zinc-700' }}">
                            {{ $statusLabel[$st][0] ?? $st }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- MEUS DADOS --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic">Meus dados</h2>
            <button type="button" x-show="!editando" @click="editando = true"
                    class="text-[#C9A84C] text-[10px] font-black uppercase tracking-widest hover:underline">Editar</button>
        </div>

        <div x-show="!editando" class="bg-[#141414] border border-zinc-800 rounded-3xl p-6 grid sm:grid-cols-2 gap-4 text-sm">
            <div><p class="text-zinc-600 text-[9px] font-black uppercase tracking-widest">Nome</p><p class="text-zinc-200 mt-1">{{ $user->name }}</p></div>
            <div><p class="text-zinc-600 text-[9px] font-black uppercase tracking-widest">WhatsApp</p><p class="text-zinc-200 mt-1">{{ $user->whatsapp ? preg_replace('/^(\d{2})(\d{4,5})(\d{4})$/', '($1) $2-$3', $user->whatsapp) : 'Não informado' }}</p></div>
            <div><p class="text-zinc-600 text-[9px] font-black uppercase tracking-widest">E-mail</p><p class="text-zinc-200 mt-1">{{ $user->email ?: 'Não informado' }}</p></div>
            <div><p class="text-zinc-600 text-[9px] font-black uppercase tracking-widest">Nascimento</p><p class="text-zinc-200 mt-1">{{ $user->birthday?->format('d/m/Y') ?: 'Não informado' }}</p></div>
        </div>

        <form x-show="editando" x-cloak method="POST" action="{{ route('cliente.atualizar') }}"
              class="bg-[#141414] border border-zinc-800 rounded-3xl p-6 space-y-4">
            @csrf
            @method('PUT')
            @foreach([
                ['name', 'Nome', 'text', $user->name],
                ['whatsapp', 'WhatsApp', 'tel', $user->whatsapp],
                ['email', 'E-mail (opcional)', 'email', $user->email],
                ['birthday', 'Nascimento (opcional)', 'date', $user->birthday?->format('Y-m-d')],
            ] as [$campo, $rotulo, $tipo, $valor])
                <div class="space-y-1">
                    <label for="{{ $campo }}" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">{{ $rotulo }}</label>
                    <input id="{{ $campo }}" name="{{ $campo }}" type="{{ $tipo }}" value="{{ old($campo, $valor) }}" style="color-scheme: dark;"
                           class="block w-full bg-zinc-900 border border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0 outline-none">
                    @error($campo)<p class="text-red-400 text-[10px] font-bold ml-2">{{ $message }}</p>@enderror
                </div>
            @endforeach
            <div class="flex gap-3 pt-2">
                <button type="button" @click="editando = false" class="flex-1 bg-zinc-900 text-zinc-500 py-3.5 rounded-2xl font-black uppercase text-[10px]">Cancelar</button>
                <button type="submit" class="flex-[2] bg-[#C9A84C] text-black py-3.5 rounded-2xl font-black uppercase text-[10px] tracking-widest">Salvar</button>
            </div>
        </form>
    </section>
</main>

</body>
</html>
