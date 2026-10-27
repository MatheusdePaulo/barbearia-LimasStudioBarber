<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "Lima's Studio Barber")</title>
    <meta name="description" content="@yield('description', "Barbearia tradicional em Cascavel-CE. Corte de cabelo, barba, combo e corte infantil. Agende seu horário online e pague no dia ou pelo site.")">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Fontes hospedadas no próprio site (os mesmos arquivos do Google Fonts, subconjunto latin).
         Sem a ida ao Google, o título do topo aparece na fonte certa bem mais cedo no celular. --}}
    <link rel="preload" href="{{ asset('fonts/cormorant-garamond-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/montserrat-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <style>
        @font-face { font-family: 'Cormorant Garamond'; font-style: normal; font-weight: 400; font-display: swap; src: url('{{ asset('fonts/cormorant-garamond-latin.woff2') }}') format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        @font-face { font-family: 'Cormorant Garamond'; font-style: normal; font-weight: 600; font-display: swap; src: url('{{ asset('fonts/cormorant-garamond-latin.woff2') }}') format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        @font-face { font-family: 'Cormorant Garamond'; font-style: normal; font-weight: 700; font-display: swap; src: url('{{ asset('fonts/cormorant-garamond-latin.woff2') }}') format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        @font-face { font-family: 'Montserrat'; font-style: normal; font-weight: 300; font-display: swap; src: url('{{ asset('fonts/montserrat-latin.woff2') }}') format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        @font-face { font-family: 'Montserrat'; font-style: normal; font-weight: 400; font-display: swap; src: url('{{ asset('fonts/montserrat-latin.woff2') }}') format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
        @font-face { font-family: 'Montserrat'; font-style: normal; font-weight: 600; font-display: swap; src: url('{{ asset('fonts/montserrat-latin.woff2') }}') format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
    </style>
    @stack('styles')
</head>
<body class="bg-[var(--color-preto)] text-[var(--color-branco)] font-[var(--font-body)]">

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
