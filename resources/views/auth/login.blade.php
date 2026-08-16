<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - Iniciar Sesión</title>
    @vite(['resources/css/app.css'])
</head>
<body class="auth-body">

    @include('components.loader')

    <div class="auth-logo">
        <a href="{{ url('home') }}" title="Volver al inicio">
            <img src="{{ asset('img/logo.png') }}" alt="Pharenia Logo">
        </a>
    </div>

    <div class="auth-decorations">
        <img src="{{ asset('img/coral-2.png') }}" class="auth-coral coral" alt="Coral Izquierdo">
        <img src="{{ asset('img/coral-3.png') }}" class="auth-coral coral-2" alt="Coral Medio">
        <img src="{{ asset('img/coral.png') }}" class="auth-coral coral-3" alt="Coral Derecho">
    </div>

    <div class="auth-wrapper">
        <main class="auth-main-container">
            
            <h1 class="auth-title">Bienvenido a<br><span>Pharenia</span></h1>

            <div class="auth-card">
                @if ($errors->any())
                    <ul class="auth-errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('login') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="auth-group">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Digite su dirección email" required autofocus>
                    </div>

                    <div class="auth-group auth-group--password">
                        <label for="password">CONTRASEÑA</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Digite su contraseña" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="auth-forgot-container">
                        <a href="{{ route('forgot-password') }}" class="auth-link">¿Olvidó su contraseña?</a>
                    </div>

                    <button type="submit" class="auth-btn-submit">
                        INICIAR SESIÓN
                    </button>
                </form>

                <p class="auth-switch">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
            </div>
        </main>
    </div>

    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const svg = btn.querySelector('svg');
            if (input.type === 'password') {
                input.type = 'text';
                svg.style.opacity = '0.6';
            } else {
                input.type = 'password';
                svg.style.opacity = '1';
            }
        }
    </script>
</body>
</html>