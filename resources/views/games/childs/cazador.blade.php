@php
    $activeChildId = session('active_child_id'); // ID del niño seleccionado
    $userId = auth()->id();

    // Consulta el récord según si hay un niño seleccionado o juega el usuario directo
    $query = \App\Models\RecordGamesChild::query();
    if ($activeChildId) {
        $query->where('child_profile_id', $activeChildId);
    } else {
        $query->where('user_id', $userId);
    }

    $userRecord = auth()->check() ? ($query->value('record_Cazador') ?? 0) : 0;
@endphp

@php
    $locale = app()->getLocale();
    $path1 = lang_path($locale . '.json');
    $path2 = resource_path('lang/' . $locale . '.json');

    $jsonPath = file_exists($path1) ? $path1 : (file_exists($path2) ? $path2 : null);
    $translations = $jsonPath ? json_decode(file_get_contents($jsonPath), true) : [];
@endphp
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> {{ __('Cazador de Burbujas') }} - Pharenia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/cazador/CSS/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/cazador/CSS/style.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/cazador/CSS/animations.css') }}">

    <!-- ================ Night  Mode ================ -->
    @vite(['resources/css/dark-theme-games.css'])

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
        
      // Ruta base específica
      window.GAME_ASSETS_PATH = "{{ asset('gamesAssets/childs/cazador/ASSETS') }}";
      window.GAME_MENU_URL = "{{ url('/activities/ninez') }}";
      window.CSRF_TOKEN = "{{ csrf_token() }}";
      window.UPDATE_RECORD_URL = "{{ route('games.childs.record.update') }}";
      window.INITIAL_HIGH_SCORE = parseInt("{{ $userRecord ?? 0 }}", 10) || 0;
      window.ACTIVE_CHILD_ID = {{ session('active_child_id') ?? 'null' }};

      //Translation
      window.translations = @json($translations, JSON_FORCE_OBJECT);
    </script>
</head>

<body>

<div id="game">
    @include('components.loader')

    <!-- ================= BANNER SUPERIOR ================= -->
    <div id="recordPanel" class="panel">
        <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/hudPanel.png') }}" alt="Panel Récord"> 
        <div class="panelContent">
            <span class="hudLabel"> {{ __('RÉCORD') }}</span>
            <span id="record" class="hudValue">0</span>
        </div>
    </div>

    <div id="gameStatus">
            <div id="timerBox" class="hudBox">
                <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/hudPanel.png') }}" alt="Timer Panel">
                <div class="hudContent">
                    <span class="hudTitle">⏱</span>
                    <span id="timerValue" class="hudValue">02:00</span>
                </div>
            </div>
        </div>

    <!-- ================ PARTE CENTRAL Y DERECHA ================ -->
    <main id="world">
        <img id="bgDay" class="game-bg bg-day" src="{{ asset('gamesAssets/childs/cazador/ASSETS/background/background.png') }}" alt="Fondo Día">
        <img id="bgNight" class="game-bg bg-night" src="{{ asset('gamesAssets/childs/cazador/ASSETS/background/background_night.png') }}" alt="Fondo Noche">
        <div id="boardContainer">
            <div id="scorePanel" class="panel">
                <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/pointsbox.png') }}" alt="Panel Puntos">
                <div class="panelContent">
                    <span class="hudLabel"> {{ __('PUNTOS') }}</span>
                    <span id="score" class="hudValue">0</span>
                </div>
            </div>

            <!-- TABLERO CENTRAL CON 9 SLOTS (3x3) -->
            <div id="memoryBoard">
                <div class="boardSlot" data-slot="1"></div>
                <div class="boardSlot" data-slot="2"></div>
                <div class="boardSlot" data-slot="3"></div>
                <div class="boardSlot" data-slot="4"></div>
                <div class="boardSlot" data-slot="5"></div>
                <div class="boardSlot" data-slot="6"></div>
                <div class="boardSlot" data-slot="7"></div>
                <div class="boardSlot" data-slot="8"></div>
                <div class="boardSlot" data-slot="9"></div>
            </div>
        </div>

        <!-- ================= ASSISTANT ZONE (DERECHA) ================= -->
        <aside id="assistantZone" class="show">
              <div id="assistantText" class="showBubble">
                <img src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/txtbubbles/assistText.png') }}" alt="Diálogo">
                <p id="assistantDialogue">¡-</p>
            </div>
            <!-- Contenedor del Asistente Lumen -->
            <div id="assistant">
                <!-- Pedestal / Base -->
                <img id="assistantBubble" src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/assistent/LumenBubbleAssist.png') }}" alt="Pedestal">
                <!-- Lumen que cambia de imagen (Base / Cartel con Estrella, Corazón, etc.) -->
                <img id="assistantHead" src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/assistent/lumenStar.png') }}" alt="Lumen Asistente">
            </div>
            <!-- Barra de Vidas -->
             <div id="healthBarContainer">
                <div id="life1" class="healthSegment"></div>
                <div id="life2" class="healthSegment"></div>
                <div id="life3" class="healthSegment"></div>
            </div>
        </aside>
    </main>

    <!-- TUTORIAL -->
    <footer id="bottomUI">
        <div id="tutorialLayer">
            <div id="lumenContainer">
                <img id="lumen" src="{{ asset('gamesAssets/childs/cazador/ASSETS/lumen/tutorial/lumenHappy.png') }}" alt="Lumen Tutorial">
            </div>
            <div id="tutorialPanel">
                <div id="speakerName">LUMEN</div>
                <img id="tutorialBubble" src="{{ asset('gamesAssets/childs/cazador/ASSETS/lumen/txtbubbles/mainBox.png') }}" alt="Globo de diálogo tutorial">
                <div id="tutorialContent">
                    <p id="tutorialText"></p>
                    <div id="tutorialButtons">
                        <img id="nextButton" class="uiButton hidden" src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/nextbutton.png') }}" alt="Siguiente">
                        <img id="playButton" class="uiButton hidden" src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/playbutton.png') }}" alt="Jugar">
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="globalReturnBtn" class="hidden">
        <a href="{{ url('/activities/ninez') }}">
            <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/globalReturn.png') }}" alt="{{ __('Volver') }}">
        </a>
    </div>

    <div id="effectsLayer"></div>

    <!--- PANTALLA DE GAME OVER --->
    <div id="timeUpOverlay"></div>
    <h1 id="timeUpBanner">{{ __('¡SE ACABÓ EL TIEMPO!') }}</h1>

    <div id="gameOverScreen">
        <div id="gameOverContainer">
            <h1 id="gameOverTitle"> {{ __('¡BUEN INTENTO!') }}</h1>
            <div id="gameOverMessage">
                <h2 id="messageTitle">--</h2>
                <p id="messageText">--</p>
            </div>
            <!-- Bloque de stats -->
            <div id="gameOverStats">
                <div class="gameOverBlock" id="scoreBlock">
                    <h2 class="statTitle"> {{ __('PUNTOS') }}</h2>
                    <span class="statValue" id="finalScore">0</span>
                    <div class="divider"></div>
                </div>
        
                <div class="gameOverBlock" id="recordBlock">
                    <h2 class="statTitle"> {{ __('RÉCORD') }}</h2>
                    <span class="statValue" id="finalRecord">450</span>
                    <div class="divider"></div>
                </div>
           
                <div class="gameOverBlock" id="waveBlock">
                    <h2 class="statTitle"> {{ __('RONDA') }}</h2>
                    <span class="statValue" id="finalWave">1</span>
                </div>
            </div>
            <div id="gameOverActions">
                <div id="returnButton">
                    <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/returnbutton.png') }}" alt="Volver">
                </div>
                <div id="gameOverStar">
                    <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/MenuStar.png') }}" alt="Estrella">
                </div>

                <div id="retryButton">
                    <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/ui/retrybutton.png') }}" alt="Retry">
                </div>
            </div>
        </div>
        <div id="gameOverLumen">
            <img src="{{ asset('gamesAssets/childs/cazador/ASSETS/lumen/tutorial/lumenVictory.png') }}" alt="Lumen GameOver">
        </div>
    </div>
</div>
<script src="{{ asset('gamesAssets/childs/cazador/JS/soundManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/roundManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/ui.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/assistLumen.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/figuresManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/figuresGameEngine.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/tutorial.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/cazador/JS/game.js') }}"></script>
</body>
</html>