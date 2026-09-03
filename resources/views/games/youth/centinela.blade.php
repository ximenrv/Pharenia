<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('youth.centinela.meta.description') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('youth.centinela.meta.title') }}</title>
    <script>window.APP_LOCALE = "{{ app()->getLocale() }}";</script>
    @vite(['resources/css/centinela/centinela.css', 'resources/js/centinela/app.js'])
</head>
<body class="min-h-screen antialiased">

    {{-- Símbolos SVG: figuras de la patrulla (contorno sin relleno)
         El color lo pone el CSS: verde = proteger, rojo = amenaza. --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        {{-- 🟢 PROTEGER: elementos de la vida cotidiana --}}
        <symbol id="fig-nino" viewBox="0 0 48 48">
            <circle cx="24" cy="12" r="5.5"/>
            <path d="M24 18v12"/>
            <path d="M15 22l9-3 9 3"/>
            <path d="M24 30l-6 10"/>
            <path d="M24 30l6 10"/>
        </symbol>

        <symbol id="fig-perro" viewBox="0 0 48 48">
            <path d="M16 17q-8 0-8 10 0 6 4 8"/>
            <path d="M32 17q8 0 8 10 0 6-4 8"/>
            <circle cx="24" cy="25" r="10"/>
            <circle cx="24" cy="29" r="3.2"/>
            <circle cx="24" cy="27" r="1.2" fill="currentColor" stroke="none"/>
            <circle cx="20" cy="22.5" r="1" fill="currentColor" stroke="none"/>
            <circle cx="28" cy="22.5" r="1" fill="currentColor" stroke="none"/>
        </symbol>

        <symbol id="fig-gato" viewBox="0 0 48 48">
            <path d="M14 20l-3-11 10 6"/>
            <path d="M34 20l3-11-10 6"/>
            <circle cx="24" cy="25" r="10.5"/>
            <path d="M12 25l-7-1"/>
            <path d="M12 28.5l-7 2"/>
            <path d="M36 25l7-1"/>
            <path d="M36 28.5l7 2"/>
            <path d="M21 30q3 2.5 6 0"/>
            <circle cx="20" cy="23" r="1" fill="currentColor" stroke="none"/>
            <circle cx="28" cy="23" r="1" fill="currentColor" stroke="none"/>
        </symbol>

        <symbol id="fig-serpiente" viewBox="0 0 48 48">
            <path d="M13 40q-7-9 3-12 13-4 11-12-1.5-7-9-5.5"/>
            <circle cx="17" cy="10" r="3.8"/>
            <path d="M14.5 7L11 3"/>
            <path d="M11 3l-2.2 1"/>
            <path d="M11 3l0.8-2.2"/>
            <circle cx="15.8" cy="9.5" r="0.9" fill="currentColor" stroke="none"/>
        </symbol>

        <symbol id="fig-arbol" viewBox="0 0 48 48">
            <circle cx="24" cy="17" r="10"/>
            <path d="M24 27v14"/>
            <path d="M24 34l-5-4"/>
            <path d="M24 34l5-4"/>
            <path d="M16 42h16"/>
        </symbol>

        <symbol id="fig-familia" viewBox="0 0 48 48">
            <circle cx="13" cy="11" r="4"/>
            <path d="M13 15v12"/>
            <path d="M13 27l-4 9"/>
            <path d="M13 27l4 9"/>
            <circle cx="35" cy="11" r="4"/>
            <path d="M35 15v12"/>
            <path d="M35 27l-4 9"/>
            <path d="M35 27l4 9"/>
            <circle cx="24" cy="21" r="3"/>
            <path d="M24 24v7"/>
            <path d="M24 31l-3 6"/>
            <path d="M24 31l3 6"/>
            <path d="M13 19q5.5 5 11 4"/>
            <path d="M35 19q-5.5 5-11 4"/>
        </symbol>

        <symbol id="fig-amigos" viewBox="0 0 48 48">
            <circle cx="16" cy="12" r="4.5"/>
            <path d="M16 17v13"/>
            <path d="M16 30l-4 9"/>
            <path d="M16 30l4 9"/>
            <circle cx="32" cy="12" r="4.5"/>
            <path d="M32 17v13"/>
            <path d="M32 30l-4 9"/>
            <path d="M32 30l4 9"/>
            <path d="M16 21q8-6 16 0"/>
        </symbol>

        {{-- 🔴 AMENAZAS: figuras que deben neutralizarse --}}
        <symbol id="fig-encapuchado" viewBox="0 0 48 48">
            <path d="M12 42V30q0-16 12-16 12 0 12 16v12"/>
            <path d="M18.5 34q-1.5-12 5.5-12 7 0 5.5 12"/>
            <path d="M9 42h30"/>
        </symbol>

        <symbol id="fig-monstruo" viewBox="0 0 48 48">
            <path d="M15 21l-5-9"/>
            <path d="M33 21l5-9"/>
            <path d="M12 42v-7q0-14 12-14 12 0 12 14v7"/>
            <circle cx="19.5" cy="29" r="2"/>
            <circle cx="28.5" cy="29" r="2"/>
            <path d="M16 37.5l3-3 3 3 3-3 3 3 3-3"/>
        </symbol>

        <symbol id="fig-mascara" viewBox="0 0 48 48">
            <circle cx="24" cy="21" r="11"/>
            <path d="M13.5 18.5h21v5.5h-21z"/>
            <circle cx="20" cy="21.2" r="1.1" fill="currentColor" stroke="none"/>
            <circle cx="28" cy="21.2" r="1.1" fill="currentColor" stroke="none"/>
            <path d="M14.5 15q0.5-8.5 9.5-8.5 9 0 9.5 8.5"/>
            <path d="M13.5 15.5h21"/>
            <path d="M24 32v3.5"/>
            <path d="M15 42q9-6 18 0"/>
        </symbol>

        <symbol id="fig-espectro" viewBox="0 0 48 48">
            <path d="M12 42V22q0-12 12-12 12 0 12 12v20"/>
            <path d="M12 42q3-5 6 0 3-5 6 0 3-5 6 0 3-5 6 0"/>
            <path d="M18 21.5l5 2.5"/>
            <path d="M30 21.5l-5 2.5"/>
            <path d="M21 31q3-2.5 6 0"/>
        </symbol>

        {{-- Nave del jugador (dorada) --}}
        <symbol id="fig-nave" viewBox="0 0 48 48">
            <path d="M24 5q7 9 7 19l1 9H16l1-9q0-10 7-19z"/>
            <path d="M17 28l-9 8V25"/>
            <path d="M31 28l9 8V25"/>
            <circle cx="24" cy="21" r="3.2"/>
            <path d="M20.5 33.5v4"/>
            <path d="M27.5 33.5v4"/>
        </symbol>

        {{-- ⭐ Tarjetas doradas: se recogen con la nave, no se disparan --}}
        <symbol id="fig-pow-rapida" viewBox="0 0 48 48">
            <rect x="9" y="5" width="30" height="38" rx="7"/>
            <path d="M26 12l-8 14h5.5L21 36l10-14h-5.5L26 12z" fill="currentColor" stroke="none"/>
        </symbol>

        <symbol id="fig-pow-doble" viewBox="0 0 48 48">
            <rect x="9" y="5" width="30" height="38" rx="7"/>
            <path d="M17 30l7-7 7 7"/>
            <path d="M17 21l7-7 7 7"/>
        </symbol>
    </svg>

    {{-- Fondo generado por canvas + viñeta + grano --}}
    <canvas id="sky-canvas" class="sky-canvas" aria-hidden="true"></canvas>
    <div class="vignette-overlay" aria-hidden="true"></div>
    <div class="noise-overlay" aria-hidden="true"></div>

    {{-- Telón de teatro: se cierra al terminar la partida --}}
    <div class="curtain" id="curtain" aria-hidden="true">
        <div class="curtain-half curtain-left"></div>
        <div class="curtain-half curtain-right"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-3xl flex-col px-5 py-8 sm:px-8">

        {{-- Cabecera --}}
        <header class="mb-8 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/mascota.png') }}" alt="{{ __('youth.centinela.header.brand') }}" class="h-12 w-auto drop-shadow-[0_6px_12px_rgba(0,0,0,0.5)]">
                <div>
                    <p class="text-xl font-bold tracking-wide text-gold-300">{{ __('youth.centinela.header.brand') }}</p>
                    <p class="text-sm text-mist-200/80">{{ __('youth.centinela.header.slogan') }}</p>
                </div>
            </div>
            <a href="{{ route('activities.youth') }}" class="btn-ghost !px-4 !py-2 text-sm">&larr; {{ __('youth.centinela.header.back') }}</a>
        </header>

        <main class="flex flex-1 flex-col">

            {{-- ============================================
                 PANTALLA: INICIO
                 ============================================ --}}
            <section class="screen" id="screen-home" data-screen="home" aria-labelledby="home-title">
                <div class="relative mb-8 flex justify-center">
                    <img src="{{ asset('img/mascota.png') }}" alt="" class="float-gentle h-44 w-auto drop-shadow-[0_20px_32px_rgba(0,0,0,0.55)] sm:h-52">
                </div>

                <div class="glass-card p-8 text-center sm:p-10">
                    <h1 id="home-title" class="mb-3 text-3xl font-bold text-mist-100 sm:text-4xl">
                        {{ __('youth.centinela.home.title') }}
                    </h1>
                    <p class="mx-auto mb-3 max-w-md text-lg leading-relaxed text-mist-200/90">
                        {!! __('youth.centinela.home.intro_line1') !!}
                    </p>
                    <p class="mx-auto mb-8 max-w-md text-base italic leading-relaxed text-gold-300/90">
                        {{ __('youth.centinela.home.intro_line2') }}
                    </p>

                    <p class="mb-4 text-sm font-semibold uppercase tracking-wider text-mist-200/70">
                        {{ __('youth.centinela.home.choose_patrol') }}
                    </p>
                    <div id="difficulty-grid" class="grid gap-4 sm:grid-cols-3"></div>

                    <div class="mt-8">
                        <button type="button" class="btn-ghost" id="btn-howto">
                            {{ __('youth.centinela.home.howto_button') }}
                        </button>
                    </div>
                </div>
            </section>

            {{-- ============================================
                 PANTALLA: CÓMO FUNCIONA
                 ============================================ --}}
            <section class="screen" id="screen-howto" data-screen="howto" aria-labelledby="howto-title">
                <div class="glass-card p-8 sm:p-10">
                    <h1 id="howto-title" class="mb-8 text-center text-3xl font-bold text-mist-100">{{ __('youth.centinela.howto.title') }}</h1>

                    <ol class="mx-auto flex max-w-lg flex-col gap-6">
                        @foreach (__('youth.centinela.howto.steps') as $step)
                            <li class="flex items-start gap-4">
                                <span class="step-number" aria-hidden="true">{{ $loop->iteration }}</span>
                                <div>
                                    <p class="font-semibold text-gold-300">{{ $step['title'] }}</p>
                                    <p class="leading-relaxed text-mist-200/90">
                                        {!! $step['text'] !!}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ol>

                    {{-- Leyenda de figuras --}}
                    <div class="mx-auto mt-10 flex max-w-lg flex-col gap-5">
                        <div class="info-card p-5">
                            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-leaf-300">{{ __('youth.centinela.howto.legend_protect') }}</p>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-nino"/></svg></span>
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-perro"/></svg></span>
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-gato"/></svg></span>
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-serpiente"/></svg></span>
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-arbol"/></svg></span>
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-familia"/></svg></span>
                                <span class="legend-figure fig-color-protect"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-amigos"/></svg></span>
                            </div>
                        </div>
                        <div class="info-card p-5">
                            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-ember-300">{{ __('youth.centinela.howto.legend_threat') }}</p>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="legend-figure fig-color-threat"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-encapuchado"/></svg></span>
                                <span class="legend-figure fig-color-threat"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-monstruo"/></svg></span>
                                <span class="legend-figure fig-color-threat"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-mascara"/></svg></span>
                                <span class="legend-figure fig-color-threat"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-espectro"/></svg></span>
                            </div>
                        </div>
                        <div class="info-card p-5">
                            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-gold-300">{{ __('youth.centinela.howto.legend_help') }}</p>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="legend-figure fig-color-gold"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-pow-rapida"/></svg></span>
                                <span class="legend-figure fig-color-gold"><svg viewBox="0 0 48 48" aria-hidden="true"><use href="#fig-pow-doble"/></svg></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 text-center">
                        <button type="button" class="btn-ghost" id="btn-howto-back">&larr; {{ __('youth.centinela.howto.back_button') }}</button>
                    </div>
                </div>
            </section>

            {{-- ============================================
                 PANTALLA: PATRULLA (juego)
                 ============================================ --}}
            <section class="screen" id="screen-game" data-screen="game" aria-labelledby="game-heading">
                <h1 id="game-heading" class="sr-only">{{ __('youth.centinela.game.heading') }}</h1>

                {{-- Barra superior: salir + opciones --}}
                <div class="mb-4 flex items-center justify-between gap-3">
                    <button type="button" class="btn-ghost !px-4 !py-2 text-sm" id="btn-exit">
                        &larr; {{ __('youth.centinela.game.exit') }}
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-ghost !px-4 !py-2 text-sm" id="btn-sound" aria-pressed="false">
                            {{ __('youth.centinela.game.sound_off') }}
                        </button>
                        <button type="button" class="btn-ghost !px-4 !py-2 text-sm" id="btn-pause">
                            {{ __('youth.centinela.game.pause') }}
                        </button>
                    </div>
                </div>

                {{-- HUD: integridad + puntuación --}}
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div class="hud-panel" id="hud-integrity">
                        <span class="hud-label">{{ __('youth.centinela.game.integrity') }}</span>
                        <span class="hud-pips" id="integrity-pips" aria-hidden="true"></span>
                        <span class="hud-value hud-value--integrity" id="integrity-value" aria-live="polite">10</span>
                    </div>
                    <div class="hud-panel" id="hud-score">
                        <span class="hud-label">{{ __('youth.centinela.game.score') }}</span>
                        <span class="hud-value" id="score-value" aria-live="polite">0</span>
                    </div>
                </div>

                {{-- Dificultad + progreso --}}
                <div class="mb-2 flex flex-wrap items-center justify-between gap-3">
                    <p class="category-chip" id="difficulty-chip"></p>
                    <p class="text-sm font-semibold tracking-wide text-mist-200">
                        <span id="progress-label"></span>
                    </p>
                </div>
                <div class="mb-4 flex items-center gap-3">
                    <div class="progress-track flex-1" role="progressbar" aria-label="{{ __('youth.centinela.game.progress') }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                </div>

                {{-- Arena --}}
                <div class="arena-wrap">
                    <div class="arena" id="arena" aria-label="{{ __('youth.centinela.game.arena_label') }}"></div>

                    {{-- Insignias de tarjeta activa --}}
                    <div class="powerup-badge" id="badge-rapid" aria-hidden="true">
                        <svg viewBox="0 0 48 48"><use href="#fig-pow-rapida"/></svg>
                        <span>{{ __('youth.centinela.game.rapid_label') }}</span>
                        <span class="powerup-badge-track"><span class="powerup-badge-fill"></span></span>
                    </div>
                    <div class="powerup-badge powerup-badge--double" id="badge-double" aria-hidden="true">
                        <svg viewBox="0 0 48 48"><use href="#fig-pow-doble"/></svg>
                        <span>{{ __('youth.centinela.game.double_label') }}</span>
                        <span class="powerup-badge-track"><span class="powerup-badge-fill"></span></span>
                    </div>

                    {{-- Pausa --}}
                    <div class="arena-overlay" id="pause-overlay">
                        <div class="glass-card mx-4 p-8 text-center">
                            <p class="mb-2 text-2xl font-bold text-mist-100">{{ __('youth.centinela.game.pause_title') }}</p>
                            <p class="mb-6 text-mist-200/90">{{ __('youth.centinela.game.pause_text') }}</p>
                            <div class="flex flex-wrap items-center justify-center gap-3">
                                <button type="button" class="btn-gold !py-3 !text-base" id="btn-resume">{{ __('youth.centinela.game.resume') }}</button>
                                <button type="button" class="btn-ghost" id="btn-quit-pause">{{ __('youth.centinela.game.quit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-center text-sm text-mist-200/70">
                    {{ __('youth.centinela.game.controls') }}
                </p>
            </section>

            {{-- ============================================
                 PANTALLA: RESULTADOS (tras el telón)
                 ============================================ --}}
            <section class="screen" id="screen-results" data-screen="results" aria-labelledby="results-title">
                <div class="question-frame">
                    <div class="question-body text-center">
                        <h1 id="results-title" class="mb-1 text-2xl font-bold uppercase tracking-[0.22em] text-gold-300 sm:text-3xl">
                            {{ __('youth.centinela.results.title') }}
                        </h1>
                        <p class="mb-8 text-sm text-mist-200/70" id="results-subtitle">{{ __('youth.centinela.results.subtitle') }}</p>

                        <div class="mx-auto mb-8 max-w-md text-left">
                            <div class="stat-row">
                                <span class="stat-label"><span aria-hidden="true">⭐</span> {{ __('youth.centinela.results.score') }}</span>
                                <span class="stat-value stat-value--gold" id="stat-score">0</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label"><span aria-hidden="true">🎯</span> {{ __('youth.centinela.results.precision') }}</span>
                                <span class="stat-value" id="stat-precision">100%</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label"><span aria-hidden="true">🟢</span> {{ __('youth.centinela.results.protected') }}</span>
                                <span class="stat-value stat-value--leaf" id="stat-protected">0</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label"><span aria-hidden="true">🔴</span> {{ __('youth.centinela.results.threats') }}</span>
                                <span class="stat-value stat-value--ember" id="stat-threats">0</span>
                            </div>
                        </div>

                        <p class="mx-auto mb-3 max-w-md leading-relaxed text-mist-200/95" id="results-message"></p>
                        <p class="results-quote mb-9">{{ __('youth.centinela.results.quote') }}</p>

                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <button type="button" class="btn-gold" id="btn-replay">{{ __('youth.centinela.results.replay') }}</button>
                            <button type="button" class="btn-ghost" id="btn-home">{{ __('youth.centinela.results.home') }}</button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
