<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Acceso Infantil') }}</title>
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    @vite(['resources/css/child-auth.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body class="child-pin-body">

    <div class="child-pin-card">
        <h3 class="child-pin-title">{{ __('Acceso a sesión de:') }} {{ $child->name }}</h3>
        <p class="child-pin-desc">{{ __('Introduce el PIN de seguridad de 4 dígitos asignado por el tutor.') }}</p>

        @if ($errors->any())
            <div class="child-pin-alert">
                {{ $errors->first('pin') }}
            </div>
        @endif

        <form action="{{ route('child.login.verify', $child->id) }}" method="POST">
            @csrf
            <div class="child-pin-group">
                <label for="pin" class="child-pin-label">{{ __('PIN de seguridad') }}</label>
                <input type="password" name="pin" maxlength="4" placeholder="••••" required class="child-pin-input">
            </div>

            <button type="submit" class="child-pin-submit">
                {{ __('Entrar a Actividades') }}
            </button>
        </form>

        <div class="child-pin-footer">
            <a href="{{ route('family-panel') }}" class="child-pin-link">← {{ __('Volver al Panel Familiar') }}</a>
        </div>
    </div>

    <x-settings-menu />
</body>
</html>