<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avalie sua Experiência | Lima's Studio Barber</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#0A0A0A] text-white min-h-screen flex items-center justify-center p-6"
      x-data="avaliacao()" x-init="init()">

<div class="w-full max-w-md space-y-6">

    {{-- Logo --}}
    <div class="text-center mb-2">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo-limas02.png') }}" alt="Lima's Studio Barber" class="h-16 w-auto mx-auto">
        </a>
    </div>

    <div class="bg-[#141414] border border-zinc-800 rounded-[2.5rem] shadow-2xl overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#C9A84C]"></div>

        <div class="p-8 space-y-6">

            {{-- Já avaliou --}}
            @if($jaAvaliou)
                <div class="text-center space-y-4 py-4">
                    <div class="text-5xl">✅</div>
                    <h2 class="text-xl font-black italic text-white uppercase tracking-tight">Obrigado!</h2>
                    <p class="text-zinc-400 text-sm">Você já deixou sua avaliação. Agradecemos muito o seu feedback!</p>
                    <a href="{{ url('/') }}" class="inline-block mt-4 text-[#C9A84C] font-black uppercase text-[11px] tracking-widest hover:underline">
                        Voltar ao site →
                    </a>
                </div>

            {{-- Formulário de avaliação --}}
            @else
                {{-- Estado: formulário --}}
                <div x-show="!reviewSent">
                    <div class="text-center mb-6">
                        <p class="text-[9px] font-black uppercase text-[#C9A84C] tracking-widest mb-1">Lima's Studio Barber</p>
                        <h2 class="text-2xl font-black italic text-white uppercase tracking-tight leading-tight">
                            Como foi sua<br>experiência?
                        </h2>
                        @if($user)
                            <p class="text-zinc-500 text-sm mt-2">Olá, <span class="text-white font-bold">{{ $user->name }}</span>! Sua opinião é muito importante.</p>
                        @endif
                    </div>

                    {{-- Estrelas --}}
                    <div class="flex gap-3 justify-center mb-2">
                        <template x-for="star in [1,2,3,4,5]" :key="star">
                            <button type="button"
                                    @click="rating = star"
                                    @mouseover="hovered = star"
                                    @mouseleave="hovered = 0"
                                    class="text-5xl transition-all duration-150 hover:scale-110 active:scale-95"
                                    :class="star <= (hovered || rating) ? 'text-[#C9A84C]' : 'text-zinc-700'">
                                ★
                            </button>
                        </template>
                    </div>
                    <p class="text-center text-[10px] font-bold uppercase tracking-widest mb-6 transition-all"
                       :class="rating > 0 ? 'text-[#C9A84C]' : 'text-zinc-700'"
                       x-text="['', 'Muito ruim', 'Ruim', 'Regular', 'Bom', 'Excelente!'][rating] || 'Toque nas estrelas'">
                    </p>

                    {{-- Comentário --}}
                    <div class="space-y-2 mb-6">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Comentário <span class="text-zinc-700">(opcional)</span></label>
                        <textarea x-model="comment"
                                  placeholder="Conta como foi o atendimento, o corte, o ambiente..."
                                  rows="4"
                                  class="w-full bg-zinc-900/60 border border-zinc-800 rounded-2xl p-4 text-white text-sm resize-none outline-none focus:border-[#C9A84C] placeholder-zinc-700 transition-all"></textarea>
                    </div>

                    <p x-show="erro" x-cloak class="text-red-400 text-[11px] font-bold text-center mb-3">Selecione pelo menos uma estrela.</p>

                    <button type="button"
                            @click="enviar()"
                            :disabled="enviando"
                            class="w-full py-4 bg-[#C9A84C] hover:bg-[#E2C97E] text-black font-black uppercase rounded-2xl transition-all tracking-[0.2em] text-[11px] shadow-xl active:scale-[0.98] disabled:opacity-60">
                        <span x-text="enviando ? 'Enviando...' : 'Enviar Avaliação'"></span>
                    </button>
                </div>

                {{-- Estado: pós-avaliação --}}
                <div x-show="reviewSent" x-cloak class="space-y-5">
                    <div class="text-center">
                        <div class="text-5xl mb-3">⭐</div>
                        <h3 class="text-xl font-black italic text-white uppercase tracking-tight">Obrigado pela avaliação!</h3>
                        <p class="text-zinc-400 text-sm mt-2">Sua opinião nos ajuda a melhorar cada vez mais.</p>
                    </div>

                    {{-- Convite Google --}}
                    @if($reviewCouponActive)
                    <div x-show="!couponCode" class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-5 text-center space-y-3">
                        <p class="text-zinc-300 text-sm leading-relaxed">
                            Avalie também no <strong class="text-white">Google</strong> e ganhe
                            <strong class="text-[#C9A84C]">{{ $reviewCouponPercent }}% de desconto</strong> no seu próximo corte! 👇
                        </p>
                        <a href="https://www.google.com/search?q=lima%27s+studio+barber+cascavel+ce"
                           target="_blank"
                           @click="gerarCupom()"
                           class="inline-flex items-center gap-2 bg-white text-[#1A1C1E] font-black uppercase text-[11px] tracking-widest px-6 py-3 rounded-xl hover:bg-zinc-100 transition-all shadow-lg">
                            <img src="https://www.google.com/favicon.ico" class="w-4 h-4"> Avaliar no Google
                        </a>
                        <p class="text-zinc-600 text-[10px] italic">O cupom é gerado ao clicar no botão acima</p>
                    </div>

                    {{-- Cupom gerado --}}
                    <div x-show="couponCode" x-cloak
                         class="bg-[#C9A84C]/10 border border-[#C9A84C]/40 rounded-2xl p-6 text-center space-y-3">
                        <p class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">Seu cupom de desconto</p>
                        <div class="flex items-center justify-center gap-3">
                            <span x-text="couponCode" class="text-[#C9A84C] font-black text-3xl tracking-widest font-mono"></span>
                        </div>
                        <button type="button"
                                @click="copiar()"
                                class="px-5 py-2 bg-zinc-800 hover:bg-zinc-700 text-white font-black text-[10px] uppercase rounded-xl tracking-widest transition-all">
                            <span x-text="copiado ? '✓ Copiado!' : 'Copiar Código'"></span>
                        </button>
                        <p class="text-zinc-500 text-[10px] italic">{{ $reviewCouponPercent }}% de desconto no próximo agendamento</p>
                    </div>
                    @endif

                    <a href="{{ url('/') }}"
                       class="block w-full py-4 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase rounded-2xl text-center transition-all tracking-widest text-[11px]">
                        Voltar ao Site
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
function avaliacao() {
    return {
        rating: 0,
        hovered: 0,
        comment: '',
        enviando: false,
        erro: false,
        reviewSent: false,
        couponCode: null,
        copiado: false,

        userId: {{ $user?->id ?? 'null' }},

        init() {},

        enviar() {
            if (this.rating === 0) { this.erro = true; return; }
            this.erro     = false;
            this.enviando = true;

            fetch('{{ route('reviews.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    user_id: this.userId,
                    rating:  this.rating,
                    comment: this.comment,
                }),
            })
            .then(r => r.json())
            .then(() => { this.reviewSent = true; })
            .catch(() => { this.reviewSent = true; })
            .finally(() => { this.enviando = false; });
        },

        gerarCupom() {
            fetch('{{ route('reviews.generateCoupon') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ user_id: this.userId }),
            })
            .then(r => r.json())
            .then(data => { if (data.code) this.couponCode = data.code; })
            .catch(() => {});
        },

        copiar() {
            navigator.clipboard.writeText(this.couponCode);
            this.copiado = true;
            setTimeout(() => { this.copiado = false; }, 2000);
        },
    };
}
</script>

</body>
</html>
