<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Infantil - Pharenia</title>
    @vite(['resources/css/child-auth.css'])
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background: #f8fafc; margin: 0; font-family: sans-serif;">

    <div style="width: 100%; max-width: 400px; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2f4f4f;">Acceso a sesión de: {{ $child->name }}</h3>
        <p style="font-size: 13px; color: #666;">Introduce el PIN de seguridad de 4 dígitos asignado por el tutor.</p>

        @if ($errors->any())
            <div style="color: #c53030; background: #fff5f5; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                {{ $errors->first('pin') }}
            </div>
        @endif

        <form action="{{ route('child.login.verify', $child->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label for="pin" style="display: block; font-size: 14px; margin-bottom: 5px; font-weight: 500;">PIN de seguridad</label>
                <input type="password" name="pin" maxlength="4" placeholder="••••" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 18px; text-align: center; letter-spacing: 5px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; background: #2f4f4f; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                Entrar a Actividades
            </button>
        </form>

        <div style="text-align: center; margin-top: 15px;">
            <a href="{{ route('family-panel') }}" style="font-size: 12px; color: #666; text-decoration: none;">← Volver al Panel Familiar</a>
        </div>
    </div>

</body>
</html>