<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Sigue la Receta') }} - Pharenia</title>
    <base href="{{ asset('gamesAssets/adults/siguelareceta') }}/">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        /* Botón fijo para salir a Actividades (siempre visible, discreto) */
        .btn-volver-actividades {
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 9999;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(160deg, rgba(31, 51, 88, 0.82), rgba(16, 28, 51, 0.82));
            border: 2px solid rgba(212, 175, 55, 0.7);
            border-radius: 12px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.28);
            opacity: 0.72;
            transition: opacity 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
        }
        .btn-volver-actividades:hover {
            opacity: 1;
            transform: translateY(-1px);
            border-color: #d4af37;
        }
        .btn-volver-actividades .bva-flecha { font-size: 0.9em; }
    </style>
</head>
<body>

    <a href="{{ route('activities.adultez') }}" class="btn-volver-actividades" title="{{ __('Regresar a Actividades') }}">
        <span class="bva-flecha">◀</span> {{ __('Regresar a Actividades') }}
    </a>

    <div id="app" class="app"></div>

    @php
        $locale = app()->getLocale();
        $p1 = lang_path($locale . '.json');
        $p2 = resource_path('lang/' . $locale . '.json');
        $jsonPath = file_exists($p1) ? $p1 : (file_exists($p2) ? $p2 : null);
        $translations = $jsonPath ? json_decode(file_get_contents($jsonPath), true) : [];
    @endphp
    <script>
        window.GAME_MENU_URL = '{{ route('activities.adultez') }}';
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        window.SAVE_RECORD_URL = '{{ route('games.adults.record.update') }}';
        window.translations = @json($translations, JSON_FORCE_OBJECT);
    </script>
    <script type="module" src="js/main.js"></script>
</body>
</html>
