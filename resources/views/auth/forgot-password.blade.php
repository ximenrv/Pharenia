@php
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;

    $paso = old('paso', 1);
    $actualizado = false;
    $errores = [];
    $status = null;
    $correoEncontrado = old('correo_hidden', '');

    if (request()->isMethod('post')) {
        $accion = request('accion');

        if ($accion === 'buscar_correo') {
            $correo = trim((string) request('correo'));

            if (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'Por favor ingresa un correo válido.';
                $paso = 1;
            } elseif (! User::where('email', $correo)->exists()) {
                $errores[] = 'No encontramos ninguna cuenta con ese correo.';
                $paso = 1;
            } else {
                $paso = 2;
                $correoEncontrado = $correo;
                $status = 'Correo encontrado. Ahora puedes cambiar tu contraseña.';
            }
        } elseif ($accion === 'cambiar_contrasena') {
            $paso = 2;
            $correoEncontrado = trim((string) request('correo_hidden'));
            $nueva = (string) request('nueva_contrasena');
            $confirmar = (string) request('confirmar_contrasena');

            if (strlen($nueva) < 8) {
                $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
            }
            if (! preg_match('/[0-9]/', $nueva)) {
                $errores[] = 'La contraseña debe contener al menos un número.';
            }
            // Validación separada para minúsculas y mayusculas
            if (! preg_match('/[a-z]/', $nueva) || ! preg_match('/[A-Z]/', $nueva)) {
                $errores[] = 'La contraseña debe contener letras mayúsculas y minúsculas.';
            }
            // Validación para símbolos
            if (! preg_match('/[^a-zA-Z0-9]/', $nueva)) {
                $errores[] = 'La contraseña debe contener al menos un símbolo.';
            }
            if ($nueva !== $confirmar) {
                $errores[] = 'Las contraseñas no coinciden.';
            }

            if (! $errores) {
                User::where('email', $correoEncontrado)->update(['password' => Hash::make($nueva)]);
                $actualizado = true;
                $status = 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
            }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - Recuperar Contraseña</title>
    @vite(['resources/css/forgot-password.css'])
</head>
<body class="auth-body">

    @include('components.loader')

    <div class="auth-logo">
        <a href="{{ url('/') }}" title="Volver al inicio">
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
            
            <h1 class="auth-title">Recuperar<br><span>Contraseña</span></h1>

            <div class="auth-card">
                @if ($errores)
                    <ul class="auth-errors">
                        @foreach ($errores as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($status)
                    <div class="auth-status">
                        {{ $status }}
                    </div>
                @endif

                @if (! $actualizado && $paso === 1)
                    <form action="{{ route('forgot-password') }}" method="POST" class="auth-form">
                        @csrf
                        <input type="hidden" name="accion" value="buscar_correo">

                        <div class="auth-group">
                            <label for="correo">EMAIL</label>
                            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" placeholder="Digite su dirección email" required autofocus>
                        </div>

                        <button type="submit" class="auth-btn-submit">
                            BUSCAR MI CUENTA
                        </button>
                    </form>
                @elseif (! $actualizado)
                    <form action="{{ route('forgot-password') }}" method="POST" class="auth-form">
                        @csrf
                        <input type="hidden" name="accion" value="cambiar_contrasena">
                        <input type="hidden" name="correo_hidden" value="{{ $correoEncontrado }}">

                        <div class="auth-group auth-group--password">
                            <label for="nueva_contrasena">NUEVA CONTRASEÑA</label>
                            <div class="password-wrapper">
                                <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Mín. 8 caracteres (May, min, núm)" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('nueva_contrasena', this)">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bars">
                                    <span class="strength-bar" id="strengthBar1"></span>
                                    <span class="strength-bar" id="strengthBar2"></span>
                                    <span class="strength-bar" id="strengthBar3"></span>
                                </div>
                                <span class="strength-label" id="strengthLabel">Escribe una contraseña</span>
                            </div>
                        </div>

                        <div class="auth-group auth-group--password">
                            <label for="confirmar_contrasena">CONFIRMAR CONTRASEÑA</label>
                            <div class="password-wrapper">
                                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Repita su contraseña" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('confirmar_contrasena', this)">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <span class="auth-error-message" id="confirmError"></span>
                        </div>

                        <button type="submit" class="auth-btn-submit">
                            GUARDAR CONTRASEÑA
                        </button>
                    </form>
                @endif

                <p class="auth-switch">¿Recordaste tu contraseña? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
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

        const nuevaContrasena = document.getElementById('nueva_contrasena');
        const confirmar = document.getElementById('confirmar_contrasena');
        const errorConfirmar = document.getElementById('confirmError');

        if (nuevaContrasena && confirmar) {
            const barras = ['strengthBar1', 'strengthBar2', 'strengthBar3'].map(id => document.getElementById(id));
            const etiqueta = document.getElementById('strengthLabel');

            function validarPasswords() {
                const val1 = nuevaContrasena.value.trim();
                const val2 = confirmar.value.trim();

                if (val2.length > 0) {
                    errorConfirmar.textContent = (val1 === val2) ? '' : 'Las contraseñas no coinciden.';
                } else {
                    errorConfirmar.textContent = '';
                }
            }

            nuevaContrasena.addEventListener('input', function () {
                const valor = this.value;
                let puntaje = 0;

                if (valor.length >= 8) puntaje++;
                if (/[0-9]/.test(valor)) puntaje++;
                if (/[a-zA-Z]/.test(valor)) puntaje++;
                if (/[^a-zA-Z0-9]/.test(valor)) puntaje++;

                barras.forEach(barra => barra.className = 'strength-bar');

                if (valor.length === 0) {
                    etiqueta.textContent = 'Escribe una contraseña';
                } else if (puntaje <= 1) {
                    barras[0].classList.add('debil');
                    etiqueta.textContent = 'Débil';
                } else if (puntaje <= 2) {
                    barras[0].classList.add('media');
                    barras[1].classList.add('media');
                    etiqueta.textContent = 'Media';
                } else {
                    barras.forEach(barra => barra.classList.add('fuerte'));
                    etiqueta.textContent = 'Fuerte';
                }

                validarPasswords();
            });

            confirmar.addEventListener('input', validarPasswords);
        }
    </script>
</body>
</html>

