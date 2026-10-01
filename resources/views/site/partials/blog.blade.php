{{-- ═══════════ BLOG — CORTES RECENTES ═══════════ --}}
@php
    // Fotos do carrossel, na ordem em que giram. Começa no fulano5 no meio (o que já aparece ao carregar),
    // com o fulano4 à esquerda e o fulano1 à direita. "flip" espelha a foto (o fulano5 olha pro outro lado).
    // "nome" e "texto" aparecem na parte de baixo do card.
    $blogItens = collect([
        ['foto' => 'images/fulano4-depois.jpg', 'nome' => 'Degradê Social',
         'texto' => 'Laterais em degradê suave e topo mais cheio. Discreto e alinhado para o dia a dia.'],
        ['foto' => 'images/fulano5-depois.jpg', 'flip' => true, 'nome' => 'Corte Italiano',
         'texto' => 'Volume no topo penteado para trás, com acabamento natural. Elegância clássica com movimento.'],
        ['foto' => 'images/fulano1-depois.jpg', 'nome' => 'Barba Desenhada',
         'texto' => 'Laterais baixas e barba alinhada com contorno marcado. Visual maduro e imponente.'],
        ['foto' => 'images/fulano6-depois.jpg', 'nome' => 'Low Fade',
         'texto' => 'Degradê baixo, rente à orelha, com topo médio. Moderno sem perder a sobriedade.'],
        ['foto' => 'images/fulano2-depois.jpg', 'nome' => 'Topete Clássico',
         'texto' => 'Topo com volume penteado para cima e laterais curtas. Um clássico que valoriza o rosto.'],
        ['foto' => 'images/fulano3-depois.jpg', 'nome' => 'Risca Lateral',
         'texto' => 'Risca marcada na navalha com degradê nas laterais. Acabamento preciso e estiloso.'],
    ])->map(fn ($item) => [
        'foto'  => asset($item['foto']),
        'flip'  => $item['flip'] ?? false,
        'nome'  => $item['nome'],
        'texto' => $item['texto'],
    ]);
@endphp
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

        /* Nome do corte + explicação na parte de baixo do card. O degradê escuro usa a mesma máscara da foto,
           então fica só dentro da moldura. Tamanhos em clamp pra caber nos cards menores do tablet. */
        #blog .blog-card-info {
            position: absolute;
            inset: 0;
            z-index: 4;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            text-align: center;
            padding: 0 9% 9%;
            background: linear-gradient(to top, rgba(10,10,10,0.92) 0%, rgba(10,10,10,0.6) 28%, transparent 50%);
            -webkit-mask-size: 100% 100%;
            -webkit-mask-repeat: no-repeat;
            mask-size: 100% 100%;
            mask-repeat: no-repeat;
        }
        #blog .blog-card-nome {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: clamp(14px, 1.4vw, 20px);
            line-height: 1.1;
            color: #C9A84C;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 0 0 6px;
        }
        /* Cards laterais são mais estreitos: nome menor pra caber numa linha só */
        #blog .blog-card--lateral .blog-card-nome {
            font-size: clamp(12px, 1.1vw, 16px);
            letter-spacing: 0.03em;
        }
        #blog .blog-card-texto {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: clamp(9px, 0.78vw, 11px);
            line-height: 1.45;
            color: rgba(245,240,232,0.85);
            margin: 0;
            /* no máximo 3 linhas */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        #blog .blog-card-info p {
            transition: opacity 0.25s ease;
        }

        @media (max-width: 767px) {
            #blog .blog-card-nome  { font-size: 20px; }
            #blog .blog-card-texto { font-size: 11px; }

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

                        {{-- Foto recortada no shape exato da moldura, via mask-image. A máscara fica no wrapper e o
                             espelhamento (flip) só na <img> de dentro: se os dois ficassem no mesmo elemento, a máscara
                             espelhava junto e, nos cards laterais (que são inclinados), a foto vazava da moldura. --}}
                        <div style="position: absolute; inset: 0;
                                -webkit-mask-image: url('{{ asset($corte['mask']) }}');
                                -webkit-mask-size: 100% 100%;
                                -webkit-mask-repeat: no-repeat;
                                mask-image: url('{{ asset($corte['mask']) }}');
                                mask-size: 100% 100%;
                                mask-repeat: no-repeat;">
                            <img src="{{ asset($corte['foto']) }}" alt="Corte" data-blog-slot="{{ ['anterior', 'atual', 'proximo'][$i] }}"
                                 style="position: absolute; inset: 0; width: 100%; height: 100%;
                                    object-fit: cover; object-position: center top;
                                    {{ !empty($corte['flip']) ? 'transform: scaleX(-1);' : '' }}">
                        </div>

                        {{-- Nome do corte + explicação --}}
                        <div class="blog-card-info" data-blog-info="{{ ['anterior', 'atual', 'proximo'][$i] }}"
                             style="-webkit-mask-image: url('{{ asset($corte['mask']) }}'); mask-image: url('{{ asset($corte['mask']) }}');">
                            <p class="blog-card-nome">{{ $blogItens[$i]['nome'] }}</p>
                            <p class="blog-card-texto">{{ $blogItens[$i]['texto'] }}</p>
                        </div>

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

            const imgs  = document.querySelectorAll('#blog [data-blog-slot]');
            const infos = document.querySelectorAll('#blog [data-blog-info]');
            const textos = document.querySelectorAll('#blog [data-blog-info] p');
            imgs.forEach(img => img.classList.add('blog-trocando'));
            textos.forEach(p => p.classList.add('blog-trocando'));
            setTimeout(() => {
                imgs.forEach(img => {
                    const item = slots[img.dataset.blogSlot];
                    img.src = item.foto;
                    img.style.transform = item.flip ? 'scaleX(-1)' : '';
                    img.classList.remove('blog-trocando');
                });
                infos.forEach(info => {
                    const item = slots[info.dataset.blogInfo];
                    info.querySelector('.blog-card-nome').textContent  = item.nome;
                    info.querySelector('.blog-card-texto').textContent = item.texto;
                });
                textos.forEach(p => p.classList.remove('blog-trocando'));
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
