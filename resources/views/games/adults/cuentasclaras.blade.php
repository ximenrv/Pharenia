<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Cuentas Claras') }} - Pharenia</title>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('gamesAssets/adults/cuentasclaras/CSS/style.css') }}">

    {{-- Aplica el tema guardado (claro/oscuro) --}}
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    @php
        $locale = app()->getLocale();
        $p1 = lang_path($locale . '.json');
        $p2 = resource_path('lang/' . $locale . '.json');
        $jsonPath = file_exists($p1) ? $p1 : (file_exists($p2) ? $p2 : null);
        $translations = $jsonPath ? json_decode(file_get_contents($jsonPath), true) : [];
    @endphp
    <script>
        window.GAME_MENU_URL   = '{{ route('activities.adultez') }}';
        window.CSRF_TOKEN      = '{{ csrf_token() }}';
        window.SAVE_RECORD_URL = '{{ route('games.adults.record.update') }}';
        window.GAME_ASSETS     = { lumen: '{{ asset('img/lumen/lumen-01.png') }}' };
        window.translations    = @json($translations, JSON_FORCE_OBJECT);
    </script>
</head>
<body>

    <a href="{{ route('activities.adultez') }}" class="cc-back" title="{{ __('Regresar a Actividades') }}">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        {{ __('Regresar a Actividades') }}
    </a>

    <div class="cc-app" id="cc-app">
        {{-- El contenido (intro, juego, resultado) se dibuja desde game.js --}}
    </div>

    <script src="{{ asset('gamesAssets/adults/cuentasclaras/JS/game.js') }}"></script>
</body>
</html>
