<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "Lima's Studio Barber")</title>
    <meta name="description" content="@yield('description', "Barbearia tradicional em Cascavel-CE. Corte de cabelo, barba, combo e corte infantil. Agende seu horário online e pague no dia ou pelo site.")">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Fontes carregadas sem travar a primeira pintura da página (o visual final é o mesmo) --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;600&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet"></noscript>
    @stack('styles')
</head>
<body class="bg-[var(--color-preto)] text-[var(--color-branco)] font-[var(--font-body)]">

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
