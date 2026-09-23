{{-- ═══════════ BLOG — CORTES RECENTES ═══════════ --}}
<section id="blog" style="background: #0A0A0A; padding: 60px 0 80px;">

    {{-- RESPONSIVIDADE MOBILE (até 767px): textos à esquerda, só o card central e setas abaixo dele
         (mesmo padrão da galeria). Desktop continua com os estilos inline originais. --}}
    <style>
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

                @foreach($cortes as $corte)
                    <div class="{{ !empty($corte['centro']) ? 'blog-card--centro' : 'blog-card--lateral' }}" style="flex-shrink: 0; width: {{ $corte['w'] }}; height: {{ $corte['h'] }}; margin-top: {{ $corte['mt'] }}; position: relative;">

                        {{-- Foto recortada no shape exato da moldura, via mask-image --}}
                        <img src="{{ asset($corte['foto']) }}" alt="Corte"
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

@push('scripts')
    <script>
        function blogNav(dir) {
            console.log('Blog nav:', dir);
            // Implementar com dados reais do banco futuramente
        }
    </script>
@endpush
