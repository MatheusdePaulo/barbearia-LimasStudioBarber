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

    /* Drawer mobile */
    .nav-drawer {
        position: fixed;
        top: 72px;
        left: 0;
        right: 0;
        z-index: 99;
        background: #0A0A0A;
        border-bottom: 1px solid rgba(201,168,76,0.2);
        padding: 28px 32px;
        flex-direction: column;
        gap: 20px;
        display: none;
    }
    .nav-drawer.open { display: flex; }

    /* Mobile: logo só aparece quando o menu abre, navbar mais baixa e hambúrguer maior */
    @media (max-width: 640px) {
        #navbar { height: 68px !important; }
        .nav-logo { display: none; }
        #navbar.drawer-open .nav-logo { display: flex; }
        #nav-icon-menu, #nav-icon-close { width: 28.6px; height: 28.6px; }
    }
</style>

<nav id="navbar"
     x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 })"
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

        <a href="{{ route('agendar') }}"
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

{{-- DRAWER MOBILE --}}
<div class="nav-drawer" id="nav-drawer">
    <a href="#sobre"    class="nav-link" onclick="toggleNavDrawer()">Sobre</a>
    <a href="#servicos" class="nav-link" onclick="toggleNavDrawer()">Serviços</a>
    <a href="#produtos" class="nav-link" onclick="toggleNavDrawer()">Produtos</a>
    <a href="#galeria"  class="nav-link" onclick="toggleNavDrawer()">Galeria</a>
    <a href="#contato"  class="nav-link" onclick="toggleNavDrawer()">Contato</a>
    <a href="{{ route('agendar') }}"
       style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; padding: 16px 32px; background: #C9A84C; color: #0A0A0A; border-radius: 8px; text-decoration: none; text-align: center; margin-top: 8px;">
        Agendar agora
    </a>
</div>

<script>
    function toggleNavDrawer() {
        const navbar    = document.getElementById('navbar');
        const drawer    = document.getElementById('nav-drawer');
        const iconMenu  = document.getElementById('nav-icon-menu');
        const iconClose = document.getElementById('nav-icon-close');
        const isOpen = drawer.classList.contains('open');
        if (isOpen) {
            drawer.classList.remove('open');
            navbar.classList.remove('drawer-open');
            iconMenu.style.display  = 'block';
            iconClose.style.display = 'none';
        } else {
            drawer.classList.add('open');
            navbar.classList.add('drawer-open');
            iconMenu.style.display  = 'none';
            iconClose.style.display = 'block';
        }
    }
    // Fecha ao clicar fora
    document.addEventListener('click', function(e) {
        const navbar = document.getElementById('navbar');
        const drawer = document.getElementById('nav-drawer');
        if (!navbar.contains(e.target) && !drawer.contains(e.target)) {
            drawer.classList.remove('open');
            navbar.classList.remove('drawer-open');
            document.getElementById('nav-icon-menu').style.display  = 'block';
            document.getElementById('nav-icon-close').style.display = 'none';
        }
    });
</script>
