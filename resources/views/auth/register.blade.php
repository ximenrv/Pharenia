<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - Registro</title>
    @vite(['resources/css/app.css'])
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
            <h1 class="auth-title">Crea tu cuenta en<br><span>Pharenia</span></h1>

            <div class="auth-card">
                @if ($errors->any())
                    <ul class="auth-errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('register') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="auth-group">
                        <label for="name">NOMBRE COMPLETO</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Digite su nombre completo" required autofocus>
                    </div>

                    <div class="auth-group">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Digite su dirección email" required>
                    </div>

                    <div class="auth-group">
                        <label for="birthdate">FECHA DE NACIMIENTO</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                    </div>

                    <div class="auth-group">
                        <label for="role">¿QUÉ PERFIL DESCRIBE TU OBJETIVO?</label>
                        <select id="role" name="role" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 8px; background-color: #fff; font-size: 14px; color: #2f4f4f;">
                            <option value="" disabled selected>Selecciona tu perfil...</option>
                            <option value="adult_tea" {{ old('role') == 'adult_tea' ? 'selected' : '' }}>Adulto Autogestor (TEA)</option>
                            <option value="ally_no_tea" {{ old('role') == 'ally_no_tea' ? 'selected' : '' }}>Tutor / Aliado (+18)</option>
                            <option value="teen" {{ old('role') == 'teen' ? 'selected' : '' }}>Joven / Adolescente (13-17 años)</option>
                        </select>
                    </div>

                    <div class="auth-group auth-group--password">
                        <label for="password">CONTRASEÑA</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Mín. 8 caracteres (May, min, núm, símbolo)" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
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
                        <label for="password_confirmation">CONFIRMAR CONTRASEÑA</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repita su contraseña" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <span class="auth-error-message" id="confirmError"></span>
                    </div>

                    <button type="submit" class="auth-btn-submit">
                        REGISTRARSE
                    </button>
                </form>

                <p class="auth-switch">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
            </div>
        </main>
    </div>

    <script>
    // Función para manejar el diseño activo de las tarjetas de rol
    function seleccionarRol(elementoCard, valor) {
        document.querySelectorAll('.role-option-card').forEach(c => c.classList.remove('active'));
        elementoCard.classList.add('active');
        const radio = elementoCard.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
        }
    }

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

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const errorConfirmar = document.getElementById('confirmError');

    if (passwordInput && confirmInput) {
        const barras = ['strengthBar1', 'strengthBar2', 'strengthBar3'].map(id => document.getElementById(id));
        const etiqueta = document.getElementById('strengthLabel');

        function validarPasswords() {
            const val1 = passwordInput.value.trim();
            const val2 = confirmInput.value.trim();

            if (val2.length > 0) {
                errorConfirmar.textContent = (val1 === val2) ? '' : 'Las contraseñas no coinciden.';
            } else {
                errorConfirmar.textContent = '';
            }
        }

        passwordInput.addEventListener('input', function () {
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

        confirmInput.addEventListener('input', validarPasswords);
    }

    
</script>
</body>
</html>