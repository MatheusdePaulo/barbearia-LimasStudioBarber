<style>
    html { scroll-behavior: smooth; }
    .nav-link { font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 15px; color: #F5F0E8; text-decoration: none; transition: color 0.3s ease; letter-spacing: 0.02em; }
    .nav-link:hover { color: #C9A84C; }

    /* Desktop: links visíveis, hambúrguer escondido */
    .nav-desktop { display: flex; }
    .nav-hamburger { display: none; }

    /* Tablet e Mobile (≤1024px) */
    @media (max-width: 1024px) {
        .nav-desktop   { display: none !important; }
        .nav-hamburger { display: flex !important; align-items: center; justify-content: center; }
    }

    /* Drawer mobile — mesmo formato do menu do Nathan do Corte: painel lateral que entra pela direita
       sobre um fundo escurecido, com logo, links com ícone, card do Wi-Fi e Instagram/WhatsApp */
    [x-cloak] { display: none !important; }
    .nav-overlay {
        position: fixed;
        inset: 0;
        z-index: 110;
        background: rgba(0,0,0,0.6);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .nav-overlay.open { opacity: 1; visibility: visible; }
    .nav-drawer {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        z-index: 120;
        width: 85%;
        max-width: 450px;
        background: #141414;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        padding: 32px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.3s ease-out;
    }
    .nav-drawer.open { transform: translateX(0); transition-timing-function: ease-in; }
    .nav-drawer-link {
        display: flex;
        align-items: center;
        gap: 16px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 300;
        font-size: 14px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #F5F0E8;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .nav-drawer-link:hover { color: #C9A84C; }
    .nav-drawer-link svg { width: 32px; flex-shrink: 0; color: #C9A84C; }
    .nav-social { display: block; width: 48px; height: 48px; transition: transform 0.3s ease; }
    .nav-social:hover { transform: scale(1.1); }
    .nav-social img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; box-shadow: 0 10px 15px rgba(0,0,0,0.3); }
    @media (min-width: 768px) {
        .nav-drawer { padding: 48px; }
        .nav-drawer-link { font-size: 18px; }
    }

    /* Mobile: logo só aparece quando o menu abre, navbar mais baixa e hambúrguer maior */
    @media (max-width: 640px) {
        #navbar { height: 68px !important; }
        .nav-logo { display: none; }
        #nav-icon-menu, #nav-icon-close { width: 28.6px; height: 28.6px; }

        /* Ao rolar: em vez da pílula quase da largura da tela (só com o hambúrguer dentro),
           vira uma bolinha só em volta do hambúrguer, no canto direito. */
        #navbar.nav-scrolled {
            left: auto !important;
            right: 20px !important;
            transform: none !important;
            width: 58.8px !important;
            height: 58.8px !important;
            padding: 0 !important;
            justify-content: center !important;
        }
    }
</style>

<nav id="navbar"
     x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 })"
     :class="{ 'nav-scrolled': scrolled }"
     style="position: fixed; top: 0; left: 0; right: 0; z-index: 100; height: 76px; background: #0A0A0A; border-bottom: 1px solid rgba(201,168,76,0.2); display: flex; align-items: center; justify-content: space-between; padding: 0 48px; transition: all 0.35s cubic-bezier(0.4,0,0.2,1);"
     :style="scrolled
        ? 'position: fixed; top: 14px; left: 50%; right: auto; transform: translateX(-50%); width: min(1080px, calc(100% - 48px)); height: 68px; background: #0A0A0A; border: 1px solid rgba(201,168,76,0.35); border-radius: 999px; box-shadow: 0 12px 34px rgba(0,0,0,0.45); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; z-index: 100; transition: all 0.35s cubic-bezier(0.4,0,0.2,1);'
        : 'position: fixed; top: 0; left: 0; right: 0; transform: none; width: 100%; height: 76px; background: #0A0A0A; border: none; border-bottom: 1px solid rgba(201,168,76,0.2); border-radius: 0; box-shadow: none; display: flex; align-items: center; justify-content: space-between; padding: 0 48px; z-index: 100; transition: all 0.35s cubic-bezier(0.4,0,0.2,1);'">

    {{-- LOGO --}}
    <a href="{{ route('home') }}" class="nav-logo" style="flex-shrink: 0;">
        <img src="{{ asset('images/Logo-Limas.png') }}"
             alt="Lima's Studio Barber"
             style="height: 46px; width: auto; object-fit: contain; transition: height 0.35s ease;"
             :style="scrolled
                ? 'height: 42px; width: auto; object-fit: contain; transition: height 0.35s ease;'
                : 'height: 46px; width: auto; object-fit: contain; transition: height 0.35s ease;'">
    </a>

    {{-- LINKS + CTA — só desktop --}}
    <div class="nav-desktop" style="align-items: center; gap: 140px; transition: gap 0.35s ease;"
         :style="scrolled
            ? 'display: flex; align-items: center; gap: 36px; transition: gap 0.35s ease;'
            : 'display: flex; align-items: center; gap: 140px; transition: gap 0.35s ease;'">

        <div style="display: flex; align-items: center; gap: 28px; transition: gap 0.35s ease;"
             :style="scrolled
                ? 'display: flex; align-items: center; gap: 18px; transition: gap 0.35s ease;'
                : 'display: flex; align-items: center; gap: 28px; transition: gap 0.35s ease;'">
            <a href="#sobre"    class="nav-link">Sobre</a>
            <a href="#servicos" class="nav-link">Serviços</a>
            <a href="#produtos" class="nav-link">Produtos</a>
            <a href="#galeria"  class="nav-link">Galeria</a>
            <a href="#contato"  class="nav-link">Contato</a>
        </div>

        <a href="{{ route('appointments.create') }}"
           style="flex-shrink: 0; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; padding: 10px 22px; background: #C9A84C; color: #0A0A0A; border-radius: 6px; text-decoration: none; transition: all 0.3s ease;"
           onmouseover="this.style.background='#E2C97E'"
           onmouseout="this.style.background='#C9A84C'">
            Agendar agora
        </a>

    </div>

    {{-- HAMBÚRGUER — tablet + mobile --}}
    <button class="nav-hamburger" onclick="toggleNavDrawer()"
            style="background: none; border: none; cursor: pointer; padding: 8px;">
        <svg id="nav-icon-menu" width="26" height="26" fill="none" stroke="#F5F0E8" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="nav-icon-close" width="26" height="26" fill="none" stroke="#F5F0E8" stroke-width="1.5" viewBox="0 0 24 24" style="display: none;">
            <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

</nav>

{{-- DRAWER MOBILE (mesmo formato do menu do Nathan do Corte) --}}
@php
    // Wi-Fi da barbearia: ainda não temos a rede do Limas. Preenchendo os dois, o QR Code e o popup aparecem sozinhos.
    $wifiRede  = '';
    $wifiSenha = '';
    $wifiQr    = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='
               . rawurlencode('WIFI:S:' . $wifiRede . ';T:WPA;P:' . $wifiSenha . ';;');
@endphp
<div class="nav-overlay" id="nav-overlay" onclick="toggleNavDrawer()"></div>

<div class="nav-drawer" id="nav-drawer">

    {{-- Fechar + logo --}}
    <div style="position: relative; display: flex; flex-direction: column; align-items: center; margin-bottom: 40px;">
        <button onclick="toggleNavDrawer()" aria-label="Fechar menu"
                style="position: absolute; top: 0; right: 0; background: none; border: none; cursor: pointer; padding: 8px; color: #F5F0E8;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img src="{{ asset('images/logo-limas02.png') }}" alt="Lima's Studio Barber"
             style="margin-top: 24px; width: 176px; height: auto; object-fit: contain;">
    </div>

    {{-- Links do menu (os mesmos do nosso site) --}}
    <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 24px;">
            <li><a href="#servicos" class="nav-drawer-link" onclick="toggleNavDrawer()"><svg viewBox="0 0 24 24" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4L8.12 15.88M14.47 14.48L20 20M8.12 8.12L12 12"/></svg> Serviços</a></li>
            <li><a href="#sobre" class="nav-drawer-link" onclick="toggleNavDrawer()"><svg viewBox="0 0 24 24" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg> Sobre</a></li>
            <li><a href="#produtos" class="nav-drawer-link" onclick="toggleNavDrawer()"><svg viewBox="0 0 24 24" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 01-8 0"/></svg> Produtos</a></li>
            <li><a href="#galeria" class="nav-drawer-link" onclick="toggleNavDrawer()"><svg viewBox="0 0 24 24" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg> Galeria</a></li>
            <li><a href="#blog" class="nav-drawer-link" onclick="toggleNavDrawer()"><svg viewBox="0 0 24 24" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h12a2 2 0 012 2v14H6a2 2 0 01-2-2V4z"/><path d="M18 8h2v10a2 2 0 01-2 2M8 8h6M8 12h6M8 16h4"/></svg> Blog</a></li>
            <li><a href="#contato" class="nav-drawer-link" onclick="toggleNavDrawer()"><svg viewBox="0 0 24 24" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg> Contato</a></li>
    </ul>

    <a href="{{ route('appointments.create') }}"
       style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; padding: 16px 32px; background: #C9A84C; color: #0A0A0A; border-radius: 8px; text-decoration: none; text-align: center; margin-top: 32px;">
        Agendar agora
    </a>

    <hr style="margin: 32px 0; border: none; border-top: 1px solid rgba(255,255,255,0.1);">

    {{-- Wi-Fi + Instagram/WhatsApp --}}
    <div x-data="{ wifiOpen: false, copied: false }"
         style="position: relative; display: flex; flex-direction: column; align-items: center; background: #1A1A1A; padding: 24px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); margin-top: auto;">
        <p style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 9px; letter-spacing: 0.3em; text-transform: uppercase; color: #C9A84C; margin: 0 0 12px; text-align: center;">Wi-Fi da barbearia</p>

        @if($wifiRede && $wifiSenha)
            <div @click="wifiOpen = true" style="background: #FFFFFF; padding: 12px; border-radius: 12px; cursor: pointer; box-shadow: 0 10px 15px rgba(0,0,0,0.3);">
                <img src="{{ $wifiQr }}" alt="QR Code Wi-Fi" style="width: 112px; height: 112px; display: block;">
            </div>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 9px; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(245,240,232,0.4); margin: 12px 0 0; text-align: center;">
                Toque no QR Code para ver a senha
            </p>

            {{-- Popup com rede e senha --}}
            <div x-show="wifiOpen" x-cloak x-transition.opacity @click.away="wifiOpen = false; copied = false"
                 style="position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 12px; width: 256px; background: #141414; border: 1px solid #3F3F46; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); padding: 20px; z-index: 5;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <p style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #C9A84C; margin: 0;">Wi-Fi da barbearia</p>
                    <button @click="wifiOpen = false; copied = false" aria-label="Fechar" style="background: none; border: none; cursor: pointer; color: #71717A; padding: 0;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="background: #18181B; border-radius: 12px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="font-family: 'Montserrat', sans-serif; font-size: 9px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: #71717A; margin: 0;">Rede</p>
                            <p style="font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 600; color: #F5F0E8; margin: 0;">{{ $wifiRede }}</p>
                        </div>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#C9A84C" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0114.08 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0"/><circle cx="12" cy="20" r="1" fill="#C9A84C"/></svg>
                    </div>
                    <div style="background: #18181B; border-radius: 12px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                        <div>
                            <p style="font-family: 'Montserrat', sans-serif; font-size: 9px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: #71717A; margin: 0;">Senha</p>
                            <p style="font-family: monospace; font-size: 14px; font-weight: 700; color: #F5F0E8; margin: 0;">{{ $wifiSenha }}</p>
                        </div>
                        <button @click="navigator.clipboard.writeText(@js($wifiSenha)); copied = true; setTimeout(() => copied = false, 2000)"
                                :style="copied ? 'background: rgba(34,197,94,0.2); color: #4ADE80;' : 'background: #27272A; color: #C9A84C;'"
                                style="flex-shrink: 0; border: none; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 9px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;">
                            <span x-text="copied ? 'Copiado!' : 'Copiar'"></span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Sem rede cadastrada ainda: espaço do QR fica em branco --}}
            <div style="background: #FFFFFF; padding: 12px; border-radius: 12px; box-shadow: 0 10px 15px rgba(0,0,0,0.3);">
                <div style="width: 112px; height: 112px;"></div>
            </div>
        @endif

        <div style="display: flex; align-items: center; justify-content: center; gap: 32px; margin-top: 32px; width: 100%;">
            <a href="https://www.instagram.com/limasstudiobarber/" target="_blank" rel="noopener" class="nav-social">
                <img src="{{ asset('images/insta.webp') }}" alt="Instagram">
            </a>
            <a href="https://wa.me/5585985430142" target="_blank" rel="noopener" class="nav-social">
                <img src="{{ asset('images/zap.webp') }}" alt="WhatsApp">
            </a>
        </div>
    </div>
</div>

<script>
    function toggleNavDrawer() {
        const open = !document.getElementById('nav-drawer').classList.contains('open');
        document.getElementById('nav-drawer').classList.toggle('open', open);
        document.getElementById('nav-overlay').classList.toggle('open', open);
    }
</script>
