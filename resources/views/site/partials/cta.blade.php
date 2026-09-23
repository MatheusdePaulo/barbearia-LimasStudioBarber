{{-- ═══════════ CTA FINAL — AGENDE SEU HORÁRIO ═══════════ --}}
<section id="contato" style="background: #0A0A0A; padding-top: 60px;">

    {{-- RESPONSIVIDADE MOBILE (até 767px): só os textos à esquerda, o botão continua centralizado.
         Desktop continua com os estilos inline originais. --}}
    <style>
        @media (max-width: 767px) {
            /* Seção inteira 20% mais pra cima: 20% da altura dela (60px de padding + 255px da foto = 315px) ≈ 63px.
               Come o espaço vazio do fim do #blog (80px de padding-bottom); o que vem depois sobe junto. */
            #contato {
                margin-top: -63px !important;
            }

            /* Bloco da foto mais baixo: 300px - 8% em cima (24px) - 7% embaixo (21px) = 255px */
            #contato .cta-banner {
                height: 255px !important;
            }

            #contato .cta-content {
                align-items: flex-start !important;
                text-align: left !important;
                padding: 0 24px !important;
                gap: 12px !important;
            }

            /* Label em uma linha só: tracinhos e espaçamento menores */
            #contato .cta-label-row {
                gap: 10px !important;
            }
            #contato .cta-label-line {
                width: 28px !important;
            }
            #contato .cta-label-text {
                font-size: 10px !important;
                letter-spacing: 0.22em !important;
                white-space: nowrap !important;
            }

            /* Textos menores (desktop: título 40–56px, subtexto 15px) */
            #contato .cta-title {
                font-size: 30px !important;
            }
            /* "Começa Aqui" sempre junto na segunda linha */
            #contato .cta-title-l2 {
                display: block;
            }
            #contato .cta-sub {
                font-size: 13px !important;
            }
            /* Botão menor no mobile (desktop: 14px / 16px 48px) */
            #contato .cta-btn {
                align-self: center !important;
                font-size: 12px !important;
                padding: 12px 32px !important;
            }
        }
    </style>

    {{-- Bloco da foto ponta a ponta (quebra de layout, igual à seção galeria) --}}
    <div class="cta-banner" style="position: relative; width: 100vw; margin-left: calc(-1 * (100vw - 100%) / 2); height: 300px; overflow: hidden;">

        {{-- Foto do ambiente --}}
        <img src="{{ asset('images/Ambiente.png') }}" alt="Lima's Studio Barber"
             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">

        {{-- Overlay gradiente leve --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.85) 100%);"></div>

        {{-- Conteúdo centralizado --}}
        <div class="cta-content" style="position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 40px; gap: 16px;">

            {{-- Label --}}
            <div class="cta-label-row" style="display: flex; align-items: center; gap: 14px;">
                <span class="cta-label-line" style="display: block; width: 50px; height: 1px; background: rgba(245,240,232,0.5);"></span>
                <span class="cta-label-text" style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: rgba(245,240,232,0.7); text-transform: uppercase;">
                    Agende seu horário
                </span>
                <span class="cta-label-line" style="display: block; width: 50px; height: 1px; background: rgba(245,240,232,0.5);"></span>
            </div>

            {{-- Título --}}
            <h2 class="cta-title" style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: clamp(2.5rem, 5vw, 3.5rem); color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.03em; line-height: 1.05; margin: 0;">
                Seu Estilo <span class="cta-title-l2">Começa Aqui</span>
            </h2>

            {{-- Subtexto --}}
            <p class="cta-sub" style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 15px; color: rgba(245,240,232,0.85); margin: 0; max-width: 480px; line-height: 1.6;">
                Reserve seu horário e experimente um corte à altura da sua história.
            </p>

            {{-- Botão --}}
            <a class="cta-btn" href="{{ route('appointments.create') }}"
               style="font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 14px; letter-spacing: 0.12em; text-transform: uppercase; padding: 16px 48px; background: #C9A84C; color: #0A0A0A; border-radius: 8px; text-decoration: none; margin-top: 8px; display: inline-block; transition: all 0.3s ease;"
               onmouseover="this.style.background='#E2C97E'; this.style.transform='scale(1.02)';"
               onmouseout="this.style.background='#C9A84C'; this.style.transform='scale(1)';">
                Agendar agora
            </a>

        </div>
    </div>

</section>
