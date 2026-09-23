@push('styles')
    <style>
        @keyframes mousePulse {
            0%, 100% { opacity: 0.5; transform: translateX(-50%) translateY(0); }
            50% { opacity: 1; transform: translateX(-50%) translateY(4px); }
        }
        @keyframes mouseWheel {
            0% { transform: translateY(0); opacity: 1; }
            60% { transform: translateY(6px); opacity: 0; }
            61% { transform: translateY(0); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* ── HERO RESPONSIVO ── */
        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: clamp(3.5rem, 7vw, 5.5rem);
            color: #F5F0E8;
            line-height: 1.05;
            text-transform: uppercase;
            margin-bottom: 20px;
            max-width: 900px;
            text-align: center;
        }
        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .hero-btn-primary {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 14px 32px;
            background: #C9A84C;
            color: #0A0A0A;
            border-radius: 8px;
            text-decoration: none;
            white-space: nowrap;
        }
        .hero-btn-secondary {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 14px 32px;
            border: 1px solid rgba(255,255,255,0.6);
            color: #F5F0E8;
            border-radius: 8px;
            text-decoration: none;
            white-space: nowrap;
        }

        /* Mobile */
        @media (max-width: 640px) {
            .hero-title {
                font-size: clamp(3rem, 14vw, 4rem);
                text-align: left;
                line-height: 1.365; /* +30% sobre o line-height base (1.05) */
                transform: translateY(-5%);
            }
            .hero-subtitle {
                text-align: left !important;
                font-size: 16.8px !important; /* +20% sobre 14px */
                transform: translateY(5%);
                width: 100%;
            }
            .hero-desde {
                justify-content: flex-start !important;
                transform: translateY(-20%);
                width: 100%;
            }
            .hero-buttons {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
                gap: 32px; /* dobro do gap base (16px) */
                transform: translateY(25%);
            }
            .hero-btn-primary,
            .hero-btn-secondary {
                width: 100%;
                text-align: center;
                padding: 18px 32px;
                font-size: 13px;
            }
            .hero-btn-primary {
                transform: translateY(-12%);
            }
            .hero-mouse { display: none; }
            .hero-info-bar span.separador { display: none; }
            .hero-info-bar {
                flex-wrap: wrap;
                gap: 8px !important;
                justify-content: flex-end;
            }
        }

        /* Tablet */
        @media (min-width: 641px) and (max-width: 1024px) {
            .hero-title {
                font-size: clamp(3rem, 8vw, 4.5rem);
            }
        }
    </style>
@endpush

{{-- ═══════════ HERO ═══════════ --}}
<section style="position: relative; height: 100vh; display: flex; flex-direction: column; overflow: hidden; width: 100vw; margin-left: calc(-1 * (100vw - 100%) / 2); background: #0A0A0A;">

    {{-- Foto de fundo --}}
    <div style="position: absolute; inset: 0;">
        <img src="{{ asset('images/Ambiente.png') }}" alt="Lima's Studio Barber"
             style="width: 100%; height: 100%; object-fit: cover; object-position: center; filter: brightness(1.05);">
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.85) 100%);"></div>
    </div>

    {{-- Fade inferior --}}
    <div style="position: absolute; inset: 0; z-index: 5; pointer-events: none; background: linear-gradient(to bottom, transparent 0%, transparent 20%, #0A0A0A 100%);"></div>

    {{-- Espaçador navbar --}}
    <div style="position: relative; z-index: 10; height: 76px; flex-shrink: 0;"></div>

    {{-- Conteúdo central --}}
    <div style="position: relative; z-index: 10; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 24px; margin-top: -8vh;">

        {{-- DESDE 1994 --}}
        <div class="hero-desde" style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
            <span style="display: block; width: 40px; height: 1px; background: #C9A84C;"></span>
            <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; letter-spacing: 0.3em; color: #C9A84C; text-transform: uppercase;">Desde 1994</span>
            <span style="display: block; width: 40px; height: 1px; background: #C9A84C;"></span>
        </div>

        {{-- Título --}}
        <h1 class="hero-title">Seu Estilo Começa Aqui</h1>

        {{-- Subtítulo --}}
        <p class="hero-subtitle" style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 14px; color: rgba(245,240,232,0.8); margin-bottom: 40px; text-align: center;">
            Corte, barba e experiência de alto nível.
        </p>

        {{-- Botões --}}
        <div class="hero-buttons">
            <a href="{{ route('agendar') }}" class="hero-btn-primary">Agendar agora</a>
            <a href="#servicos" class="hero-btn-secondary">Ver serviços</a>
        </div>

    </div>

    {{-- Mouse scroll (só desktop) --}}
    <div class="hero-mouse" style="position: absolute; z-index: 10; bottom: 52px; left: 50%; animation: mousePulse 1.8s ease-in-out infinite;">
        <svg width="24" height="38" viewBox="0 0 24 38" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="1" y="1" width="22" height="36" rx="11" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
            <rect x="11" y="7" width="2" height="7" rx="1" fill="rgba(255,255,255,0.6)" style="animation: mouseWheel 1.8s ease-in-out infinite;"/>
        </svg>
    </div>

    {{-- Barra inferior — sempre fixada no bottom --}}
    <div class="hero-info-bar" style="position: relative; z-index: 10; flex-shrink: 0; padding: 12px 24px; display: flex; align-items: center; justify-content: flex-end; gap: 16px;">
        <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; color: rgba(245,240,232,0.55);">✦ +30 anos de experiência</span>
        <span class="separador" style="color: rgba(255,255,255,0.15);">|</span>
        <span style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 11px; color: rgba(245,240,232,0.55);">✦ Cascavel, CE</span>
    </div>

</section>
