{{-- ═══════════ QUEBRA DE LAYOUT — GALERIA ═══════════ --}}
<div style="position: relative; width: 100vw; margin-left: calc(-1 * (100vw - 100%) / 2); height: 200px; overflow: hidden;">
    <img src="{{ asset('images/Ambiente.png') }}" alt=""
         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.85) 100%);"></div>
    <div style="position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 24px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
            <span style="display: block; width: 40px; height: 1px; background: #F5F0E8; opacity: 0.5;"></span>
            <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: rgba(245,240,232,0.8); text-transform: uppercase;">Transformações</span>
            <span style="display: block; width: 40px; height: 1px; background: #F5F0E8; opacity: 0.5;"></span>
        </div>
        <h2 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 28px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.03em; line-height: 1.3; margin: 0; max-width: 600px;">
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
            .slider-container:active .slider-handle-icon {
                transform: translate(-50%, -50%) scale(1.1);
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
        <h2 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: 36px; color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 48px; text-align: center;">
            Antes e depois
        </h2>

        <div style="display: flex; align-items: center; gap: 0;">

            {{-- Seta esquerda --}}
            <button onclick="galeriaNav(-1)"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="anterior"
                     style="width: 44px; height: auto; transform: rotate(90deg); opacity: 0.9;">
            </button>

            {{-- Cards --}}
            <div style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 24px;">

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
                        $w = $isDestaque ? '280px' : '220px';
                        $h = $isDestaque ? '340px' : '270px';
                        $mt = $isDestaque ? '0' : '20px';
                    @endphp

                    {{-- Container do card --}}
                    <div class="card-frame" style="flex-shrink: 0; width: {{ $w }}; height: {{ $h }}; margin-top: {{ $mt }};">

                        @if($isDestaque)
                            {{-- Card destaque: slider interativo --}}
                            <div class="slider-container" id="slider-{{ $id }}">
                                <img class="slider-antes" draggable="false"
                                     src="{{ asset('images/fulano'.$id.'-antes.jpg') }}" alt="Antes">
                                <img class="slider-depois" draggable="false"
                                     src="{{ asset('images/fulano'.$id.'-depois.jpg') }}" alt="Depois"
                                     id="slider-depois-{{ $id }}">
                                <div class="slider-handle" id="slider-handle-{{ $id }}">
                                    <div class="slider-handle-icon">↔</div>
                                </div>
                            </div>
                        @else
                            {{-- Card lateral: foto com shape --}}
                            <img class="foto-lateral"
                                 src="{{ asset('images/fulano'.$id.'-depois.jpg') }}" alt="Resultado">
                        @endif

                        {{-- Borda do Figma sobreposta (por cima da foto) --}}
                        <img src="{{ asset('images/retangulo-galeria.png') }}" alt=""
                             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: fill; pointer-events: none; z-index: 5;">

                    </div>
                @endforeach

            </div>

            {{-- Seta direita --}}
            <button onclick="galeriaNav(1)"
                    style="flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0 20px;">
                <img src="{{ asset('images/polygon-produtos.png') }}" alt="próximo"
                     style="width: 44px; height: auto; transform: rotate(-90deg); opacity: 0.9;">
            </button>

        </div>

        {{-- CTA --}}
        <div style="text-align: center; margin-top: 48px;">
            <a href="{{ route('agendar') }}"
               style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; padding: 14px 40px; background: transparent; color: #F5F0E8; border: 1px solid rgba(245,240,232,0.4); border-radius: 8px; text-decoration: none; display: inline-block;"
               onmouseover="this.style.background='#C9A84C'; this.style.color='#0A0A0A'; this.style.borderColor='#C9A84C';"
               onmouseout="this.style.background='transparent'; this.style.color='#F5F0E8'; this.style.borderColor='rgba(245,240,232,0.4)';">
                Agendar agora
            </a>
        </div>

    </div>

</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const containers = document.querySelectorAll('.slider-container');
            containers.forEach(container => {
                const id = container.id.replace('slider-', '');
                const depois = document.getElementById('slider-depois-' + id);
                const handle = document.getElementById('slider-handle-' + id);
                let dragging = false;

                function update(e) {
                    const rect = container.getBoundingClientRect();
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const rawPct = ((clientX - rect.left) / rect.width) * 100;
                    // reserva margem nas pontas (raio do círculo do grip ~6% da largura do card)
                    // para o ícone nunca ficar cortado na borda
                    const pct = Math.max(7, Math.min(93, rawPct));
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

        function galeriaNav(dir) {
            console.log('Galeria nav:', dir);
        }
    </script>
@endpush
