<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('QuizzSense: práctica diaria de habilidades sociales para adolescentes, con calma y a tu propio ritmo.') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('QuizzSense · Habilidades sociales') }}</title>
    <script>window.APP_LOCALE = "{{ app()->getLocale() }}";</script>
    @vite(['resources/css/quizzsense/quizzsense.css', 'resources/js/quizzsense/app.js'])
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
                <img src="{{ asset('img/mascota.png') }}" alt="{{ __('Mascota de QuizzSense') }}" class="h-12 w-auto drop-shadow-[0_6px_12px_rgba(0,0,0,0.5)]">
                <div>
                    <p class="text-xl font-bold tracking-wide text-gold-300">QuizzSense</p>
                    <p class="text-sm text-mist-200/80">{{ __('Habilidades sociales') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <p id="streak-badge" class="hidden rounded-full border border-gold-500/60 bg-night-800 px-4 py-2 text-sm font-semibold text-gold-300"></p>
                <a href="{{ route('activities.youth') }}" class="btn-ghost !px-4 !py-2 text-sm">&larr; {{ __('Volver a actividades') }}</a>
            </div>
        </header>

        <main class="flex flex-1 flex-col">

            {{-- PANTALLA: INICIO --}}
            <section class="screen is-active" id="screen-home" data-screen="home" aria-labelledby="home-title">
                <div class="relative mb-8 flex justify-center">
                    <img src="{{ asset('img/mascota.png') }}" alt="{{ __('Mascota de QuizzSense') }}" class="float-gentle h-48 w-auto drop-shadow-[0_20px_32px_rgba(0,0,0,0.55)] sm:h-56">
                </div>

                <div class="glass-card p-8 text-center sm:p-10">
                    <h1 id="home-title" class="mb-3 text-3xl font-bold text-mist-100 sm:text-4xl">
                        {{ __('Tu sesión de hoy te espera') }}
                    </h1>
                    <p class="mx-auto mb-8 max-w-md text-lg leading-relaxed text-mist-200/90">
                        {{ __('Diez situaciones cotidianas para practicar con calma. Sin prisa y sin exámenes: tú marcas el ritmo.') }}
                    </p>

                    <div id="home-available">
                        <button type="button" class="btn-gold" id="btn-start">
                            {{ __('Comenzar la sesión de hoy') }}
                        </button>
                        <p class="mt-5 text-sm text-mist-200/70">
                            {{ __('10 preguntas · sin temporizador · puedes salir cuando quieras') }}
                        </p>
                    </div>

                    <div id="home-completed" class="hidden">
                        <div class="info-card mx-auto max-w-md p-6">
                            <p class="mb-2 text-xl font-semibold text-mist-100">{{ __('Ya completaste tu sesión de hoy') }}</p>
                            <p class="text-mist-200/90" id="home-result-summary"></p>
                            <p class="mt-3 text-sm text-mist-200/70">{{ __('Vuelve mañana: habrá nuevas situaciones para practicar.') }}</p>
                        </div>
                        <div class="mt-6" id="home-repeat-wrap">
                            <button type="button" class="btn-ghost" id="btn-repeat-home">
                                {{ __('Repetir las preguntas de hoy') }}
                            </button>
                            <p class="mt-2 text-xs text-mist-200/60">{{ __('Modo práctica: el mismo conjunto de hoy; no se guarda ningún resultado.') }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="button" class="btn-ghost" id="btn-howto">
                            {{ __('¿Cómo funciona?') }}
                        </button>
                    </div>
                </div>
            </section>

            {{-- PANTALLA: CÓMO FUNCIONA --}}
            <section class="screen" id="screen-howto" data-screen="howto" aria-labelledby="howto-title">
                <div class="glass-card p-8 sm:p-10">
                    <h1 id="howto-title" class="mb-8 text-center text-3xl font-bold text-mist-100">{{ __('¿Cómo funciona?') }}</h1>

                    <ol class="mx-auto flex max-w-lg flex-col gap-6">
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">1</span>
                            <div>
                                <p class="font-semibold text-gold-300">{{ __('Lee la situación') }}</p>
                                <p class="leading-relaxed text-mist-200/90">{{ __('Cada día encontrarás 10 situaciones de la vida real: ruido, cambios de planes, bromas, cansancio social y más.') }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">2</span>
                            <div>
                                <p class="font-semibold text-gold-300">{{ __('Elige la reacción que te parezca mejor') }}</p>
                                <p class="leading-relaxed text-mist-200/90">{{ __('No hay temporizador ni trampas. Piensa con calma y elige una de las 4 opciones.') }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">3</span>
                            <div>
                                <p class="font-semibold text-gold-300">{{ __('Aprende con la explicación') }}</p>
                                <p class="leading-relaxed text-mist-200/90">{{ __('Después de responder verás por qué una opción suele funcionar mejor. Tú decides cuándo avanzar.') }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="step-number" aria-hidden="true">4</span>
                            <div>
                                <p class="font-semibold text-gold-300">{{ __('Sin presión') }}</p>
                                <p class="leading-relaxed text-mist-200/90">{{ __('Puedes salir en cualquier momento: tu progreso se guarda. Mañana habrá una sesión nueva.') }}</p>
                            </div>
                        </li>
                    </ol>

                    <div class="mt-10 text-center">
                        <button type="button" class="btn-ghost" id="btn-howto-back">&larr; {{ __('Volver al inicio') }}</button>
                    </div>
                </div>
            </section>

            {{-- PANTALLA: PREGUNTA --}}
            <section class="screen" id="screen-quiz" data-screen="quiz" aria-labelledby="quiz-heading">
                <h1 id="quiz-heading" class="sr-only">{{ __('Pregunta de la sesión diaria') }}</h1>

                <div class="mb-5 flex items-center justify-between gap-4">
                    <button type="button" class="btn-ghost !px-4 !py-2 text-sm" id="btn-exit">
                        &larr; {{ __('Salir') }}
                    </button>
                    <p class="text-sm font-semibold tracking-wide text-mist-200">
                        <span id="progress-label"></span>
                    </p>
                </div>

                <div class="mb-2 flex items-center gap-3">
                    <div class="progress-track flex-1" role="progressbar" aria-label="{{ __('Progreso de la sesión') }}" aria-valuemin="0" aria-valuemax="10" aria-valuenow="1" id="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                </div>
                <div class="mb-8 flex items-center justify-center gap-1.5" id="progress-stars" aria-hidden="true"></div>

                <p class="practice-banner hidden" id="practice-banner" role="status"></p>

                <div class="question-frame mb-8">
                    <div class="question-body">
                        <p class="category-chip mb-4" id="question-category"></p>
                        <p class="question-text" id="question-text" aria-live="polite"></p>
                        <div class="question-divider" aria-hidden="true"></div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2" id="options-grid" role="group" aria-label="{{ __('Opciones de respuesta') }}"></div>

                <div id="feedback-wrap" class="mt-8 hidden" aria-live="polite">
                    <div class="feedback-panel" id="feedback-panel">
                        <p class="feedback-title" id="feedback-title"></p>
                        <p class="text-mist-100/95" id="feedback-text"></p>
                        <div class="mt-5 text-right">
                            <button type="button" class="btn-gold !py-3 !text-base" id="btn-next"></button>
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
                    <h1 id="results-title" class="mb-2 text-3xl font-bold text-mist-100">{{ __('Resultados de hoy') }}</h1>

                    <p class="mb-6 text-5xl font-bold text-gold-300">
                        <span id="results-score">0</span><span class="text-2xl text-mist-200/80">{{ __(' de ') }}</span><span class="text-2xl text-mist-200/80" id="results-total">10</span>
                    </p>
                    <p class="mx-auto mb-8 max-w-md text-lg leading-relaxed text-mist-200/95" id="results-message"></p>

                    <p id="results-practice-note" class="info-card mx-auto mb-8 hidden max-w-md px-5 py-3 text-sm font-semibold text-iris-300">
                        {{ __('Resultado de práctica: no se guarda ni cambia tu registro del día o tu racha.') }}
                    </p>

                    <div class="mx-auto mb-10 max-w-lg text-left">
                        <div id="results-strong" class="mb-6"></div>
                        <div id="results-practice"></div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <button type="button" class="btn-gold" id="btn-home">{{ __('Volver al inicio') }}</button>
                        <button type="button" class="btn-ghost" id="btn-replay"></button>
                    </div>
                    <p class="mt-5 text-sm text-mist-200/70">{{ __('La próxima sesión estará lista mañana') }}</p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
