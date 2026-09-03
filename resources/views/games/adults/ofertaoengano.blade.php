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
    <a href="{{ route('activities.adultez') }}" class="btn-volver-actividades" title="Regresar a Actividades">
        <span class="bva-flecha">◀</span> Regresar a Actividades
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
                    Regresar
                </button>
                <button class="btn-game btn-next" id="btn-next">
                    Siguiente
                    <span class="btn-icon">▶</span>
                </button>
                <button class="btn-play" id="btn-play">Jugar</button>
            </div>
        </div>
    </div>

    {{-- ============ PANTALLA 2: NIVEL (cartel + situación + laberinto) ============ --}}
    <div id="screen-game" class="screen">
        <div class="background-layer" style="background-image: url('{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/backgrounds/espacio.jpg') }}')"></div>

        {{-- Botón regresar --}}
        <button class="btn-game-back" id="btn-game-back">
            <span>◀</span>
            Regresar
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
                Siguiente
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
        </div>
    </div>

    {{-- Transición --}}
    <div id="transition-overlay" class="transition-overlay"></div>

    <script>
        window.GAME_ASSETS = {
            lumen: '{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/Lumen/lumen1.png') }}'
        };
        window.GAME_MENU_URL = '{{ route('activities.adultez') }}';
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        window.SAVE_RECORD_URL = '{{ route('games.adults.record.update') }}';
    </script>
    <script src="{{ asset('gamesAssets/adults/ofertaoengano/JS/intro.js') }}"></script>
    <script src="{{ asset('gamesAssets/adults/ofertaoengano/JS/game.js') }}"></script>
</body>
</html>
