<style>
    /* ══════════════════════════════════════════
       SERVIÇOS — RESPONSIVO MOBILE ONLY
       Não altera nada no desktop (> 768px)
    ══════════════════════════════════════════ */

    @media (max-width: 768px) {

        /* Foto do Enos: volta a aparecer, reduzida pra caber no layout mobile
           (e agora mais 40% menor em cima disso: 298px -> 179px) */
        .enos-photo {
            left: 0px !important;
            bottom: -38px !important;
            height: 197px !important;
            transform: translateY(-25%) translateY(-40px);
            /* Fade inferior um pouco mais longo que o desktop (começa em 82% em vez de 85%) */
            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 18%, #000 82%, transparent 100%) !important;
            mask-image: linear-gradient(to bottom, transparent 0%, #000 18%, #000 82%, transparent 100%) !important;
        }
        .enos-photo__img {
            height: 232px !important;
        }
        .enos-glow {
            left: -39px !important;
            bottom: -90px !important;
            width: 257px !important;
            height: 180px !important;
            transform: translateY(-25%) translateY(-40px);
        }

        /* Layout principal: muda de flex row para column */
        #servicos > div:first-of-type {
            flex-direction: column !important;
            padding: 0 20px !important;
            gap: 24px !important;
            align-items: flex-start !important;
        }

        /* Wrapper do título: remove o padding-left grande usado no desktop */
        #servicos > div:first-of-type > div:first-child {
            padding-left: 0 !important;
        }

        /* Título em uma linha só, alinhado à esquerda */
        #servicos h2 {
            font-size: 28px !important;
            white-space: nowrap !important;
            text-align: left !important;
            padding-left: 0 !important;
        }

        /* Coluna direita: ocupa a largura toda, cards continuam centralizados.
           Gap um pouco maior (32px -> 48px) pra dar espaço entre os cards e a foto do Enos + frase abaixo */
        #servicos > div:first-of-type > div:last-child {
            margin-left: 0 !important;
            width: 100% !important;
            align-items: center !important;
            gap: 48px !important;
        }

        /* Cards: +15% no tamanho (90px -> 103.5px) */
        #servicos .servico-card-wrap a {
            width: 103.5px !important;
            height: 103.5px !important;
        }

        /* Gap entre cards menor + desce os cards 10% */
        #servicos > div:first-of-type > div:last-child > div:first-child {
            gap: 16px !important;
            justify-content: center !important;
            transform: translateY(10%);
        }

        /* Badge da estrela: reposiciona */
        .servico-card__badge {
            width: 22px !important;
            height: 22px !important;
            top: -10px !important;
        }
        .servico-card__badge svg {
            width: 11px !important;
            height: 11px !important;
        }

        /* Label dos cards menor */
        .servico-card__label {
            font-size: 9px !important;
            bottom: 10px !important;
            letter-spacing: 0.1em !important;
        }

        /* Frase: esconde a versão desktop, mostra a versão mobile (3 linhas, sem "e"/vírgula) */
        .frase-servicos-desktop { display: none !important; }
        .frase-servicos-mobile  { display: block !important; }

        /* Onda divisória: usa a imagem real (wave-divider.png), escondendo a versão SVG do desktop. */
        .onda-divisoria-desktop { display: none !important; }
        .onda-divisoria-mobile  { display: block !important; }
        /* Sobe junto com #sobre e a foto do Enos (mesmo valor em px nos três, pra não desalinhar) */
        .onda-divisoria-wrap {
            transform: translateY(-40px);
        }

        /* Tesoura dourada + traços: -60% no tamanho geral, e agora mais -3% em cima disso + sobe 5%
           pra ficar mais perto da palavra "TRADIÇÃO" */
        .tesoura-icon {
            width: 13.97px !important;
            height: 13.97px !important;
        }
        .tesoura-linha {
            width: 38% !important;
            gap: 3.88px !important;
            transform: translateY(-80%);
        }

        /* Bloco da frase: continua no lado direito da tela (só o alinhamento do texto mudou pra center) */
        #servicos > div:first-of-type > div:last-child > div:last-child {
            align-items: flex-end !important;
        }

        /* Reduz padding da seção — título sobe pro topo */
        #servicos {
            padding: 0 0 120px !important;
        }

        /* Espaço entre o hero e a seção de serviços: bem menor no mobile */
        .servicos-wrapper {
            margin-top: 24px !important;
        }

        /* Wrapper geral: remove margin-top grande */
        #servicos ~ * ,
        #servicos {
            overflow: visible !important;
        }
    }

    /* Tablet (768px - 1024px) */
    @media (min-width: 769px) and (max-width: 1024px) {

        .enos-photo { left: 20px !important; height: 380px !important; }
        .enos-glow  { left: -20px !important; }

        #servicos h2 { font-size: 38px !important; }

        #servicos .servico-card-wrap a {
            width: 180px !important;
            height: 180px !important;
        }

        #servicos > div:first-of-type {
            padding: 0 24px 0 16px !important;
            gap: 32px !important;
        }

        #servicos p[style*="Cormorant"] {
            font-size: 28px !important;
        }
    }
</style>

{{-- ═══════════ SERVIÇOS + SOBRE ═══════════ --}}
{{-- Wrapper aberto aqui e fechado no final de sobre.blade.php.
     margin-top empurra #servicos (e #sobre junto, que vem em seguida) pra baixo, aumentando o espaço visível
     entre o fim de #hero e o início de #servicos. --}}
<div class="servicos-wrapper" style="position: relative; overflow: visible; margin-top: 80px;">

    {{-- SERVIÇOS (fundo branco) --}}
    <section id="servicos" style="background: #0A0A0A; position: relative; overflow: visible; padding: 40px 0 153px;">

        {{-- ─── LAYOUT: flex row, título esquerda | cards direita ─── --}}
        <div style="width: 100%; padding: 0 40px 0 16px; display: flex; align-items: flex-start; gap: 60px;">

            {{-- TÍTULO — para mover: ajuste padding-left abaixo --}}
            <div style="flex-shrink: 0; padding-left: 140px;">
                {{-- TAMANHO DO TÍTULO: font-size abaixo --}}
                <h2 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 52px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; margin: 0;">
                    Nossos Serviços
                </h2>
            </div>

            {{-- COLUNA DIREITA: cards + frase --}}
            <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-start; gap: 32px; margin-left: -200px;">

                {{-- CARDS — para ajustar espaço entre eles: gap abaixo --}}
                <div style="display: flex; gap: 52px; align-items: center; justify-content: center; width: 100%;">
                    @php
                        $servicos_destaque = [
                            ['nome' => 'Cabelo', 'img' => 'images/circulo-cabelo.png'],
                            ['nome' => 'Combo',  'img' => 'images/circulo-combo.png'],
                            ['nome' => 'Barba',  'img' => 'images/circulo-barba.png'],
                        ];
                    @endphp
                    @foreach($servicos_destaque as $s)
                        @php $featured = $s['nome'] === 'Combo'; @endphp
                        {{-- Wrapper SEM overflow:hidden — necessário pra estrelinha do Combo poder "flutuar"
                             pra fora da borda do círculo sem ser cortada pelo overflow:hidden do card em si. --}}
                        <div class="servico-card-wrap" style="position: relative; flex-shrink: 0;">
                            @if($featured)
                                {{-- ESTRELINHA DOURADA — badge de destaque no topo do card Combo --}}
                                <div class="servico-card__badge" style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); z-index: 10; width: 32px; height: 32px; border-radius: 50%; background: #0A0A0A; border: 1.5px solid #C9A84C; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.45);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#C9A84C" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2l2.9 6.9L22 9.6l-5.3 4.7L18.2 22 12 18l-6.2 4 1.5-7.7L2 9.6l7.1-.7L12 2z"/>
                                    </svg>
                                </div>
                            @endif
                            {{-- TAMANHO DOS CÍRCULOS: width e height abaixo --}}
                            <a href="#" class="servico-card {{ $featured ? 'servico-card--featured' : '' }}" style="position: relative; width: 238px; height: 238px; border-radius: 50%; overflow: hidden; display: block; text-decoration: none;">
                                <img src="{{ asset($s['img']) }}" alt="{{ $s['nome'] }}" class="servico-card__img"
                                     style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                <div class="servico-card__overlay" style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 55%);"></div>
                                <span class="servico-card__label" style="position: absolute; bottom: 24px; left: 0; right: 0; text-align: center; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 13px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.14em;">
                                {{ $s['nome'] }}
                            </span>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- FRASE + TESOURA — centralizada na coluna direita --}}
                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    {{-- TAMANHO DA FRASE: font-size abaixo --}}
                    <p class="frase-servicos-desktop" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 40px; color: #C9A84C; line-height: 1.15; text-transform: uppercase; margin-bottom: 10px; white-space: nowrap; text-align: center;">
                        Estilo, Tradição e Precisão
                    </p>
                    {{-- Versão mobile: uma palavra por linha, sem "e"/vírgula --}}
                    <p class="frase-servicos-mobile" style="display: none; font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 26px; color: #C9A84C; line-height: 1.2; text-transform: uppercase; margin: 0 0 10px; text-align: center;">
                        ESTILO<br>PRECISÃO<br>TRADIÇÃO
                    </p>
                    {{-- LINHA ── ✂ ── do tamanho da frase --}}
                    <div class="tesoura-linha" style="display: flex; align-items: center; gap: 10px; width: 480px;">
                        <span style="flex: 1; height: 1px; background: #C9A84C; display: block;"></span>
                        <svg class="tesoura-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="6" cy="6" r="3" stroke="#C9A84C" stroke-width="1.5"/>
                            <circle cx="6" cy="18" r="3" stroke="#C9A84C" stroke-width="1.5"/>
                            <path d="M8.5 8.5L20 4" stroke="#C9A84C" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M8.5 15.5L20 20" stroke="#C9A84C" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M14 12H20" stroke="#C9A84C" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span style="flex: 1; height: 1px; background: #C9A84C; display: block;"></span>
                    </div>
                </div>

            </div>
        </div>

        {{-- GLOW DE PROFUNDIDADE: pool de luz dourada radial atrás da foto. O gradiente em si continua centralizado
             na divisória #servicos/#sobre (mesma forma/curva de antes), mas a mask-image corta exatamente na
             metade (a linha da divisória), deixando visível só a parte de cima — de #servicos pra cima da foto.
             Dourado em vez de preto porque uma sombra escura sobre fundo quase-preto não teria contraste nenhum.
             Ajuste left/width/bottom abaixo pra recentralizar caso não bata exatamente com a silhueta da foto. --}}
        <div class="enos-glow" style="position: absolute; bottom: -210px; left: -50px; z-index: 15; width: 600px; height: 420px; pointer-events: none; background: radial-gradient(ellipse at center, rgba(201,168,76,0.28) 0%, rgba(201,168,76,0.1) 45%, transparent 72%); -webkit-mask-image: linear-gradient(to bottom, #000 0%, #000 50%, transparent 50%, transparent 100%); mask-image: linear-gradient(to bottom, #000 0%, #000 50%, transparent 50%, transparent 100%);"></div>

        {{-- FOTO BARBEIRO — para mover: bottom e left abaixo. Atravessa o fundo de #servicos (#0A0A0A) e o de
             #sobre (#1A1A1A); o filter mantém contraste/legibilidade uniformes nas duas metades da foto.
             Wrapper com overflow:hidden e altura de 459px (85% de 540px) corta os 15% finais (base) da imagem.
             Mask no topo (transparent → opaco nos primeiros 18% da altura) faz a foto "emergir" suavemente da
             seção de cima. Mask na base (opaco → transparent nos últimos 15%) suaviza a linha onde o
             overflow:hidden corta a imagem, em vez de um corte seco.
             Hover em CSS puro (:hover): zoom + brilho na foto, anel dourado no quadro, glow atrás intensifica.
             O transform (scale) fica só na <img>, NUNCA no mesmo elemento que tem o mask-image — é a lição do
             bug de composição gráfica que já pegamos (mask-image + transform 3D no mesmo elemento). Aqui nem é
             3D, mas mantive a separação por segurança. pointer-events volta a "auto" pro :hover funcionar. --}}
        <div class="enos-photo" style="position: absolute; bottom: -59px; left: 50px; z-index: 20; pointer-events: auto; height: 459px; overflow: hidden; -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 18%, #000 85%, transparent 100%); mask-image: linear-gradient(to bottom, transparent 0%, #000 18%, #000 85%, transparent 100%);">
            <img src="{{ asset('images/enos-barbeiro.png') }}" alt="Enos Lima" class="enos-photo__img"
                 style="height: 540px; width: auto; object-fit: contain; display: block;">
        </div>

        {{-- ONDA SVG (Retângulo 70): preenchida com a cor de #sobre, não de #servicos — assim a curva fica
             visível subindo com o tom da próxima seção, criando a quebra de página.
             Versão mobile separada: usa o asset real (wave-divider.png), que existia no projeto mas não estava
             sendo referenciado em lugar nenhum — o SVG desenhado à mão tinha um formato diferente do original. --}}
        <div class="onda-divisoria-wrap" style="position: absolute; bottom: -2px; left: 0; right: 0; z-index: 10; line-height: 0;">
            <svg class="onda-divisoria-desktop" viewBox="0 0 1440 200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                 style="width: 100%; height: 200px; display: block;">
                <path d="M0,190 C300,200 500,180 700,170 C900,160 1100,80 1300,30 C1380,10 1420,4 1440,2 L1440,200 L0,200 Z"
                      fill="#1A1A1A"/>
            </svg>
            {{-- Usa mask-image (não <img> direto) pra recolorir o preto puro do PNG pra #1A1A1A,
                 a cor exata de #sobre — mesma técnica já usada nos cards do blog. --}}
            <div class="onda-divisoria-mobile" style="width: 100%; height: 90px; display: none; background: #1A1A1A;
                    -webkit-mask-image: url('{{ asset('images/wave-divider.png') }}');
                    -webkit-mask-size: 100% 100%;
                    -webkit-mask-repeat: no-repeat;
                    mask-image: url('{{ asset('images/wave-divider.png') }}');
                    mask-size: 100% 100%;
                    mask-repeat: no-repeat;"></div>
        </div>

    </section>
