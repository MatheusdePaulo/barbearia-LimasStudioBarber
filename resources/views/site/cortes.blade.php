@extends('layouts.app')

@section('title', "Nossos Cortes | Lima's Studio Barber")

@php
    // Tipos de corte. "foco" é o object-position da foto (qual parte aparece quando ela é cortada pra caber no quadro);
    // "rosto" é onde fica o centro do rosto dentro do quadro (x% y%), pro destaque de luz;
    // "flip" espelha a foto. As fotos são as dos clientes que já estão no site.
    $secoes = [
        [
            'id'      => 'cabelo',
            'label'   => 'Cabelo',
            'titulo'  => 'Cortes de Cabelo',
            'servico' => 'cabelo',
            'itens'   => [
                ['nome' => 'Corte na Tesoura', 'rosto' => '61% 42%', 'foto' => 'images/barbeiro-trabalhando2.jpg', 'foco' => 'center 35%',
                 'texto' => 'Feito todo na tesoura, mecha por mecha, sem máquina. Deixa o cabelo com caimento natural e textura, ideal pra quem gosta de um visual mais comprido e bem acabado.'],
                ['nome' => 'Degradê', 'rosto' => '44% 44%', 'foto' => 'images/fulano6-depois.jpg', 'foco' => '60% center',
                 'texto' => 'As laterais vão do quase zero ao comprimento do topo numa transição suave, sem marcas. Pode ser baixo, médio ou alto, de acordo com o seu estilo.'],
                ['nome' => 'Corte Social', 'rosto' => '46% 45%', 'foto' => 'images/fulano4-depois.jpg', 'foco' => 'center 45%',
                 'texto' => 'Clássico e discreto: laterais curtas, topo um pouco mais cheio e acabamento alinhado. Funciona no trabalho, em eventos e no dia a dia.'],
                ['nome' => 'Corte Italiano', 'rosto' => '47% 45%', 'foto' => 'images/fulano5-depois.jpg', 'foco' => '55% center', 'flip' => true,
                 'texto' => 'Volume no topo penteado para trás, com movimento e acabamento natural nas laterais. Elegância de estilo clássico que nunca sai de moda.'],
                ['nome' => 'Risca Lateral', 'rosto' => '40% 46%', 'foto' => 'images/fulano3-depois.jpg', 'foco' => 'center 40%',
                 'texto' => 'Uma risca marcada na navalha separa o penteado, combinada com degradê nas laterais. Acabamento preciso que dá personalidade ao corte.'],
                ['nome' => 'Topete', 'rosto' => '45% 52%', 'foto' => 'images/fulano2-depois.jpg', 'foco' => 'center 60%',
                 'texto' => 'Topo com volume penteado pra cima e laterais curtas. Valoriza o rosto e fica ótimo com pomada ou cera.'],
            ],
        ],
        [
            'id'      => 'barba',
            'label'   => 'Barba',
            'titulo'  => 'Estilos de Barba',
            'servico' => 'barba',
            'itens'   => [
                ['nome' => 'Barba Desenhada', 'rosto' => '44% 53%', 'foto' => 'images/fulano1-depois.jpg', 'foco' => 'center 50%',
                 'texto' => 'Contornos marcados na navalha nas bochechas e no pescoço, com a barba aparada no volume certo. Visual alinhado e imponente.'],
                ['nome' => 'Barba Cheia', 'rosto' => '41% 47%', 'foto' => 'images/fulano6-depois.jpg', 'foco' => '60% center',
                 'texto' => 'Pra quem deixa crescer: aparamos o volume, tiramos as pontas e acertamos o desenho, mantendo a barba cheia e saudável.'],
                ['nome' => 'Cavanhaque', 'rosto' => '46% 47%', 'foto' => 'images/fulano4-depois.jpg', 'foco' => 'center 60%',
                 'texto' => 'Bigode e queixo em destaque, com as laterais bem baixas. Um estilo marcante que muda a expressão do rosto.'],
                ['nome' => 'Barba Rente', 'rosto' => '36% 41%', 'foto' => 'images/fulano3-depois.jpg', 'foco' => 'center 55%',
                 'texto' => 'Barba curta e uniforme, feita na máquina com acabamento na navalha. Prática pra quem quer um visual limpo sem tirar tudo.'],
            ],
        ],
        [
            'id'      => 'combo',
            'label'   => 'Combo',
            'titulo'  => 'Combo',
            'servico' => 'combo',
            'itens'   => [
                ['nome' => 'Cabelo + Barba', 'rosto' => '64% 26%', 'foto' => 'images/barbeiro-trabalhando.png', 'foco' => 'center 40%',
                 'texto' => 'O cuidado completo numa visita só: qualquer corte de cabelo junto com o estilo de barba que você escolher, pensados pra combinar entre si.'],
            ],
        ],
    ];
    $numero = 0;
@endphp

@push('styles')
    <style>
        /* ══════════ PÁGINA NOSSOS CORTES ══════════ */
        .cortes-page {
            background: #0A0A0A;
            padding: 150px 0 40px;
            overflow: hidden;
        }
        .cortes-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Topo */
        .cortes-head {
            text-align: center;
            margin-bottom: 40px;
        }
        .cortes-label-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 14px;
        }
        .cortes-label-row span.linha {
            display: block;
            width: 60px;
            height: 1px;
            background: #C9A84C;
            opacity: 0.6;
        }
        .cortes-label-row span.texto {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 11px;
            letter-spacing: 0.3em;
            color: #C9A84C;
            text-transform: uppercase;
        }
        .cortes-titulo {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 52px;
            color: #F5F0E8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 16px;
        }
        .cortes-sub {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.7;
            color: rgba(245,240,232,0.7);
            max-width: 560px;
            margin: 0 auto;
        }

        /* Atalhos Cabelo | Barba | Combo */
        .cortes-tabs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 32px;
            flex-wrap: wrap;
        }
        .cortes-tabs a {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #F5F0E8;
            text-decoration: none;
            padding: 11px 26px;
            border: 1px solid rgba(201,168,76,0.5);
            border-radius: 999px;
            transition: background 0.25s ease, color 0.25s ease;
        }
        .cortes-tabs a:hover {
            background: #C9A84C;
            color: #0A0A0A;
        }

        /* Cabeçalho de cada seção (Cabelo, Barba, Combo) */
        .cortes-secao {
            padding-top: 70px;
            /* compensa a navbar fixa ao pular pra #cabelo, #barba, #combo */
            scroll-margin-top: 60px;
        }
        .cortes-secao-titulo {
            display: flex;
            align-items: center;
            gap: 20px;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 36px;
            color: #C9A84C;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0 0 20px;
        }
        .cortes-secao-titulo::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, rgba(201,168,76,0.6), transparent);
        }

        /* Cada tipo de corte: foto de um lado e texto do outro, alternando */
        .corte-item {
            display: flex;
            align-items: center;
            gap: 48px;
            padding: 10px 0;
        }
        .corte-item--invertido {
            flex-direction: row-reverse;
        }
        /* Quadro em pé (4:5), igual às fotos, que são todas em retrato: assim quase não precisa cortar
           (num quadro deitado o cover cortava muito em cima e embaixo e dava zoom alto) */
        .corte-foto {
            position: relative;
            flex: 0 0 40%;
            aspect-ratio: 4 / 5;
        }
        /* Brilho dourado bem suave atrás da foto */
        .corte-foto::before {
            content: '';
            position: absolute;
            inset: 10%;
            background: radial-gradient(ellipse at center, rgba(201,168,76,0.14) 0%, transparent 70%);
            pointer-events: none;
        }
        /* Duas camadas da mesma foto:
           - base: a foto inteira bem transparente, com as bordas sumindo no preto (se mistura com o site);
           - rosto: a mesma foto nítida, visível só numa elipse em volta do rosto (--rosto = centro do rosto),
             que vai se misturando com a base. Dá o efeito de holofote no rosto.
           A máscara fica no wrapper e o espelhamento (flip) só na <img>, senão a máscara espelha junto. */
        .corte-foto-camada {
            position: absolute;
            inset: 0;
        }
        .corte-foto-camada img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(0.9);
        }
        .corte-foto-base {
            opacity: 0.4;
            -webkit-mask-image:
                linear-gradient(to right, transparent 0%, #000 18%, #000 82%, transparent 100%),
                linear-gradient(to bottom, transparent 0%, #000 15%, #000 85%, transparent 100%);
            -webkit-mask-composite: source-in;
            mask-image:
                linear-gradient(to right, transparent 0%, #000 18%, #000 82%, transparent 100%),
                linear-gradient(to bottom, transparent 0%, #000 15%, #000 85%, transparent 100%);
            mask-composite: intersect;
        }
        .corte-foto-rosto {
            opacity: 0.95;
            -webkit-mask-image: radial-gradient(ellipse 34% 28% at var(--rosto), #000 30%, rgba(0,0,0,0.6) 60%, transparent 100%);
            mask-image: radial-gradient(ellipse 34% 28% at var(--rosto), #000 30%, rgba(0,0,0,0.6) 60%, transparent 100%);
        }
        .corte-texto {
            flex: 1;
            max-width: 440px;
        }
        .corte-num {
            display: block;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 18px;
            color: rgba(201,168,76,0.55);
            letter-spacing: 0.2em;
            margin-bottom: 8px;
        }
        .corte-nome {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 40px;
            line-height: 1.1;
            color: #F5F0E8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 0 0 16px;
        }
        .corte-desc {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(245,240,232,0.75);
            margin: 0 0 24px;
        }
        .corte-agendar {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #C9A84C;
            text-decoration: none;
            border-bottom: 1px solid rgba(201,168,76,0.4);
            padding-bottom: 4px;
            transition: gap 0.25s ease, border-color 0.25s ease;
        }
        .corte-agendar:hover {
            gap: 16px;
            border-color: #C9A84C;
        }

        /* Tablet */
        @media (max-width: 1024px) {
            .cortes-titulo { font-size: 44px; }
            .corte-item { gap: 32px; }
            .corte-nome { font-size: 34px; }
        }

        /* Mobile: foto em cima, texto embaixo (sempre na mesma ordem, sem alternar) */
        @media (max-width: 767px) {
            .cortes-page { padding-top: 120px; }
            .cortes-head { text-align: left; }
            .cortes-label-row { justify-content: flex-start; }
            .cortes-titulo { font-size: 36px; }
            .cortes-sub { font-size: 14px; margin: 0; }
            .cortes-tabs { justify-content: flex-start; }
            .cortes-tabs a { padding: 10px 20px; }
            .cortes-secao { padding-top: 48px; }
            .cortes-secao-titulo { font-size: 28px; }

            .corte-item,
            .corte-item--invertido {
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 0 0 36px;
            }
            .corte-foto {
                flex: none;
                width: 100%;
            }
            .corte-texto { max-width: none; margin-top: -30px; position: relative; }
            .corte-nome { font-size: 30px; }
            .corte-desc { font-size: 14px; }
        }
    </style>
@endpush

@section('content')
    @include('site.partials.navbar')

    <div class="cortes-page">
        <div class="cortes-wrap">

            <header class="cortes-head">
                <div class="cortes-label-row">
                    <span class="linha"></span>
                    <span class="texto">Lima's Studio Barber</span>
                    <span class="linha"></span>
                </div>
                <h1 class="cortes-titulo">Nossos Cortes</h1>
                <p class="cortes-sub">Conheça os estilos de cabelo e barba que fazemos. Escolha o seu e agende um horário.</p>
                <nav class="cortes-tabs">
                    @foreach($secoes as $secao)
                        <a href="#{{ $secao['id'] }}">{{ $secao['label'] }}</a>
                    @endforeach
                </nav>
            </header>

            @foreach($secoes as $secao)
                <section id="{{ $secao['id'] }}" class="cortes-secao">
                    <h2 class="cortes-secao-titulo">{{ $secao['titulo'] }}</h2>

                    @foreach($secao['itens'] as $item)
                        @php $numero++; @endphp
                        <article class="corte-item {{ $numero % 2 === 0 ? 'corte-item--invertido' : '' }}">
                            <div class="corte-foto" style="--rosto: {{ $item['rosto'] }};">
                                @foreach(['base', 'rosto'] as $camada)
                                    <div class="corte-foto-camada corte-foto-{{ $camada }}">
                                        <img src="{{ asset($item['foto']) }}" alt="{{ $camada === 'rosto' ? $item['nome'] : '' }}" loading="lazy"
                                             style="object-position: {{ $item['foco'] }};{{ !empty($item['flip']) ? ' transform: scaleX(-1);' : '' }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="corte-texto">
                                <span class="corte-num">{{ str_pad($numero, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="corte-nome">{{ $item['nome'] }}</h3>
                                <p class="corte-desc">{{ $item['texto'] }}</p>
                                <a href="{{ route('appointments.create', $secao['servico']) }}" class="corte-agendar">
                                    Agendar {{ $secao['label'] === 'Combo' ? 'combo' : strtolower($secao['label']) }} <span>→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endforeach

        </div>
    </div>

    @include('site.partials.footer')
@endsection
