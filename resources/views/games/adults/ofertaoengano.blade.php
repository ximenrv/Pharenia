<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta o Engaño - Pharenia</title>
    <link rel="stylesheet" href="{{ asset('gamesAssets/adults/ofertaoengano/CSS/intro.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/adults/ofertaoengano/CSS/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Botón fijo para salir a Actividades (siempre visible, discreto) */
        .btn-volver-actividades {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 9999;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            font-family: 'Fredoka', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            color: #e6ddf7;
            background: rgba(30, 20, 60, 0.72);
            border: 1.5px solid rgba(212, 175, 55, 0.55);
            border-radius: 12px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
            opacity: 0.72;
            transition: opacity 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
        }
        .btn-volver-actividades:hover {
            opacity: 1;
            transform: translateY(-1px);
            border-color: #d4af37;
        }
        .btn-volver-actividades .bva-flecha { font-size: 0.9em; }
        /* El botón "Regresar" interno baja para no encimarse con el de salir */
        .btn-game-back { top: 62px !important; }
    </style>
</head>
<body>
    <a href="{{ route('activities.adultez') }}" class="btn-volver-actividades" title="{{ __('Regresar a Actividades') }}">
        <span class="bva-flecha">◀</span> {{ __('Regresar a Actividades') }}
    </a>

    {{-- ============ PANTALLA 1: INTRO ============ --}}
    <div id="screen-intro" class="screen active">
        <div class="background-layer" style="background-image: url('{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/backgrounds/fondodia.png') }}')"></div>
        <div class="overlay"></div>

        <div class="lumen-container" id="lumen-container">
            <img src="{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/Lumen/lumen1.png') }}" alt="Lumen" class="lumen-img" id="lumen">
        </div>

        <div class="dialogue-container" id="dialogue-box">
            <div class="dialogue-bubble" id="dialogue-bubble">
                <p class="dialogue-text" id="dialogue-text"></p>
                <span class="dialogue-indicator" id="dialogue-indicator">▼</span>
            </div>
            <div class="nav-buttons" id="nav-buttons">
                <button class="btn-game btn-back" id="btn-back">
                    <span class="btn-icon">◀</span>
                    {{ __('Regresar') }}
                </button>
                <button class="btn-game btn-next" id="btn-next">
                    {{ __('Siguiente') }}
                    <span class="btn-icon">▶</span>
                </button>
                <button class="btn-play" id="btn-play">{{ __('Jugar') }}</button>
            </div>
        </div>
    </div>

    {{-- ============ PANTALLA 2: NIVEL (cartel + situación + laberinto) ============ --}}
    <div id="screen-game" class="screen">
        <div class="background-layer" style="background-image: url('{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/backgrounds/espacio.jpg') }}')"></div>

        {{-- Botón regresar --}}
        <button class="btn-game-back" id="btn-game-back">
            <span>◀</span>
            {{ __('Regresar') }}
        </button>

        {{-- FASE 1: Cartel del nivel --}}
        <div id="phase-level" class="game-phase">
            <div class="level-card">
                <div class="level-card-deco top-left"></div>
                <div class="level-card-deco top-right"></div>
                <div class="level-card-deco bottom-left"></div>
                <div class="level-card-deco bottom-right"></div>
                <span class="level-label" id="level-label">Nivel 1</span>
                <h1 class="level-title" id="level-title">Compras</h1>
            </div>
            <button class="btn-game btn-next level-next" id="btn-level-next">
                {{ __('Siguiente') }}
                <span class="btn-icon">▶</span>
            </button>
        </div>

        {{-- FASE 2: Situación --}}
        <div id="phase-situation" class="game-phase" style="display:none;">
            <div class="situation-card">
                <span class="situation-label" id="situation-label">Situación 1</span>
                <p class="situation-text" id="situation-text"></p>
                <div class="timer-bar-container">
                    <div class="timer-bar" id="timer-bar"></div>
                </div>
            </div>
        </div>

        {{-- FASE 3: Laberinto --}}
        <div id="phase-maze" class="game-phase" style="display:none;">
            {{-- Escudos (vidas) --}}
            <div class="hearts-container" id="hearts-container"></div>

            {{-- Situación minimizada arriba (expandible al hacer clic) --}}
            <div class="situation-mini" id="situation-mini">
                <span class="situation-mini-label">Situación:</span>
                <span class="situation-mini-text" id="situation-mini-text"></span>
                <span class="situation-mini-toggle" id="situation-mini-toggle">👁</span>
            </div>

            {{-- Canvas del laberinto --}}
            <canvas id="maze-canvas"></canvas>

            {{-- Controles táctiles (D-pad) para mover a Lumen sin teclado.
                 Solo se muestran en pantallas táctiles/pequeñas (ver CSS). --}}
            <div id="maze-dpad" aria-label="{{ __('Controles') }}">
                <button type="button" class="dpad-btn dpad-up" data-dir="ArrowUp" aria-label="{{ __('Arriba') }}">▲</button>
                <button type="button" class="dpad-btn dpad-left" data-dir="ArrowLeft" aria-label="{{ __('Izquierda') }}">◀</button>
                <button type="button" class="dpad-btn dpad-down" data-dir="ArrowDown" aria-label="{{ __('Abajo') }}">▼</button>
                <button type="button" class="dpad-btn dpad-right" data-dir="ArrowRight" aria-label="{{ __('Derecha') }}">▶</button>
            </div>
        </div>
    </div>

    {{-- Transición --}}
    <div id="transition-overlay" class="transition-overlay"></div>

    @php
        $locale = app()->getLocale();
        $p1 = lang_path($locale . '.json');
        $p2 = resource_path('lang/' . $locale . '.json');
        $jsonPath = file_exists($p1) ? $p1 : (file_exists($p2) ? $p2 : null);
        $translations = $jsonPath ? json_decode(file_get_contents($jsonPath), true) : [];
    @endphp
    <script>
        window.GAME_ASSETS = {
            lumen: '{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/Lumen/lumen1.png') }}'
        };
        window.GAME_MENU_URL = '{{ route('activities.adultez') }}';
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        window.SAVE_RECORD_URL = '{{ route('games.adults.record.update') }}';
        window.translations = @json($translations, JSON_FORCE_OBJECT);
    </script>
    <script src="{{ asset('gamesAssets/adults/ofertaoengano/JS/intro.js') }}"></script>
    <script src="{{ asset('gamesAssets/adults/ofertaoengano/JS/game.js') }}"></script>

    {{-- Wiring del D-pad táctil: despacha eventos de flecha sintéticos para
         reutilizar EXACTAMENTE la misma lógica de movimiento del teclado.
         No modifica nada del juego. --}}
    <script>
        (function () {
            var dpad = document.getElementById('maze-dpad');
            if (!dpad) return;
            function move(key) {
                document.dispatchEvent(new KeyboardEvent('keydown', { key: key, bubbles: true }));
            }
            dpad.addEventListener('pointerdown', function (e) {
                var btn = e.target.closest('.dpad-btn');
                if (!btn) return;
                e.preventDefault();
                btn.classList.add('is-pressed');
                move(btn.getAttribute('data-dir'));
            });
            dpad.addEventListener('pointerup', function (e) {
                var btn = e.target.closest('.dpad-btn');
                if (btn) btn.classList.remove('is-pressed');
            });
            dpad.addEventListener('pointerleave', function (e) {
                var btn = e.target.closest('.dpad-btn');
                if (btn) btn.classList.remove('is-pressed');
            }, true);
            // Evita el zoom por doble-tap sobre los controles
            dpad.addEventListener('touchstart', function (e) { e.preventDefault(); }, { passive: false });
        })();
    </script>
</body>
</html>
