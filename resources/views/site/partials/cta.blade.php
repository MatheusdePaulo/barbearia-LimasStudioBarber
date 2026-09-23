{{-- ═══════════ CTA FINAL — AGENDE SEU HORÁRIO ═══════════ --}}
<section id="contato" style="background: #0A0A0A; padding-top: 60px;">

    {{-- Bloco da foto ponta a ponta (quebra de layout, igual à seção galeria) --}}
    <div style="position: relative; width: 100vw; margin-left: calc(-1 * (100vw - 100%) / 2); height: 300px; overflow: hidden;">

        {{-- Foto do ambiente --}}
        <img src="{{ asset('images/Ambiente.png') }}" alt="Lima's Studio Barber"
             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">

        {{-- Overlay gradiente leve --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.85) 100%);"></div>

        {{-- Conteúdo centralizado --}}
        <div style="position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 40px; gap: 16px;">

            {{-- Label --}}
            <div style="display: flex; align-items: center; gap: 14px;">
                <span style="display: block; width: 50px; height: 1px; background: rgba(245,240,232,0.5);"></span>
                <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: rgba(245,240,232,0.7); text-transform: uppercase;">
                    Agende seu horário
                </span>
                <span style="display: block; width: 50px; height: 1px; background: rgba(245,240,232,0.5);"></span>
            </div>

            {{-- Título --}}
            <h2 style="font-family: 'Cormorant Garamond', serif; font-weight: 700; font-size: clamp(2.5rem, 5vw, 3.5rem); color: #F5F0E8; text-transform: uppercase; letter-spacing: 0.03em; line-height: 1.05; margin: 0;">
                Seu Estilo Começa Aqui
            </h2>

            {{-- Subtexto --}}
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 15px; color: rgba(245,240,232,0.85); margin: 0; max-width: 480px; line-height: 1.6;">
                Reserve seu horário e experimente um corte à altura da sua história.
            </p>

            {{-- Botão --}}
            <a href="{{ route('agendar') }}"
               style="font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 14px; letter-spacing: 0.12em; text-transform: uppercase; padding: 16px 48px; background: #C9A84C; color: #0A0A0A; border-radius: 8px; text-decoration: none; margin-top: 8px; display: inline-block; transition: all 0.3s ease;"
               onmouseover="this.style.background='#E2C97E'; this.style.transform='scale(1.02)';"
               onmouseout="this.style.background='#C9A84C'; this.style.transform='scale(1)';">
                Agendar agora
            </a>

        </div>
    </div>

</section>
