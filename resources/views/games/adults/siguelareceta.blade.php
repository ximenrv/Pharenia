<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sigue la Receta - Pharenia</title>
    <base href="{{ asset('gamesAssets/adults/siguelareceta') }}/">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>

    <div id="app" class="app"></div>

    <script>
        window.GAME_MENU_URL = '{{ route('activities.adultez') }}';
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        window.SAVE_RECORD_URL = '{{ route('games.adults.record.update') }}';
    </script>
    <script type="module" src="js/main.js"></script>
</body>
</html>
