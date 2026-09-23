    <style>
        /* Mobile only: sobe a seção #sobre.
           IMPORTANTE: usar margin-top negativo, NÃO transform — transform só desloca o "desenho" (paint),
           não a caixa real no fluxo do documento. Isso fazia a onda divisória (que fica presa em #servicos,
           ancorada no boundary real) ficar desalinhada e "flutuando" sobre o #sobre deslocado. Com margin
           a caixa de #sobre realmente sobe e encaixa certinho com a onda. */
        @media (max-width: 768px) {
            #sobre {
                margin-top: -40px;
                transform: translateY(-40px);
            }

            /* Empilha em coluna única (o grid 2 colunas do desktop deixa a coluna de texto estreita
               demais pro parágrafo caber em poucas linhas com fonte legível) */
            #sobre > div {
                grid-template-columns: 1fr !important;
                gap: 32px !important;
                padding: 0 20px !important;
            }

            /* Remove o respiro de 110px usado no desktop pra "nascer" abaixo da foto do Enos —
               no mobile a foto é bem menor, então sobe todo o bloco de texto */
            .sobre-coluna-texto {
                margin-top: 0 !important;
            }

            /* Selo "Nossa História": mesmo tamanho do "Desde 1994" do hero, em uma linha só */
            .sobre-label {
                font-size: 11px !important;
                white-space: nowrap !important;
            }

            /* Parágrafo: fonte menor pra caber em no máximo 5 linhas */
            .sobre-paragrafo {
                font-size: 14px !important;
                line-height: 1.6 !important;
            }

            /* Estatísticas (∞ / 30+): sobe 10% */
            .sobre-stats {
                transform: translateY(-10%);
            }

            /* Fotos do barbeiro trabalhando (carrossel): desce 15% */
            .sobre-carrossel {
                transform: translateY(5%) !important;
            }
        }
    </style>

    {{-- SOBRE (fundo preto neutro, levemente mais claro que #servicos pra diferenciar as seções).
         Grid 2 colunas: ESQUERDA = selo "Nossa História" + heading + texto + estatísticas, com margin-top pra
         "nascer" logo abaixo da foto do Enos que vaza de #servicos (que aqui só poka ~59-80px dentro de #sobre).
         DIREITA = foto barbeiro-trabalhando.png. Fontes aumentadas (estavam pequenas demais). --}}
    <section id="sobre" style="background: #1A1A1A; padding: 40px 0 80px; position: relative;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 60px 0 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start;">

            {{-- COLUNA ESQUERDA — selo + texto abaixo da foto do Enos que vaza de #servicos --}}
            <div class="sobre-coluna-texto" style="margin-top: 110px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <span style="display: block; width: 32px; height: 1px; background: #C9A84C;"></span>
                    <span class="sobre-label" style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; letter-spacing: 0.3em; color: #C9A84C; text-transform: uppercase;">Nossa História</span>
                    <span style="display: block; width: 32px; height: 1px; background: #C9A84C;"></span>
                </div>
                <h2 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 40px; color: #F5F0E8; text-transform: uppercase; margin-bottom: 20px; line-height: 1.2;">
                    Uma Tradição de Família
                </h2>
                <p class="sobre-paragrafo" style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 17px; color: rgba(245,240,232,0.8); line-height: 1.8; margin-bottom: 36px;">
                    Desde 1994, a arte de aparar com precisão corre no sangue da família Lima. O primeiro corte foi dado com as próprias tesouras do pai, e desde então, cada cliente que senta na cadeira carrega um pouco dessa história.
                </p>
                <div class="sobre-stats" style="display: flex; gap: 48px;">
                    <div>
                        <span style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 46px; color: #C9A84C; display: block;">∞</span>
                        <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; color: rgba(245,240,232,0.5); text-transform: uppercase; letter-spacing: 0.15em;">Milhares de cortes</span>
                    </div>
                    <div>
                        <span style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 46px; color: #C9A84C; display: block;">30+</span>
                        <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; color: rgba(245,240,232,0.5); text-transform: uppercase; letter-spacing: 0.15em;">Anos de experiência</span>
                    </div>
                </div>
            </div>

            {{-- COLUNA DIREITA — carrossel de verdade (desliza horizontalmente, não crossfade) entre
                 barbeiro-trabalhando (436×573) e barbeiro-trabalhando2 (947×1600, proporção bem diferente).
                 O quadro fica travado na proporção da 1ª foto (aspect-ratio) e as duas usam object-fit:cover,
                 então a 2ª é recortada via CSS (sem editar o arquivo) pra sair do mesmo tamanho exato da 1ª.
                 Mesma vinheta radial aplicada nas duas. --}}
            <div class="sobre-carrossel" x-data="{ slide: 0 }" x-init="setInterval(() => { slide = (slide + 1) % 2 }, 5000)"
                 style="position: relative; border-radius: 12px; overflow: hidden; border: 1px solid rgba(201,168,76,0.2); transform: translateY(-8%); aspect-ratio: 436 / 573;">

                {{-- TRACK — as 2 fotos lado a lado (width:200%, cada uma 50% = 100% do quadro); desliza via
                     translateX pra dar o efeito de carrossel real --}}
                <div :style="'display: flex; width: 200%; height: 100%; transition: transform 0.7s cubic-bezier(0.4,0,0.2,1); transform: translateX(' + (slide * -50) + '%);'">
                    <img src="{{ asset('images/barbeiro-trabalhando.png') }}" alt="Barbeiro trabalhando"
                         style="width: 50%; height: 100%; object-fit: cover; display: block;">
                    <img src="{{ asset('images/barbeiro-trabalhando2.jpg') }}" alt="Barbeiro trabalhando 2"
                         style="width: 50%; height: 100%; object-fit: cover; display: block;">
                </div>

                {{-- VINHETA — por cima do track, estilo idêntico ao que já usávamos --}}
                <div style="position: absolute; inset: 0; pointer-events: none; background: radial-gradient(ellipse at center, transparent 32%, rgba(26,26,26,0.55) 68%, #1A1A1A 100%);"></div>

                {{-- DOTS — indicam qual foto está visível, clicáveis. "Pílula" de fundo escuro atrás da fileira
                     pra garantir contraste contra qualquer parte da foto (independe do que estiver atrás).
                     IMPORTANTE: o Alpine SUBSTITUI o atributo style inteiro quando :style é string (não mescla
                     com o style estático) — por isso cada branch do ternário repete width/height/padding/border/
                     border-radius/cursor/transition, e não só o background/transform que mudam. --}}
                <div style="position: absolute; bottom: 20px; left: 0; right: 0; z-index: 30; display: flex; align-items: center; justify-content: center;">
                    <div style="display: flex; align-items: center; gap: 14px; background: rgba(10,10,10,0.55); border: 1px solid rgba(255,255,255,0.15); border-radius: 999px; padding: 8px 14px;">
                        <button type="button" @click="slide = 0" aria-label="Foto 1"
                                style="width: 16px; height: 16px; padding: 0; border: none; border-radius: 50%; cursor: pointer; transition: background 0.3s ease, transform 0.3s ease;"
                                :style="slide === 0
                                    ? 'width: 16px; height: 16px; padding: 0; border: none; border-radius: 50%; cursor: pointer; transition: background 0.3s ease, transform 0.3s ease; background: #C9A84C; transform: scale(1.15);'
                                    : 'width: 16px; height: 16px; padding: 0; border: none; border-radius: 50%; cursor: pointer; transition: background 0.3s ease, transform 0.3s ease; background: #9CA3AF; transform: scale(1);'"></button>
                        <button type="button" @click="slide = 1" aria-label="Foto 2"
                                style="width: 16px; height: 16px; padding: 0; border: none; border-radius: 50%; cursor: pointer; transition: background 0.3s ease, transform 0.3s ease;"
                                :style="slide === 1
                                    ? 'width: 16px; height: 16px; padding: 0; border: none; border-radius: 50%; cursor: pointer; transition: background 0.3s ease, transform 0.3s ease; background: #C9A84C; transform: scale(1.15);'
                                    : 'width: 16px; height: 16px; padding: 0; border: none; border-radius: 50%; cursor: pointer; transition: background 0.3s ease, transform 0.3s ease; background: #9CA3AF; transform: scale(1);'"></button>
                    </div>
                </div>
            </div>

        </div>
    </section>

{{-- Fecha o wrapper aberto em servicos.blade.php --}}
</div>
