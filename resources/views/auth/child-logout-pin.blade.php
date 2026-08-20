<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Cerrar Sesión - Control Parental') }}</title>
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    @vite(['resources/css/child-auth.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background: var(--bg-color, #fff5f5); margin: 0; font-family: "Segoe UI", Tahoma,  Verdana, sans-serif;">

    <div style="width: 100%; max-width: 400px; padding: 30px; background: var(--card-bg, white); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); text-align: center;">
        <h3 style="margin-top: 0; color: #c53030;">{{ __('Control Parental Requerido') }}</h3>
        <p style="font-size: 13px; color: #666;">{{ __('Para salir de esta sesión e ir al Panel Familiar, introduce el PIN de seguridad del tutor.') }}</p>

        @if ($errors->any())
            <div style="color: #c53030; background: #fff5f5; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                {{ $errors->first('pin') }}
            </div>
        @endif

        <form action="{{ route('child.logout.verify') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px; text-align: left;">
                <label for="pin" style="display: block; font-size: 14px; margin-bottom: 5px; font-weight: 500;">{{ __('PIN de seguridad') }}</label>
                <input type="password" name="pin" maxlength="4" placeholder="••••" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 18px; text-align: center; letter-spacing: 5px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; background: #c53030; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                {{ __('Confirmar y Salir al Panel') }}
            </button>
        </form>

        <div style="margin-top: 15px;">
            <a href="/actividades/ninez" style="font-size: 12px; color: #666; text-decoration: none;">← {{ __('Cancelar y volver a actividades') }}</a>
        </div>
    </div>

    <x-settings-menu />
</body>
</html>