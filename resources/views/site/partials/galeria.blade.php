{{-- ═══════════ QUEBRA DE LAYOUT — GALERIA ═══════════ --}}
<div style="position: relative; width: 100vw; margin-left: calc(-1 * (100vw - 100%) / 2); height: 200px; overflow: hidden;">
    <img src="{{ asset('images/Ambiente.png') }}" alt=""
         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.85) 100%);"></div>
    <div class="gq-content" style="position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 24px;">
        <div class="gq-label-row" style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
            <span style="display: block; width: 40px; height: 1px; background: #F5F0E8; opacity: 0.5;"></span>
            <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: rgba(245,240,232,0.8); text-transform: uppercase;">Transformações</span>
            <span style="display: block; width: 40px; height: 1px; background: #F5F0E8; opacity: 0.5;"></span>
        </div>
        <h2 class="gq-title" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 28px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.03em; line-height: 1.3; margin: 0; max-width: 600px;">
            Veja os resultados reais dos nossos clientes
        </h2>
    </div>
</div>

{{-- ═══════════ GALERIA — CARDS ANTES/DEPOIS ═══════════ --}}
<section id="galeria" style="background: #0A0A0A; padding: 60px 0 80px;">

    @push('styles')
        <style>
            .card-frame {
                position: relative;
                overflow: hidden;
                /* Formato calcado no shape real de retangulo-galeria.png (425x340):
                   cantos superiores arredondados e um recorte diagonal grande que
                   começa a ~70% da altura na borda direita e desce até perto do
                   canto inferior esquerdo. Pontos em %, não px, porque a borda é
                   esticada via object-fit:fill até preencher o card (100% x 100%);
                   usando % o recorte da foto acompanha esse esticamento e fica
                   sempre alinhado com o contorno dourado, em qualquer tamanho de card. */
                clip-path: polygon(
                    3% 0%, 97% 0%,
                    100% 3.5%,
                    100% 70%,
                    4% 98%,
                    0% 96%,
                    0% 3.5%
                );
            }
            .slider-container {
                position: absolute;
                inset: 0;
                overflow: hidden;
                user-select: none;
                cursor: ew-resize;
            }
            .slider-antes,
            .slider-depois {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                /* impede o drag nativo da <img> do navegador (o "fantasma" saindo
                   do lugar), que rouba o mousemove do slider customizado */
                -webkit-user-drag: none;
                user-drag: none;
                -webkit-touch-callout: none;
                user-select: none;
                pointer-events: none;
            }
            .slider-antes {
                object-position: center 52%;
                transform: scale(1.3) translateY(10%);
            }
            .slider-depois {
                object-position: center top;
                /* 1.4 é o zoom mínimo que ainda cobre o translateX(20%) sem sobrar
                   espaço vazio na lateral esquerda da foto */
                transform: scale(1.4) translateX(20%);
                /* estado inicial: "depois" totalmente escondida, mostrando só a
                   foto de "antes"; arrastar para a direita é que revela a "depois" */
                clip-path: inset(0 100% 0 0);
            }
            .slider-handle {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 7%;
                width: 3px;
                background: linear-gradient(to bottom,
                    rgba(201,168,76,0) 0%,
                    #C9A84C 12%,
                    #E8CE84 50%,
                    #C9A84C 88%,
                    rgba(201,168,76,0) 100%);
                box-shadow: 0 0 10px rgba(201,168,76,0.55);
                transform: translateX(-50%);
                z-index: 10;
                pointer-events: none;
            }
            .slider-handle-icon {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: linear-gradient(160deg, #E8CE84, #C9A84C);
                color: #0A0A0A;
                font-size: 14px;
                font-weight: bold;
                width: 34px;
                height: 34px;
                border-radius: 50%;
                border: 2px solid rgba(245,240,232,0.55);
                box-shadow: 0 3px 12px rgba(0,0,0,0.45);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.15s ease;
            }
            /* Dica quase transparente avisando que dá pra arrastar; some depois que a pessoa arrasta */
            /* "Arraste" no meio do card, pulsando. O pulso fica no <span> de dentro pra não brigar
               com o fade de sumir (.slider-dica--oculta), que é no wrapper. */
            .slider-dica {
                position: absolute;
                left: 0;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                z-index: 9;
                text-align: center;
                pointer-events: none;
                transition: opacity 0.4s ease;
            }
            .slider-dica span {
                display: inline-block;
                font-family: 'Montserrat', sans-serif;
                font-weight: 600;
                font-size: 18px;
                letter-spacing: 0.3em;
                text-transform: uppercase;
                color: #F5F0E8;
                text-shadow: 0 2px 10px rgba(0,0,0,0.7);
                animation: slider-dica-pulso 1.8s ease-in-out infinite;
            }
            @keyframes slider-dica-pulso {
                0%, 100% { opacity: 0.35; transform: scale(1); }
                50%      { opacity: 0.75; transform: scale(1.08); }
            }
            .slider-dica--oculta {
                opacity: 0;
            }
            .slider-container:active .slider-handle-icon {
                transform: translate(-50%, -50%) scale(1.1);
            }
            /* RESPONSIVIDADE MOBILE (até 767px): textos alinhados à esquerda.
               Desktop continua com os estilos inline originais. */
            @media (max-width: 767px) {
                .gq-content {
                    align-items: flex-start !important;
                    text-align: left !important;
                }
                .gq-label-row {
                    justify-content: flex-start !important;
                }
                .gq-title,
                .galeria-title {
                    text-align: left !important;
                }

                /* Cards: só o central (slider antes/depois, mesmos efeitos do desktop),
                   setinhas lado a lado abaixo dele */
                .galeria-carousel {
                    flex-wrap: wrap !important;
                    justify-content: center !important;
                    column-gap: 28px !important;
                }
                .galeria-cards {
                    flex: 0 0 100% !important;
                    gap: 0 !important;
                }
                /* Setas dentro de um círculo transparente com borda dourada de 1px; seta 30px +15% = 34.5px */
                .galeria-arrow {
                    order: 1;
                    margin-top: 20px !important;
                    padding: 0 !important;
                    width: 60px !important;
                    height: 60px !important;
                    border: 1px solid #C9A84C !important;
                    border-radius: 50% !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                }
                /* O PNG da seta (44x31, achatado e com pontas arredondadas) ficava curto girado.
                   No mobile a seta é um triângulo equilátero em SVG, pontudo e quase encostando no círculo. */
                .galeria-arrow {
                    background: url('{{ asset('images/seta-circulo.svg') }}') center / 100% 100% no-repeat !important;
                }
                .galeria-carousel > .galeria-arrow:first-child {
                    transform: scaleX(-1);
                }
                .galeria-arrow img {
                    display: none !important;
                }
                .card-frame--lateral {
                    display: none !important;
                }
                /* Card do antes/depois com o mesmo retângulo do card central do blog (mesmo tamanho 270x370,
                   mesma moldura dourada). A foto é recortada pela máscara da moldura do blog, igual lá,
                   em vez do clip-path diagonal do desktop. */
                .card-frame--destaque {
                    width: 270px !important;
                    height: 370px !important;
                    margin-top: 0 !important;
                    clip-path: none !important;
                    overflow: visible !important;
                }
                .card-frame--destaque .card-frame__borda {
                    display: none !important;
                }
                .card-frame--destaque .slider-container {
                    -webkit-mask-image: url('{{ asset('images/rectangle-centro-blog-mask.png') }}');
                    -webkit-mask-size: 100% 100%;
                    -webkit-mask-repeat: no-repeat;
                    mask-image: url('{{ asset('images/rectangle-centro-blog-mask.png') }}');
                    mask-size: 100% 100%;
                    mask-repeat: no-repeat;
                }
                .card-frame--destaque::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: url('{{ asset('images/Rectangle-centro-blog.png') }}') center / 100% 100% no-repeat;
                    pointer-events: none;
                    z-index: 15;
                }
                /* A moldura do blog é mais grossa: o grip começa mais pra dentro pra não ficar escondido nela */
                .card-frame--destaque .slider-handle {
                    left: 14%;
                }
            }
            /* TABLET (768px - 1024px): cards e setas fixos em px (~984px no total) vazavam da tela.
               Aqui tudo encolhe em vw, mantendo as proporções dos cards do desktop. */
            @media (min-width: 768px) and (max-width: 1024px) {
                .galeria-arrow {
                    padding: 0 1.5vw !important;
                }
                .galeria-arrow img {
                    width: 4vw !important;
                }
                .galeria-cards {
                    gap: 2vw !important;
                    min-width: 0 !important;
                }
                .card-frame--lateral {
                    width: 21vw !important;
                    height: 25.8vw !important;
                }
                .card-frame--destaque {
                    width: 27vw !important;
                    height: 32.8vw !important;
                }
            }
            /* Fade ao trocar de foto no carrossel (galeriaNav) */
            .slider-antes,
            .slider-depois,
            .foto-lateral {
                transition: opacity 0.25s ease;
            }
            .galeria-trocando {
                opacity: 0;
            }
            .foto-lateral {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center top;
            }
        </style>
    @endpush

    <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">

        {{-- Título --}}
        <h2 class="galeria-title" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 36px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 48px; text-align: center;">
            Antes e depois
        </h2>

        <div class="galeria-carousel" style="display: flex; align-items: center; gap: 0;">

            {{-- Seta esquerda --}}
            <button class="galeria-arrow" onclick="galeriaNav(-1)"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="anterior"
                     style="width: 44px; height: auto; transform: rotate(90deg); opacity: 0.9;">
            </button>

            {{-- Cards --}}
            <div class="galeria-cards" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 24px;">

                @php
                    $clientes = [
                        ['id' => 1, 'destaque' => false],
                        ['id' => 3, 'destaque' => true],
                        ['id' => 2, 'destaque' => false],
                    ];
                @endphp

                @foreach($clientes as $cliente)
                    @php
                        $isDestaque = $cliente['destaque'];
                        $id = $cliente['id'];
                        // largura +15% (280 -> 322, 220 -> 253); o min() com vw só entra em telas estreitas
                        // (1025-1100px), onde os cards maiores não caberiam ao lado das setas
                        $w = $isDestaque ? 'min(322px, 28vw)' : 'min(253px, 22vw)';
                        $h = $isDestaque ? '340px' : '270px';
                        $mt = $isDestaque ? '0' : '20px';
                    @endphp

                    {{-- Container do card --}}
                    <div class="card-frame {{ $isDestaque ? 'card-frame--destaque' : 'card-frame--lateral' }}" style="flex-shrink: 0; width: {{ $w }}; height: {{ $h }}; margin-top: {{ $mt }};">

                        @if($isDestaque)
                            {{-- Card destaque: slider interativo --}}
                            <div class="slider-container" id="slider-{{ $id }}">
                                <img class="slider-antes" data-galeria-antes draggable="false"
                                     src="{{ asset('images/fulano'.$id.'-antes.jpg') }}" alt="Antes">
                                <img class="slider-depois" data-galeria-depois draggable="false"
                                     src="{{ asset('images/fulano'.$id.'-depois.jpg') }}" alt="Depois"
                                     id="slider-depois-{{ $id }}">
                                <div class="slider-dica"><span>Arraste</span></div>
                                <div class="slider-handle" id="slider-handle-{{ $id }}">
                                    <div class="slider-handle-icon">↔</div>
                                </div>
                            </div>
                        @else
                            {{-- Card lateral: foto com shape --}}
                            <img class="foto-lateral" data-galeria-slot="{{ $loop->first ? 'anterior' : 'proximo' }}"
                                 src="{{ asset('images/fulano'.$id.'-depois.jpg') }}" alt="Resultado">
                        @endif

                        {{-- Borda do Figma sobreposta (por cima da foto) --}}
                        <img class="card-frame__borda" src="{{ asset('images/retangulo-galeria.png') }}" alt=""
                             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: fill; pointer-events: none; z-index: 5;">

                    </div>
                @endforeach

            </div>

            {{-- Seta direita --}}
            <button class="galeria-arrow" onclick="galeriaNav(1)"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="próximo"
                     style="width: 44px; height: auto; transform: rotate(-90deg); opacity: 0.9;">
            </button>

        </div>

        {{-- CTA --}}
        <div style="text-align: center; margin-top: 48px;">
            <a href="{{ route('appointments.create') }}"
               style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; padding: 14px 40px; background: transparent; color: #F5F0E8; border: 1px solid rgba(245,240,232,0.4); border-radius: 8px; text-decoration: none; display: inline-block;"
               onmouseover="this.style.background='#C9A84C'; this.style.color='#0A0A0A'; this.style.borderColor='#C9A84C';"
               onmouseout="this.style.background='transparent'; this.style.color='#F5F0E8'; this.style.borderColor='rgba(245,240,232,0.4)';">
                Agendar agora
            </a>
        </div>

    </div>

</section>

@php
    $galeriaItens = collect([1, 3, 2])->map(fn ($id) => [
        'antes'  => asset('images/fulano'.$id.'-antes.jpg'),
        'depois' => asset('images/fulano'.$id.'-depois.jpg'),
    ]);
@endphp
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const containers = document.querySelectorAll('.slider-container');
            containers.forEach(container => {
                const id = container.id.replace('slider-', '');
                const depois = document.getElementById('slider-depois-' + id);
                const handle = document.getElementById('slider-handle-' + id);
                let dragging = false;

                const dica = container.querySelector('.slider-dica');

                function update(e) {
                    dica.classList.add('slider-dica--oculta');
                    const rect = container.getBoundingClientRect();
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const rawPct = ((clientX - rect.left) / rect.width) * 100;
                    // reserva margem nas pontas (raio do círculo do grip ~6% da largura do card)
                    // para o ícone nunca ficar cortado na borda;
                    // no mobile a moldura (a do blog) é mais grossa, então a margem é maior
                    const margem = window.matchMedia('(max-width: 767px)').matches ? 14 : 7;
                    const pct = Math.max(margem, Math.min(100 - margem, rawPct));
                    depois.style.clipPath = `inset(0 ${100 - pct}% 0 0)`;
                    handle.style.left = pct + '%';
                }

                container.addEventListener('dragstart', e => e.preventDefault());
                container.addEventListener('mousedown',  e => { e.preventDefault(); dragging = true; update(e); });
                container.addEventListener('touchstart', e => { dragging = true; update(e); }, { passive: true });
                window.addEventListener('mousemove',  e => { if (dragging) update(e); });
                window.addEventListener('touchmove',  e => { if (dragging) update(e); }, { passive: true });
                window.addEventListener('mouseup',  () => dragging = false);
                window.addEventListener('touchend', () => dragging = false);
            });
        });

        // Carrossel: as setas giram a lista de clientes. O card do meio (slider antes/depois) mostra o
        // cliente atual e os laterais mostram o "depois" do anterior e do próximo. Começa no cliente 3,
        // que é o que já aparece no meio ao carregar a página.
        const galeriaItens = {{ Js::from($galeriaItens) }};
        let galeriaAtual = 1;

        // pré-carrega todas as fotos pra troca não piscar
        galeriaItens.forEach(item => { new Image().src = item.antes; new Image().src = item.depois; });

        function galeriaNav(dir) {
            const total = galeriaItens.length;
            galeriaAtual = (galeriaAtual + dir + total) % total;
            const atual    = galeriaItens[galeriaAtual];
            const anterior = galeriaItens[(galeriaAtual - 1 + total) % total];
            const proximo  = galeriaItens[(galeriaAtual + 1) % total];

            const card    = document.querySelector('#galeria .slider-container');
            const antes   = card.querySelector('[data-galeria-antes]');
            const depois  = card.querySelector('[data-galeria-depois]');
            const handle  = card.querySelector('.slider-handle');
            const latAnt  = document.querySelector('#galeria [data-galeria-slot="anterior"]');
            const latProx = document.querySelector('#galeria [data-galeria-slot="proximo"]');
            const imgs = [antes, depois, latAnt, latProx];

            imgs.forEach(img => img.classList.add('galeria-trocando'));
            setTimeout(() => {
                antes.src   = atual.antes;
                depois.src  = atual.depois;
                latAnt.src  = anterior.depois;
                latProx.src = proximo.depois;
                // volta o slider pro estado inicial (só o "antes" visível) e mostra a dica de novo
                depois.style.clipPath = '';
                handle.style.left = '';
                card.querySelector('.slider-dica').classList.remove('slider-dica--oculta');
                imgs.forEach(img => img.classList.remove('galeria-trocando'));
            }, 250);
        }
    </script>
@endpush
