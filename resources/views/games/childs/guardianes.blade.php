@php
    $userRecord = auth()->check() 
        ? \App\Models\RecordGamesChild::where('email', auth()->user()->email)->value('record_Guardianes') ?? 0 
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

    <title> {{ __('Guardianes del Planeta') }} - Pharenia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect"href="https://fonts.gstatic.com"crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800&display=swap"rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/guardianes/CSS/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/guardianes/CSS/style.css') }}">
    <link rel="stylesheet" href="{{ asset('gamesAssets/childs/guardianes/CSS/animations.css') }}">
    <script>
      // Ruta base específica
      window.GAME_ASSETS_PATH = "{{ asset('gamesAssets/childs/guardianes/ASSETS') }}";
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
        
        <div id="scorePanel" class="panel">
        <img src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/pointsbox.png') }}" alt="Panel de puntos">
        <div class="panelContent">
        <span class="label">
            {{ __('Puntos') }}
        </span>
        <span id="score">
            0
        </span>
        </div>
        </div>

        <div id="gameStatus">
            <div id="waveBox" class="hudBox">
                <img src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/hudPanel.png') }}" alt="Wave Panel">
                <div class="hudContent">
                    <span class="hudTitle">{{ __('OLA') }}</span>
                    <span id="waveValue" class="hudValue">1</span>
                </div>
            </div>
            <div id="timerBox" class="hudBox">       
                <img src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/hudPanel.png') }}" alt="Timer Panel">
                <div class="hudContent">
                    <span class="hudTitle">⏱</span>
                    <span id="timerValue" class="hudValue">00:50</span>
                </div>
            </div>
        </div>
        
        <div id="recordPanel" class="panel">
        <img src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/pointsbox.png') }}" alt="Panel de puntos">
        <div class="panelContent">
        <span class="label">
            {{ __('Récord') }}
        </span>
        <span id="record">
            0
        </span>
        </div>
        </div>

 <div id="assistant">
    <div id="assistantAvatar">
        <img id="assistantBubble" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/lumen/assistent/LumenBubbleAssist.png') }}">
        <img id="assistantHead" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/lumen/assistent/lumenBase.png') }}">
    </div>
    <div id="assistantText">
        <img id="SpeechAssistBubble" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/lumen/txtbubbles/assistText.png') }}">
        <p id="assistantDialogue">!</p>
    </div>
 </div>

    </header>
    <!-- ================ MAPA ================ -->
    <main id="world">
        <img id="background" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/background/fondoPlaya.png') }}" alt="">
        <div id="ocean">
        <svg id="waveBack" viewBox="0 0 1920 220" preserveAspectRatio="none">
            <path id="waveBackPath" fill="#38AEEB" d="M0 90C120 60 220 120 340 90
            S560 120 700 90S920 60 1080 90S1300 120 1460 90S1700 60 1920 90L1920 220L0 220Z"/>
        </svg>

        <svg id="waveFoam" viewBox="0 0 1920 220" preserveAspectRatio="none">
        <path id="waveFoamPath" fill="White" opacity=".55" d="M0 90C120 60 220 120 340 90
            S560 120 700 90S920 60 1080 90S1300 120 1460 90S1700 60 1920 90L1920 220L0 220Z"/>
        </svg>

        <filter id="foamNoise">
            <feTurbulence type="fractalNoise" baseFrequency="0.7" numOctaves="2"/>
        </filter>

        <svg id="waveFront" viewBox="0 0 1920 220" preserveAspectRatio="none">
            <path id="waveFrontPath" fill="#69D9FF" opacity=".65" d="M0 110C160 80 280 140 
            420 110S700 150 900 110S1200 70 1450 110S1750 140 1920 110L1920 220L0 220Z"/>
        </svg>
        </div>
        <div id="trashLayer"></div>
        <div id="effectsLayer"></div>
        <div id="binLayer">
            <div class="binSlot metalSlot">
             <img class="bin" id="metalBin" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/trashcans/trashMetal.png') }}" data-type="metal">
            </div>
            <div class="binSlot glassSlot">
             <img class="bin" id="glassBin" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/trashcans/trashGlass.png') }}" data-type="glass">
            </div>
            <div class="binSlot plasticSlot">
             <img class="bin" id="plasticBin" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/trashcans/trashPlastic.png') }}" data-type="plastic">
            </div>
        </div>
    </main>
    <!-- ============= FOOTER ============= -->
    <footer id="bottomUI">
        <div id="tutorialLayer">
            <div id="lumenContainer">
                <img id="lumen" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/lumen/tutorial/lumenHappy.png') }}">
            </div>
        <div id="tutorialPanel">
            <div id="speakerName">LUMEN</div>
            <img id="tutorialBubble" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/lumen/txtbubbles/mainBox.png') }}">
        <div id="tutorialContent">
            <p id="tutorialText"></p>
            <div id="tutorialButtons">
                <img id="nextButton" class="uiButton hidden" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/nextbutton.png') }}">
                <img id="playButton" class="uiButton hidden" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/playbutton.png') }}">
            </div>
        </div>
        </div>
        </div>
    </footer>

    <div id="gameOverScreen">
        <h1 id="gameOverTitle">{{ __('¡SE ACABÓ EL TIEMPO!') }}</h1>
      <div id="gameOverContainer">
        <img id="gameOverPanel" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/TimeUpMenu.png') }}">
        <img id="returnButton" src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/returnbutton.png') }}">
        <div id="gameOverMessage">
            <h2 id="messageTitle">-¡-</h2>
            <p id="messageText">.</p>
        </div>
        <div id="gameOverStats">
            <div class="gameOverBlock" id="scoreBlock">
                <h2 class="statTitle">{{ __('PUNTOS') }}</h2>
                <span class="statValue" id="finalScore">0</span>
                <div class="divider"></div>
            </div>
            <div class="gameOverBlock" id="recordBlock">
                <h2 class="statTitle">{{ __('RÉCORD') }}</h2>
                <span class="statValue" id="finalRecord">0</span>
                <div class="divider"></div>
            </div>
            <div class="gameOverBlock" id="waveBlock">
                <h2 class="statTitle">{{ __('OLA') }}</h2>
                <span class="statValue" id="finalWave">1</span>
            </div>
        </div>
        <div id="gameOverLumen">
            <img src="{{ asset('gamesAssets/childs/guardianes/ASSETS/lumen/tutorial/lumenVictory.png') }}" alt="Lumen">
        </div>
        <div id="retryButton">
            <img src="{{ asset('gamesAssets/childs/guardianes/ASSETS/ui/retrybutton.png') }}" alt="Retry">
        </div>
      </div>
    </div>
</div>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/soundManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/ui.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/roundManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/assistLumen.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/trash.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/wave.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/game.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/dragManager.js') }}"></script>
<script src="{{ asset('gamesAssets/childs/guardianes/JS/tutorial.js') }}"></script>
</body>
</html>