<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="QuizzSense Países: explora los países del mundo, un continente a la vez, con calma y a tu propio ritmo.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QuizzSense · Países del mundo</title>
    @vite(['resources/css/quizzsense/quizzsense.css', 'resources/js/paises/paises.js'])
</head>
<body class="min-h-screen antialiased">

    {{-- Depuración temporal: mostrar errores de JS en pantalla --}}
    <div id="js-error-panel" style="display:none;position:fixed;top:0;left:0;right:0;z-index:9999;background:#8b1538;color:#fff;padding:1rem;font-family:monospace;white-space:pre-wrap;max-height:50vh;overflow:auto;"></div>
    <script>
        window.addEventListener('error', function(e) {
            var panel = document.getElementById('js-error-panel');
            if (panel) {
                panel.style.display = 'block';
                panel.textContent += 'ERROR: ' + e.message + '\n' + (e.filename || '') + ':' + (e.lineno || '') + '\n\n';
            }
        });
        window.addEventListener('unhandledrejection', function(e) {
            var panel = document.getElementById('js-error-panel');
            if (panel) {
                panel.style.display = 'block';
                panel.textContent += 'PROMISE REJECTION: ' + (e.reason && e.reason.message ? e.reason.message : e.reason) + '\n\n';
            }
        });
    </script>

    {{-- Símbolo SVG: estrella de progreso --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <symbol id="star" viewBox="0 0 24 24">
            <path d="M12 2.5l2.9 5.9 6.5.9-4.7 4.6 1.1 6.5L12 17.4l-5.8 3 1.1-6.5L2.6 9.3l6.5-.9z" fill="currentColor"/>
        </symbol>
    </svg>

    {{-- Fondo generado por canvas + viñeta + grano --}}
    <canvas id="sky-canvas" class="sky-canvas" aria-hidden="true"></canvas>
    <div class="vignette-overlay" aria-hidden="true"></div>
    <div class="noise-overlay" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-3xl flex-col px-5 py-8 sm:px-8">

        {{-- Cabecera --}}
        <header class="mb-8 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/mascota.png') }}" alt="Mascota de QuizzSense" class="h-12 w-auto drop-shadow-[0_6px_12px_rgba(0,0,0,0.5)]">
                <div>
                    <p class="text-xl font-bold tracking-wide text-gold-300">QuizzSense</p>
                    <p class="text-sm text-mist-200/80">Países del mundo</p>
                </div>
            </div>
            <a href="{{ route('activities.youth') }}" class="btn-ghost !px-4 !py-2 text-sm">&larr; Volver a actividades</a>
        </header>

        <main class="flex flex-1 flex-col">

            {{-- PANTALLA: INICIO --}}
            <section class="screen is-active" id="screen-home" data-screen="home" aria-labelledby="home-title">
                <div class="relative mb-8 flex justify-center">
                    <img src="{{ asset('img/mascota.png') }}" alt="Mascota de QuizzSense" class="float-gentle h-48 w-auto drop-shadow-[0_20px_32px_rgba(0,0,0,0.55)] sm:h-56">
                </div>

                <div class="glass-card p-8 text-center sm:p-10">
                    <h1 id="home-title" class="mb-3 text-3xl font-bold text-mist-100 sm:text-4xl">
                        Tu aventura geográfica te espera
                    </h1>
                    <p class="mx-auto mb-8 max-w-md text-lg leading-relaxed text-mist-200/90">
                        Explora los países del mundo, un continente a la vez.
                        Sin prisa y sin presión: tú marcas el ritmo.
                    </p>

                    <button type="button" class="btn-gold" id="btn-start">
                        Comenzar a explorar
                    </button>
                    <p class="mt-5 text-sm text-mist-200/70">
                        5 continentes &middot; sin temporizador &middot; puedes salir cuando quieras
                    </p>

                    <div class="mt-8">
                        <button type="button" class="btn-ghost" id="btn-howto">
                            ¿Cómo funciona?
                        </button>
                    </div>
                </div>
            </section>

            {{-- PANTALLA: CÓMO FUNCIONA --}}
            <section class="screen" id="screen-howto" data-screen="howto" aria-labelledby="howto-title">
                <div class="glass-card p-8 sm:p-10">
                    <h1 id="howto-title" class="mb-8 text-center text-3xl font-bold text-mist-100">¿Cómo funciona?</h1>

                    <ol class="mx-auto flex max-w-lg flex-col gap-6">
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">1</span>
                            <div>
                                <p class="font-semibold text-gold-300">Elige un continente</p>
                                <p class="leading-relaxed text-mist-200/90">Tienes cinco continentes para explorar: América, Europa, Asia, África y Oceanía. Cada uno con sus países.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">2</span>
                            <div>
                                <p class="font-semibold text-gold-300">Lee el nombre del país</p>
                                <p class="leading-relaxed text-mist-200/90">Aparecerá el nombre de un país en grande. Tu misión es encontrarlo en el mapa del continente.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">3</span>
                            <div>
                                <p class="font-semibold text-gold-300">Haz clic en el mapa</p>
                                <p class="leading-relaxed text-mist-200/90">Pasa el ratón por los países para verlos en verde y haz clic cuando creas que es el correcto.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">4</span>
                            <div>
                                <p class="font-semibold text-gold-300">Aprende con calma</p>
                                <p class="leading-relaxed text-mist-200/90">Si no aciertas, no pasa nada: te mostramos cuál era y sigues adelante. Cada intento te acerca más al mundo.</p>
                            </div>
                        </li>
                    </ol>

                    <div class="mt-10 text-center">
                        <button type="button" class="btn-ghost" id="btn-howto-back">&larr; Volver al inicio</button>
                    </div>
                </div>
            </section>

            {{-- PANTALLA: SELECCIÓN DE CONTINENTE --}}
            <section class="screen" id="screen-continents" data-screen="continents" aria-labelledby="continents-title">
                <div class="glass-card p-8 sm:p-10">
                    <h1 id="continents-title" class="mb-3 text-center text-3xl font-bold text-mist-100">Elige un continente</h1>
                    <p class="mx-auto mb-8 max-w-md text-center text-mist-200/90">
                        Cada continente tiene sus países. Empieza por el que más te llame la atención.
                    </p>

                    <div id="continent-grid" class="grid grid-cols-1 gap-4 sm:grid-cols-2"></div>

                    <div class="mt-8 text-center">
                        <button type="button" class="btn-ghost" id="btn-continents-back">&larr; Volver al inicio</button>
                    </div>
                </div>
            </section>

            {{-- PANTALLA: JUEGO --}}
            <section class="screen" id="screen-game" data-screen="game" aria-labelledby="game-heading">
                <h1 id="game-heading" class="sr-only">Encuentra el país en el mapa</h1>

                <div class="mb-5 flex items-center justify-between gap-4">
                    <button type="button" class="btn-ghost !px-4 !py-2 text-sm" id="btn-exit">
                        &larr; Salir
                    </button>
                    <p class="text-sm font-semibold tracking-wide text-mist-200">
                        <span id="progress-label">País 1 de ?</span>
                    </p>
                </div>

                <div class="mb-2 flex items-center gap-3">
                    <div class="progress-track flex-1" role="progressbar" aria-label="Progreso del continente" aria-valuemin="0" aria-valuemax="10" aria-valuenow="1" id="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                </div>

                <div class="question-frame mb-6">
                    <div class="question-body text-center">
                        <p class="category-chip mb-4" id="continent-chip"></p>
                        <p class="question-text" id="country-name" aria-live="polite"></p>
                    </div>
                </div>

                <div class="map-container mb-6">
                    <svg id="map-svg" class="map-svg" preserveAspectRatio="xMidYMid meet" role="group" aria-label="Mapa del continente"></svg>
                </div>

                <div id="feedback-wrap" class="mt-6 hidden" aria-live="polite">
                    <div class="feedback-panel" id="feedback-panel">
                        <p class="feedback-title" id="feedback-title"></p>
                        <p class="text-mist-100/95" id="feedback-text"></p>
                        <div class="mt-5 text-right">
                            <button type="button" class="btn-gold !py-3 !text-base" id="btn-next">Siguiente &rarr;</button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- PANTALLA: RESULTADOS --}}
            <section class="screen" id="screen-results" data-screen="results" aria-labelledby="results-title">
                <div class="relative mb-6 flex justify-center">
                    <img src="{{ asset('img/mascota.png') }}" alt="" class="float-gentle h-28 w-auto drop-shadow-[0_16px_26px_rgba(0,0,0,0.55)]">
                </div>

                <div class="glass-card p-8 text-center sm:p-10">
                    <h1 id="results-title" class="mb-2 text-3xl font-bold text-mist-100">Resultados</h1>

                    <p class="mb-6 text-5xl font-bold text-gold-300">
                        <span id="results-score">0</span><span class="text-2xl text-mist-200/80"> de </span><span class="text-2xl text-mist-200/80" id="results-total">10</span>
                    </p>
                    <p class="mx-auto mb-8 max-w-md text-lg leading-relaxed text-mist-200/95" id="results-message"></p>

                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <button type="button" class="btn-gold" id="btn-other-continent">Elegir otro continente</button>
                        <button type="button" class="btn-ghost" id="btn-home">Volver al inicio</button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
