{{-- ═══════════ QUEBRA DE LAYOUT — GALERIA ═══════════ --}}
<div style="position: relative; width: 100vw; margin-left: calc(-1 * (100vw - 100%) / 2); height: 200px; overflow: hidden;">
    <img loading="lazy" decoding="async" src="{{ asset('images/Ambiente.webp') }}" alt=""
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
            }
            /* Desktop/tablet: cards retangulares com borda dourada arredondada (laterais 330x500,
               central 385x580). A borda fica num ::after por cima da foto. No mobile o card central
               usa a moldura do blog (ver @media max-width 767px). */
            @media (min-width: 768px) {
                .card-frame {
                    border-radius: 14px;
                }
                .card-frame::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    border: 3px solid #C9A84C;
                    border-radius: 14px;
                    pointer-events: none;
                    z-index: 15;
                }

                /* Efeitos: entrada com fade ao chegar na seção, card sobe com brilho dourado no hover,
                   reflexo de luz passando pela foto, laterais apagados que acendem e o central pulsando */
                .card-frame {
                    opacity: 0;
                    transform: translateY(40px);
                    transition: opacity 0.7s ease, transform 0.45s ease, box-shadow 0.45s ease;
                }
                .galeria-cards--visivel .card-frame {
                    opacity: 1;
                    transform: none;
                }
                .galeria-cards--visivel .card-frame--lateral:last-child {
                    transition-delay: 0.15s, 0s, 0s;
                }
                .galeria-cards--visivel .card-frame--destaque {
                    transition-delay: 0.08s, 0s, 0s;
                }
                .card-frame::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    left: -75%;
                    width: 50%;
                    background: linear-gradient(105deg, transparent, rgba(255, 236, 190, 0.28), transparent);
                    transform: skewX(-20deg);
                    pointer-events: none;
                    z-index: 14;
                }
                .card-frame:hover::before {
                    left: 130%;
                    transition: left 0.9s ease;
                }
                .card-frame--lateral {
                    cursor: pointer;
                }
                .card-frame--lateral .foto-lateral {
                    filter: brightness(0.6) saturate(0.8);
                }
                .card-frame--lateral:hover .foto-lateral {
                    filter: none;
                    transform: scale(1.22);
                }
                .galeria-cards--visivel .card-frame--lateral:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 18px 40px rgba(201, 168, 76, 0.28);
                }
                .card-frame--destaque {
                    animation: galeriaPulso 3.5s ease-in-out infinite;
                }
                .galeria-cards--visivel .card-frame--destaque:hover {
                    transform: translateY(-6px);
                }
                @keyframes galeriaPulso {
                    0%, 100% { box-shadow: 0 0 18px rgba(201, 168, 76, 0.18); }
                    50%      { box-shadow: 0 0 38px rgba(201, 168, 76, 0.42); }
                }
                @media (prefers-reduced-motion: reduce) {
                    .card-frame,
                    .card-frame--lateral .foto-lateral {
                        transition: none;
                        animation: none;
                    }
                    .card-frame::before {
                        display: none;
                    }
                }
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
            /* As fotos são padronizadas (961x1637, cabeça em ~40% x 45%): antes, depois e laterais
               usam o mesmo enquadramento, assim o rosto fica no mesmo lugar ao arrastar o slider */
            .slider-antes,
            .slider-depois,
            .foto-lateral {
                object-position: 40% 50%;
                transform: scale(1.15);
                transform-origin: 40% 45%;
            }
            .slider-depois {
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
                    width: 23vw !important;
                }
                .card-frame--destaque {
                    width: 27.5vw !important;
                }
            }
            /* Fade ao trocar de foto no carrossel (galeriaNav) */
            .slider-antes,
            .slider-depois,
            .foto-lateral {
                transition: opacity 0.25s ease, transform 0.5s ease, filter 0.45s ease;
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
            }
        </style>
    @endpush

    <div style="max-width: 1320px; margin: 0 auto; padding: 0 24px;">

        {{-- Título --}}
        <h2 class="galeria-title" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 36px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 48px; text-align: center;">
            Antes e depois
        </h2>

        <div class="galeria-carousel" style="display: flex; align-items: center; gap: 0;">

            {{-- Seta esquerda --}}
            <button class="galeria-arrow" onclick="galeriaNav(-1)" aria-label="Antes e depois anterior"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img loading="lazy" decoding="async" src="{{ asset('images/polygon-produtos.png') }}" alt="anterior"
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
                        // laterais 330x500 e central 385x580; o min() com vw só entra abaixo de ~1320px,
                        // onde os cards não caberiam ao lado das setas (a altura acompanha pelo aspect-ratio)
                        $w = $isDestaque ? 'min(385px, 27vw)' : 'min(330px, 23vw)';
                        $ratio = $isDestaque ? '385 / 580' : '330 / 500';
                    @endphp

                    {{-- Container do card --}}
                    <div class="card-frame {{ $isDestaque ? 'card-frame--destaque' : 'card-frame--lateral' }}"
                         @unless($isDestaque) onclick="galeriaNav({{ $loop->first ? -1 : 1 }})" @endunless
                         style="flex-shrink: 0; width: {{ $w }}; aspect-ratio: {{ $ratio }};">

                        @if($isDestaque)
                            {{-- Card destaque: slider interativo --}}
                            <div class="slider-container" id="slider-{{ $id }}">
                                <img loading="lazy" decoding="async" class="slider-antes" data-galeria-antes draggable="false"
                                     src="{{ asset('images/fulano'.$id.'-antes.webp') }}" alt="Antes">
                                <img loading="lazy" decoding="async" class="slider-depois" data-galeria-depois draggable="false"
                                     src="{{ asset('images/fulano'.$id.'-depois.webp') }}" alt="Depois"
                                     id="slider-depois-{{ $id }}">
                                <div class="slider-dica"><span>Arraste</span></div>
                                <div class="slider-handle" id="slider-handle-{{ $id }}">
                                    <div class="slider-handle-icon">↔</div>
                                </div>
                            </div>
                        @else
                            {{-- Card lateral: foto com shape --}}
                            <img loading="lazy" decoding="async" class="foto-lateral" data-galeria-slot="{{ $loop->first ? 'anterior' : 'proximo' }}"
                                 src="{{ asset('images/fulano'.$id.'-depois.webp') }}" alt="Resultado">
                        @endif


                    </div>
                @endforeach

            </div>

            {{-- Seta direita --}}
            <button class="galeria-arrow" onclick="galeriaNav(1)" aria-label="Próximo antes e depois"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img loading="lazy" decoding="async" src="{{ asset('images/polygon-produtos.png') }}" alt="próximo"
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
        'antes'  => asset('images/fulano'.$id.'-antes.webp'),
        'depois' => asset('images/fulano'.$id.'-depois.webp'),
    ]);
@endphp
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Entrada dos cards (fade + subida) quando a seção aparece na tela
            const galeriaCards = document.querySelector('#galeria .galeria-cards');
            if ('IntersectionObserver' in window) {
                new IntersectionObserver((entradas, obs) => {
                    if (entradas[0].isIntersecting) {
                        galeriaCards.classList.add('galeria-cards--visivel');
                        obs.disconnect();
                    }
                }, { threshold: 0.25 }).observe(galeriaCards);
            } else {
                galeriaCards.classList.add('galeria-cards--visivel');
            }

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
