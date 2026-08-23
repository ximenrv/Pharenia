@php
    $userRecord = auth()->check() 
        ? \App\Models\RecordGamesChild::where('email', auth()->user()->email)->value('record_Eco') ?? 0 
        : 0;
@endphp

@php
    $locale = app()->getLocale();
    $path1 = lang_path($locale . '.json');
    $path2 = resource_path('lang/' . $locale . '.json');

    $jsonPath = file_exists($path1) ? $path1 : (file_exists($path2) ? $path2 : null);
    $translations = $jsonPath ? json_decode(file_get_contents($jsonPath), true) : [];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> {{ __('Eco de los Colores') }} - Pharenia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/eco/CSS/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/eco/CSS/style.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/eco/CSS/animations.css') }}">

    <script>
      // Ruta base específica
      window.GAME_ASSETS_PATH = "{{ asset('gamesAssets/childs/eco/ASSETS') }}";
      window.GAME_MENU_URL = "{{ url('/activities/ninez') }}"
      window.CSRF_TOKEN = "{{ csrf_token() }}";
      window.UPDATE_RECORD_URL = "{{ route('games.childs.record.update') }}";
      window.INITIAL_HIGH_SCORE = parseInt("{{ $userRecord ?? 0 }}", 10) || 0;

      //Translation
      window.translations = @json($translations, JSON_FORCE_OBJECT);
    </script>
</head>

<body>

<div id="game">
    @include('components.loader')
    <!-- ================= HUD SUPERIOR ================= -->
    <header id="hud">
        <!-- Banner de Puntos -->
        <div id="scorePanel" class="panel">
            <!-- Reemplaza slots.png por el nombre exacto de la imagen de tu banner en ASSETS/ui/ -->
            <img src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/pointsbox.png') }}" alt="Panel Puntos"> 
            <div class="panelContent">
                <span class="hudLabel"> {{ __('PUNTOS') }}</span>
                <span id="score" class="hudValue">0</span>
            </div>
        </div>

        <!-- Banner de Récord -->
        <div id="recordPanel" class="panel">
            <img src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/hudPanel.png') }}" alt="Panel Récord">
            <div class="panelContent">
                <span class="hudLabel"> {{ __('RÉCORD') }}</span>
                <span id="record" class="hudValue">0</span>
            </div>
        </div>
    </header>

    <!-- ================ PARTE CENTRAL ================ -->
    <main id="world">
        <img id="background" src="{{ asset('gamesAssets/childs/eco/ASSETS/background/background.png') }}" alt="Fondo">

        <!-- TABLERO CENTRAL DE MEMORIA -->
        <div id="memoryBoard">
            <div id="bubbleBlue" class="colorBubble blue"></div>
            <div id="bubbleCyan" class="colorBubble cyan"></div>
            <div id="bubbleBlack" class="colorBubble black"></div>
            <div id="bubbleViolet" class="colorBubble violet"></div>
        </div>

        <!-- ================= ASSISTANT ZONE ================= -->
        <div id="assistantZone">
            <!-- Globo de texto de Lumen -->
            <div id="assistantText">
                <img src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/txtbubbles/assistText.png') }}" alt="Diálogo">
                <p id="assistantDialogue">.</p>
            </div>

            <!-- Lumen y Pedestal -->
            <div id="assistant">
                <img id="assistantBubble" src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/assistent/LumenBubbleAssist.png') }}" alt="Pedestal">
                <img id="assistantHead" src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/assistent/lumenBase.png') }}" alt="Lumen Assistant">
            </div>

            <!-- Barra de Secuencia -->
            <div id="sequenceBar">
                <div id="sequenceSlots"></div>
            </div>

            <!-- Barra de Vidas -->
            <div id="healthBarContainer">
                <div id="life1" class="healthSegment"></div>
                <div id="life2" class="healthSegment"></div>
                <div id="life3" class="healthSegment"></div>
            </div>
        </div>
    </main>

    <!-- TUTORIAL -->
    <footer id="bottomUI">
        <div id="tutorialLayer">
            <div id="lumenContainer">
                <img id="lumen" src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/tutorial/lumenHappy.png') }}" alt="Lumen Tutorial">
            </div>
            <div id="tutorialPanel">
                <div id="speakerName">LUMEN</div>
                <img id="tutorialBubble" src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/txtbubbles/mainBox.png') }}" alt="Globo de diálogo tutorial">
                <div id="tutorialContent">
                    <p id="tutorialText"></p>
                    <div id="tutorialButtons">
                        <img id="nextButton" class="uiButton hidden" src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/nextbutton.png') }}" alt="Siguiente">
                        <img id="playButton" class="uiButton hidden" src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/playbutton.png') }}" alt="Jugar">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="effectsLayer"></div>

    <!--- PANTALLA DE GAME OVER --->
    <div id="gameOverScreen">
        <div id="gameOverContainer">
            <h1 id="gameOverTitle"> {{ __('¡BUEN INTENTO!') }}</h1>
            <div id="gameOverMessage">
                <h2 id="messageTitle">-</h2>
                <p id="messageText">.</p>
            </div>
            <!-- Bloque de stats -->
            <div id="gameOverStats">
                <div class="gameOverBlock" id="scoreBlock">
                    <h2 class="statTitle">{{ __('PUNTOS') }}</h2>
                    <span class="statValue" id="finalScore">0</span>
                    <div class="divider"></div>
                </div>
        
                <div class="gameOverBlock" id="recordBlock">
                    <h2 class="statTitle">{{ __('RÉCORD') }}</h2>
                    <span class="statValue" id="finalRecord">450</span>
                    <div class="divider"></div>
                </div>
           
                <div class="gameOverBlock" id="waveBlock">
                    <h2 class="statTitle">{{ __('RONDA') }}</h2>
                    <span class="statValue" id="finalWave">1</span>
                </div>
            </div>
            <div id="gameOverActions">
                <div id="returnButton">
                    <img src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/returnbutton.png') }}" alt="Volver">
                </div>
                <div id="gameOverStar">
                    <img src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/MenuStar.png') }}" alt="Estrella">
                </div>

                <div id="retryButton">
                    <img src="{{ asset('gamesAssets/childs/eco/ASSETS/ui/retrybutton.png') }}" alt="Retry">
                </div>
            </div>
        </div>
        <div id="gameOverLumen">
            <img src="{{ asset('gamesAssets/childs/eco/ASSETS/lumen/tutorial/lumenVictory.png') }}" alt="Lumen GameOver">
        </div>
    </div>
</div>

<script src="{{ asset('gamesAssets/childs/eco/JS/soundManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/eco/JS/ui.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/eco/JS/assistLumen.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/eco/JS/roundManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/eco/JS/memoryManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/eco/JS/tutorial.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/eco/JS/game.js') }}"></script>
</body>
</html>