{{--
    RESPONSIVIDADE MOBILE (até 767px)
    - Todo o desktop continua com os estilos inline originais, sem nenhuma alteração.
    - No mobile, o bloco <style> abaixo sobrescreve com !important (só dentro do @media).
--}}
<style>
    @media (max-width: 767px) {

        /* Seção: cola a parte de baixo do painel na quebra da galeria */
        #produtos {
            padding: 56px 0 0 !important;
        }

        #produtos .lp-container {
            /* 16px base + 3% da largura da tela de cada lado = painel mais fino */
            padding: 0 calc(16px + 3vw) !important;
            max-width: 100% !important;
        }

        /* Painel: o PNG (900x568) esticado num painel alto e estreito deformaria a curva do topo.
           Então: PNG no topo com largura 100% e altura proporcional (a curva mantém o formato)
           + uma camada sólida cobrindo o resto do painel para baixo.
           O PNG original é semitransparente (~60%) e o glow dourado do drop-shadow vazava por trás
           do título. No mobile uso retangulo-produtos-mobile.png: mesmo formato, mas opaco e na cor
           do #sobre (#1A1A1A), igual à camada sólida de baixo. */
        #produtos .lp-panel {
            background-image: url('{{ asset('images/retangulo-produtos-mobile.png') }}'), linear-gradient(#1A1A1A, #1A1A1A) !important;
            background-size: 100% auto, 100% calc(100% - 100px) !important;
            background-position: top center, bottom center !important;
            background-repeat: no-repeat, no-repeat !important;
            background-color: transparent !important;
            padding: 40px 12px 36px !important;
            border-radius: 20px 20px 0 0 !important;
            box-shadow: none !important;
            /* drop-shadow segue a forma real do PNG (inclusive a curva do recorte):
               fio dourado fino + glow dourado suave pra destacar do fundo preto */
            filter: drop-shadow(0 0 0.6px rgba(201,168,76,0.9))
            drop-shadow(0 0 1px rgba(201,168,76,0.55))
            drop-shadow(0 0 22px rgba(201,168,76,0.16)) !important;
        }

        /* O contorno SVG usa viewBox fixo e deformaria no mobile */
        #produtos .lp-outline {
            display: none !important;
        }

        /* Setinha dourada dentro do recorte do topo */
        #produtos .lp-polygon {
            transform: translateX(-50%) translateY(-6%) !important;
        }
        #produtos .lp-polygon img {
            width: 22px !important;
        }

        /* Label + título */
        #produtos .lp-head {
            margin-bottom: 20px !important;
        }
        #produtos .lp-label-row {
            gap: 10px !important;
            margin-bottom: 6px !important;
        }
        #produtos .lp-label-line {
            width: 28px !important;
        }
        #produtos .lp-label-text {
            font-size: 10px !important;
            letter-spacing: 0.22em !important;
            white-space: nowrap !important;
        }
        #produtos .lp-title {
            font-size: clamp(26px, 8.2vw, 36px) !important;
            letter-spacing: 0.04em !important;
            white-space: nowrap !important;
            line-height: 1.1 !important;
        }

        /* Carrossel: setas nas pontas, um card só no meio */
        #produtos .lp-carousel {
            align-items: center !important;
            justify-content: space-between !important;
        }
        #produtos .lp-arrow {
            padding: 0 4px !important;
            margin-top: 0 !important;
        }
        #produtos .lp-arrow svg {
            width: 30px !important;
            height: 34px !important;
        }
        #produtos .lp-cards {
            gap: 0 !important;
        }

        /* No mobile aparece só um card: o lateral da esquerda (escuro), no lugar do destaque dourado */
        #produtos .lp-card {
            display: none !important;
        }
        #produtos .lp-card:first-child {
            display: block !important;
            width: 170px !important;
            margin-top: 0 !important;
        }
        /* O PNG do card é um contorno de 1px: reduzido, a diagonal de baixo ficava fina e borrada.
           No mobile o contorno vem de um SVG (vetor, traço sempre 1.5px) e o PNG só segura a altura. */
        #produtos .lp-card:first-child {
            background: url('{{ asset('images/card-lateral-mobile.svg') }}') center / 100% 100% no-repeat !important;
        }
        #produtos .lp-card:first-child .lp-card-img {
            height: 194px !important;
            visibility: hidden !important;
        }
        #produtos .lp-card-text {
            bottom: 22px !important;
            padding: 0 14px !important;
        }
    }
</style>

<section id="produtos" style="background: #0A0A0A; padding: 100px 0 80px; position: relative;">

    <div class="lp-container" style="max-width: 1100px; margin: 0 auto; padding: 0 24px; position: relative;">

        {{-- Painel usando a imagem do Figma como fundo. Sombra pra destacar do fundo #0A0A0A (que é quase
             preto igual ao painel): sombra escura funda embaixo (profundidade/flutuação) + glow dourado sutil.
             O CONTORNO dourado fino NÃO é mais box-shadow — a imagem tem uma curva côncava no topo (recorte
             onde a setinha encaixa; medi o PNG: vai de x=385 a x=515 num canvas de 900×568, fundo em x=450/y=51)
             e um box-shadow simples seguiria só a caixa retangular, cortando essa curva em linha reta. Uso um
             <svg> overlay com um <path> que traça o contorno real (cantos arredondados + a curva), calculado
             a partir dos pixels reais do PNG. --}}
        <div class="lp-panel" style="position: relative; background-image: url('{{ asset('images/retangulo-produtos.png') }}'); background-size: 100% 100%; background-repeat: no-repeat; padding: 80px 80px 60px; border-radius: 28px; box-shadow: 0 40px 80px rgba(0,0,0,0.65), 0 12px 28px rgba(0,0,0,0.5), 0 0 40px rgba(201,168,76,0.08);">

            {{-- Contorno dourado que segue o formato real do painel (inclusive a curva do recorte no topo) --}}
            <svg class="lp-outline" viewBox="0 0 900 568" preserveAspectRatio="none" fill="none" stroke="rgba(201,168,76,0.55)" stroke-width="2"
                 style="position: absolute; inset: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none;">
                <path d="M0,20 Q0,0 20,0 L385,0 L390,1 L395,3 L400,8 L405,13 L410,18 L415,23 L420,28 L425,34 L430,39 L435,44 L440,48 L445,50 L450,51 L455,50 L460,48 L465,44 L470,39 L475,34 L480,29 L485,24 L490,19 L495,14 L500,9 L505,4 L510,1 L515,0 L880,0 Q900,0 900,20 L900,548 Q900,568 880,568 L20,568 Q0,568 0,548 Z"/>
            </svg>

            {{-- Setinha dourada no topo --}}
            <div class="lp-polygon" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%) translateY(-48%); z-index: 2;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="" style="width: 52px; height: auto; display: block;">
            </div>

            {{-- Label + Título --}}
            <div class="lp-head" style="text-align: center; margin-bottom: 48px;">
                <div class="lp-label-row" style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 14px;">
                    <span class="lp-label-line" style="display: block; width: 80px; height: 1px; background: #C9A84C; opacity: 0.6;"></span>
                    <span class="lp-label-text" style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: #C9A84C; text-transform: uppercase;">Qualidade Premium</span>
                    <span class="lp-label-line" style="display: block; width: 80px; height: 1px; background: #C9A84C; opacity: 0.6;"></span>
                </div>
                <h2 class="lp-title" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 48px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">
                    Nossos Produtos
                </h2>
            </div>

            {{-- Carrossel --}}
            <div class="lp-carousel" style="display: flex; align-items: center;">

                {{-- Seta esquerda — sem botão/moldura ao redor. SVG inline (em vez do PNG) pra poder dar um
                     stroke que acompanha exatamente as pontas do triângulo, não uma caixa em volta dele.
                     100% maior (30x34 → 60x68), cantos com raio 5 via <path> com curvas nos vértices, e ponta
                     mais afastada da base (era x=7, agora x=3) pra ficar mais larga/menos fina. --}}
                <button class="lp-arrow" onclick="moverCarrossel(-1)"
                        style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px; margin-top: 14px;">
                    <svg width="60" height="68" viewBox="0 0 24 24" fill="#C9A84C" stroke="rgba(229,231,235,0.65)" stroke-width="1" stroke-linejoin="round" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.9; display: block;">
                        <path d="M 17,8 L 17,16 Q 17,21 12.79,18.30 L 7.21,14.70 Q 3,12 7.21,9.30 L 12.79,5.70 Q 17,3 17,8 Z"/>
                    </svg>
                </button>

                {{-- Cards --}}
                <div class="lp-cards" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 24px;">

                    @php
                        $produtos_mock = [
                            ['nome' => 'Pomada Matte',    'marca' => 'Barber Pro',  'preco' => '49,90', 'destaque' => false],
                            ['nome' => 'Óleo de Barba',   'marca' => "Lima's",      'preco' => '59,90', 'destaque' => true],
                            ['nome' => 'Shampoo Premium', 'marca' => 'Barber Gold', 'preco' => '39,90', 'destaque' => false],
                        ];
                    @endphp

                    @foreach($produtos_mock as $produto)
                        @php
                            $isDestaque = $produto['destaque'];
                            $w = $isDestaque ? '225px' : '200px';
                            $h = $isDestaque ? '272px' : '250px';
                            $mt = $isDestaque ? '-62px' : '90px';
                            $cardImg = $isDestaque ? 'images/card-destaque.png' : 'images/card-lateral.png';
                            $textColor = $isDestaque ? '#0A0A0A' : '#F5F0E8';
                            $subColor = $isDestaque ? 'rgba(10,10,10,0.6)' : 'rgba(245,240,232,0.45)';
                            $priceColor = $isDestaque ? '#0A0A0A' : '#C9A84C';
                        @endphp

                        <div class="lp-card {{ $isDestaque ? 'lp-card--destaque' : 'lp-card--lateral' }}"
                             style="flex-shrink: 0; width: {{ $w }}; margin-top: {{ $mt }}; position: relative;">
                            {{-- Imagem do card como fundo --}}
                            <img class="lp-card-img" src="{{ asset($cardImg) }}" alt=""
                                 style="width: 100%; height: {{ $h }}; object-fit: fill; display: block;">

                            {{-- Texto sobre o card --}}
                            <div class="lp-card-text" style="position: absolute; bottom: 28px; left: 0; right: 0; padding: 0 18px;">
                                <p style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 13px; color: {{ $textColor }}; margin: 0 0 3px;">{{ $produto['nome'] }}</p>
                                <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; color: {{ $subColor }}; margin: 0 0 6px;">{{ $produto['marca'] }}</p>
                                <p style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 20px; color: {{ $priceColor }}; margin: 0;">R$ {{ $produto['preco'] }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Seta direita — mesmos ajustes da esquerda, path espelhado --}}
                <button class="lp-arrow" onclick="moverCarrossel(1)"
                        style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px; margin-top: 14px;">
                    <svg width="60" height="68" viewBox="0 0 24 24" fill="#C9A84C" stroke="rgba(229,231,235,0.65)" stroke-width="1" stroke-linejoin="round" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.9; display: block;">
                        <path d="M 7,8 L 7,16 Q 7,21 11.21,18.30 L 16.79,14.70 Q 21,12 16.79,9.30 L 11.21,5.70 Q 7,3 7,8 Z"/>
                    </svg>
                </button>

            </div>

        </div>
    </div>

</section>

@push('scripts')
    <script>
        function moverCarrossel(dir) {
            console.log('Carrossel:', dir);
        }
    </script>
@endpush
