{{-- ═══════════ BLOG — CORTES RECENTES ═══════════ --}}
<section id="blog" style="background: #0A0A0A; padding: 60px 0 80px;">

    {{-- RESPONSIVIDADE MOBILE (até 767px): textos à esquerda, só o card central e setas abaixo dele
         (mesmo padrão da galeria). Desktop continua com os estilos inline originais. --}}
    <style>
        /* Fade ao trocar de foto no carrossel (blogNav) */
        #blog [data-blog-slot] {
            transition: opacity 0.25s ease;
        }
        #blog .blog-trocando {
            opacity: 0;
        }

        @media (max-width: 767px) {
            #blog .blog-head {
                text-align: left !important;
                padding: 0 24px !important;
            }
            #blog .blog-label-row {
                justify-content: flex-start !important;
            }

            #blog .blog-carousel {
                flex-wrap: wrap !important;
                justify-content: center !important;
                column-gap: 28px !important;
            }
            #blog #blog-track {
                flex: 0 0 100% !important;
                gap: 0 !important;
            }
            #blog .blog-card--lateral {
                display: none !important;
            }
            #blog .blog-card--centro {
                margin-top: 0 !important;
            }

            /* Setas dentro de um círculo transparente com borda dourada de 1px (igual à galeria) */
            #blog .blog-arrow {
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
            #blog .blog-arrow {
                background: url('{{ asset('images/seta-circulo.svg') }}') center / 100% 100% no-repeat !important;
            }
            #blog .blog-carousel > .blog-arrow:first-child {
                transform: scaleX(-1);
            }
            #blog .blog-arrow img {
                display: none !important;
            }
        }

        /* TABLET (768px - 1024px): cards e setas fixos em px (~1026px no total) vazavam da tela.
           Aqui tudo encolhe em vw, mantendo as proporções das molduras do desktop. */
        @media (min-width: 768px) and (max-width: 1024px) {
            #blog .blog-arrow {
                padding: 0 1.5vw !important;
            }
            #blog .blog-arrow img {
                width: 4vw !important;
            }
            #blog #blog-track {
                gap: 3vw !important;
                min-width: 0 !important;
            }
            #blog .blog-card--lateral {
                width: 21vw !important;
                height: 28.3vw !important;
            }
            #blog .blog-card--centro {
                width: 25vw !important;
                height: 34.3vw !important;
            }
        }
    </style>

    {{-- Título --}}
    <div class="blog-head" style="text-align: center; margin-bottom: 48px;">
        <div class="blog-label-row" style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 14px;">
            <span style="display: block; width: 60px; height: 1px; background: #F5F0E8; opacity: 0.5;"></span>
            <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: rgba(245,240,232,0.65); text-transform: uppercase;">Nossos Trabalhos</span>
            <span style="display: block; width: 60px; height: 1px; background: #F5F0E8; opacity: 0.5;"></span>
        </div>
        <h2 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 36px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.08em; margin: 0;">
            Cortes Recentes
        </h2>
    </div>

    {{-- Carrossel --}}
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">
        <div class="blog-carousel" style="display: flex; align-items: center; gap: 0;">

            {{-- Seta esquerda --}}
            <button class="blog-arrow" onclick="blogNav(-1)"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="anterior"
                     style="width: 44px; height: auto; transform: rotate(90deg); opacity: 0.9;">
            </button>

            {{-- Cards --}}
            <div id="blog-track" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 40px;">

                @php
                    $cortes = [
                        [
                            'foto'     => 'images/fulano4-depois.jpg',
                            'moldura'  => 'images/Rectangle-esquerda-blog.png',
                            // máscara preenchida (gerada a partir da moldura, via flood-fill)
                            // usada para recortar a foto exatamente no contorno da moldura
                            'mask'     => 'images/rectangle-esquerda-blog-mask.png',
                            'w'        => '230px',
                            'h'        => '310px',
                            'mt'       => '15px',
                        ],
                        [
                            'foto'     => 'images/fulano5-depois.jpg',
                            'moldura'  => 'images/Rectangle-centro-blog.png',
                            'mask'     => 'images/rectangle-centro-blog-mask.png',
                            'w'        => '270px',
                            'h'        => '370px',
                            'mt'       => '0px',
                            'flip'     => true,
                            'centro'   => true,
                        ],
                        [
                            'foto'     => 'images/fulano1-depois.jpg',
                            'moldura'  => 'images/Rectangle-direita-blog.png',
                            'mask'     => 'images/rectangle-direita-blog-mask.png',
                            'w'        => '230px',
                            'h'        => '310px',
                            'mt'       => '15px',
                        ],
                    ];
                @endphp

                @foreach($cortes as $i => $corte)
                    <div class="{{ !empty($corte['centro']) ? 'blog-card--centro' : 'blog-card--lateral' }}" style="flex-shrink: 0; width: {{ $corte['w'] }}; height: {{ $corte['h'] }}; margin-top: {{ $corte['mt'] }}; position: relative;">

                        {{-- Foto recortada no shape exato da moldura, via mask-image --}}
                        <img src="{{ asset($corte['foto']) }}" alt="Corte" data-blog-slot="{{ ['anterior', 'atual', 'proximo'][$i] }}"
                             style="position: absolute; inset: 0; width: 100%; height: 100%;
                                object-fit: cover; object-position: center top;
                                {{ !empty($corte['flip']) ? 'transform: scaleX(-1);' : '' }}
                                -webkit-mask-image: url('{{ asset($corte['mask']) }}');
                                -webkit-mask-size: 100% 100%;
                                -webkit-mask-repeat: no-repeat;
                                mask-image: url('{{ asset($corte['mask']) }}');
                                mask-size: 100% 100%;
                                mask-repeat: no-repeat;">

                        {{-- Moldura do Figma por cima --}}
                        <img src="{{ asset($corte['moldura']) }}" alt=""
                             style="position: absolute; inset: 0; width: 100%; height: 100%;
                                object-fit: fill; pointer-events: none; z-index: 5;">

                    </div>
                @endforeach

            </div>

            {{-- Seta direita --}}
            <button class="blog-arrow" onclick="blogNav(1)"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="próximo"
                     style="width: 44px; height: auto; transform: rotate(-90deg); opacity: 0.9;">
            </button>

        </div>
    </div>

</section>

@php
    // Fotos do carrossel, na ordem em que giram. Começa no fulano5 no meio (o que já aparece ao carregar),
    // com o fulano4 à esquerda e o fulano1 à direita. "flip" espelha a foto (o fulano5 olha pro outro lado).
    $blogItens = collect([
        ['foto' => 'images/fulano4-depois.jpg'],
        ['foto' => 'images/fulano5-depois.jpg', 'flip' => true],
        ['foto' => 'images/fulano1-depois.jpg'],
        ['foto' => 'images/fulano6-depois.jpg'],
        ['foto' => 'images/fulano2-depois.jpg'],
        ['foto' => 'images/fulano3-depois.jpg'],
    ])->map(fn ($item) => ['foto' => asset($item['foto']), 'flip' => $item['flip'] ?? false]);
@endphp
@push('scripts')
    <script>
        const blogItens = {{ Js::from($blogItens) }};
        let blogAtual = 1;

        // pré-carrega todas as fotos pra troca não piscar
        blogItens.forEach(item => { new Image().src = item.foto; });

        function blogNav(dir) {
            const total = blogItens.length;
            blogAtual = (blogAtual + dir + total) % total;
            const slots = {
                anterior: blogItens[(blogAtual - 1 + total) % total],
                atual:    blogItens[blogAtual],
                proximo:  blogItens[(blogAtual + 1) % total],
            };

            const imgs = document.querySelectorAll('#blog [data-blog-slot]');
            imgs.forEach(img => img.classList.add('blog-trocando'));
            setTimeout(() => {
                imgs.forEach(img => {
                    const item = slots[img.dataset.blogSlot];
                    img.src = item.foto;
                    img.style.transform = item.flip ? 'scaleX(-1)' : '';
                    img.classList.remove('blog-trocando');
                });
            }, 250);
        }

        // No celular dá pra arrastar o card pro lado, além das setas
        document.addEventListener('DOMContentLoaded', function () {
            const track = document.getElementById('blog-track');
            let inicioX = null;
            track.addEventListener('touchstart', e => { inicioX = e.touches[0].clientX; }, { passive: true });
            track.addEventListener('touchend', e => {
                if (inicioX === null) return;
                const dx = e.changedTouches[0].clientX - inicioX;
                if (Math.abs(dx) > 40) blogNav(dx < 0 ? 1 : -1);
                inicioX = null;
            });
        });
    </script>
@endpush
