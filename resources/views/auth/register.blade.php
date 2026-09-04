<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Registro') }}</title>
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    @vite(['resources/css/auth.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body class="auth-body">


    <div class="auth-logo">
        <a href="{{ url('home') }}" title="{{ __('Volver al inicio') }}">
            <img src="{{ asset('img/logo.png') }}" alt="{{ __('Pharenia Logo') }}">
        </a>
    </div>

    <div class="auth-decorations">
        <img src="{{ asset('img/coral-2.png') }}" class="auth-coral coral" alt="Coral Izquierdo">
        <img src="{{ asset('img/coral-3.png') }}" class="auth-coral coral-2" alt="Coral Medio">
        <img src="{{ asset('img/coral.png') }}" class="auth-coral coral-3" alt="Coral Derecho">
    </div>

    <div class="auth-wrapper">
        <main class="auth-main-container">
            <h1 class="auth-title">{{ __('Crea tu cuenta en') }}<br><span>Pharenia</span></h1>

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
                        <label for="name">{{ __('NOMBRE COMPLETO') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('Digite su nombre completo') }}"  autofocus>
                    </div>

                    <div class="auth-group">
                        <label for="email">{{ __('EMAIL') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Digite su dirección email') }}" >
                    </div>

                    <div class="auth-group">
                        <label for="birthdate">{{ __('FECHA DE NACIMIENTO') }}</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" >
                    </div>

                    <div class="auth-group">
                        <label for="role">{{ __('¿QUÉ PERFIL DESCRIBE TU OBJETIVO?') }}</label>
                        <select id="role" name="role"  style="width: 100%; padding: 7px; border: 1px solid #cbd5e0; border-radius: 8px; background-color: #fff; font-size: 14px; color: #2f4f4f;">
                            <option value="" disabled selected>{{ __('Selecciona tu perfil...') }}</option>
                            <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>{{ __('Visitante General') }}</option>
                            <option value="adult_tea" {{ old('role') == 'adult_tea' ? 'selected' : '' }}>{{ __('Adulto (TEA)') }}</option>
                            <option value="ally_no_tea" {{ old('role') == 'ally_no_tea' ? 'selected' : '' }}>{{ __('Adulto Padre/Madre (NO TEA)') }}</option>
                            <option value="teen" {{ old('role') == 'teen' ? 'selected' : '' }}>{{ __('Adolescente (TEA)') }}</option>
                        </select>

                        <!-- Aviso dinámico de edad según el rol seleccionado -->
                        <p class="auth-error-message auth-error-message--dynamic" id="visitorAgeError" aria-live="polite">{{ __('auth.visitor_under_12') }}</p>
                    </div>

                    <div class="auth-group auth-group--password">
                        <label for="password">{{ __('CONTRASEÑA') }}</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="{{ __('Mín. 8 caracteres (May, min, núm, símbolo)') }}" >
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
                            <span class="strength-label" id="strengthLabel">{{ __('Escribe una contraseña') }}</span>
                        </div>

                        <!-- Aviso dinámico: requisitos de contraseña que faltan -->
                        <p class="auth-error-message auth-error-message--dynamic password-hint" id="passwordHint" aria-live="polite"></p>
                    </div>

                    <div class="auth-group auth-group--password">
                        <label for="password_confirmation">{{ __('CONFIRMAR CONTRASEÑA') }}</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('Repita su contraseña') }}" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <span class="auth-error-message" id="confirmError"></span>
                    </div>
                    <div class="auth-group" style="display: flex; flex-direction: row !important; align-items: center; justify-content: flex-end; gap: 10px; margin-bottom: 20px;">
                        <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}  style="width: 20px; height: 20px; cursor: pointer; accent-color: #2f4f4f; margin: 0;">
                        <label for="terms" style="font-size: 13px; margin-bottom: 0; cursor: pointer;">
                            {{ __('Acepto los') }} <a href="#" onclick="openTermsModal(event)" style="text-decoration: underline;">{{ __('Términos y Condiciones') }}</a>
                        </label>
                    </div>

                    <button type="submit" class="auth-btn-submit">
                        {{ __('REGISTRARSE') }}
                    </button>
                </form>

                <p class="auth-switch">{{ __('¿Ya tienes cuenta?') }} <a href="{{ route('login') }}">{{ __('Inicia sesión aquí') }}</a></p>
            </div>
        </main>
    </div>

    <x-settings-menu />

    <div id="termsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: var(--card-bg, #ffffff); padding: 25px; border-radius: 12px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.3); text-align: left; color: #2f4f4f;">
            <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px;">{{ __('Términos y Condiciones de Pharenia') }}</h3>
            <p style="font-size: 14px; line-height: 1.5; margin-bottom: 15px;">
                {{ __('Bienvenido a Pharenia. Al registrarte en nuestra plataforma, aceptas hacer un uso responsable de las herramientas de gestión y control parental. Nos comprometemos a proteger la privacidad de tus datos y la de los menores a tu cargo.') }}
            </p>
            <button type="button" onclick="closeTermsModal()" style="background: #2f4f4f; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; display: block; width: 100%;">
                {{ __('Entendido y Cerrar') }}
            </button>
        </div>
    </div>

    <script>
        function openTermsModal(event) {
            event.preventDefault();
            document.getElementById('termsModal').style.display = 'flex';
        }

        function closeTermsModal() {
            document.getElementById('termsModal').style.display = 'none';
        }
    </script>

    @php
        /** Mensajes de validación en tiempo real traducidos (lang/es.json, lang/en.json) */
        $registerMessages = [
            'wordAnd' => __('auth.word_and'),
            'passPrefix' => __('auth.pass_need_prefix'),
            'passOk' => __('auth.pass_ok'),
            'passReqLength' => __('auth.pass_req_length'),
            'passReqUpper' => __('auth.pass_req_upper'),
            'passReqLower' => __('auth.pass_req_lower'),
            'passReqNumber' => __('auth.pass_req_number'),
            'passReqSymbol' => __('auth.pass_req_symbol'),
            'passReqNoSpace' => __('auth.pass_req_no_space'),
            'roleVisitor' => __('auth.visitor_under_12'),
            'roleAdultTea' => __('auth.role_age_adult_tea'),
            'roleAlly' => __('auth.role_age_ally'),
            'roleTeen' => __('auth.role_age_teen'),
            'teenRoleRequired' => __('auth.teen_role_required'),
            'adultNoTeen' => __('auth.adult_no_teen'),
        ];
    @endphp

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

    // Mensajes de validación traducidos en el servidor (ver bloque @php superior)
    const registerMessages = @json($registerMessages);

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const errorConfirmar = document.getElementById('confirmError');
    const passwordHint = document.getElementById('passwordHint');
    const roleSelect = document.getElementById('role');
    const birthdateInput = document.getElementById('birthdate');
    const ageMessage = document.getElementById('visitorAgeError');
    const registerForm = document.querySelector('.auth-form');

    // Calcula la edad exacta (años cumplidos) a partir de la fecha de nacimiento.
    // Se parsea Y-M-D manualmente para evitar desfases por zona horaria.
    function calcularEdad(valorFecha) {
        const partes = valorFecha.split('-').map(Number);
        if (partes.length !== 3 || partes.some(parte => isNaN(parte))) return null;

        const nacimiento = new Date(partes[0], partes[1] - 1, partes[2]);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const diferenciaMeses = hoy.getMonth() - nacimiento.getMonth();

        if (diferenciaMeses < 0 || (diferenciaMeses === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }

        return edad;
    }

    // Devuelve el aviso para la combinación rol/edad, o null si cumple los
    // requisitos del perfil (mismas reglas que valida el backend).
    function getMensajeEdadPorRol() {
        if (!roleSelect.value || !birthdateInput.value) return null;

        const edad = calcularEdad(birthdateInput.value);
        if (edad === null) return null;

        switch (roleSelect.value) {
            case 'visitor': // Visitante General: más de 12 años cumplidos
                if (edad <= 12) return registerMessages.roleVisitor;
                return (edad <= 17) ? registerMessages.teenRoleRequired : null;
            case 'adult_tea': // Adultos: al menos 18 años cumplidos
                return (edad >= 18) ? null : registerMessages.roleAdultTea;
            case 'ally_no_tea':
                return (edad >= 18) ? null : registerMessages.roleAlly;
            case 'teen': // Adolescente: entre 13 y 17 años
                if (edad >= 13 && edad <= 17) return null;
                return (edad >= 18) ? registerMessages.adultNoTeen : registerMessages.roleTeen;
            default:
                return null;
        }
    }

    // Muestra u oculta el aviso justo debajo del campo de fecha/rol
    function actualizarAvisoEdad() {
        const mensaje = getMensajeEdadPorRol();

        if (ageMessage) {
            ageMessage.textContent = mensaje || '';
            ageMessage.classList.toggle('is-visible', Boolean(mensaje));
        }

        return !mensaje;
    }

    if (roleSelect && birthdateInput && ageMessage && registerForm) {
        roleSelect.addEventListener('change', actualizarAvisoEdad);
        birthdateInput.addEventListener('change', actualizarAvisoEdad);
        birthdateInput.addEventListener('input', actualizarAvisoEdad);

        // Estado inicial (p. ej. cuando el formulario vuelve con old())
        actualizarAvisoEdad();

        registerForm.addEventListener('submit', function (event) {
            if (!actualizarAvisoEdad()) {
                event.preventDefault();
                ageMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    if (passwordInput && confirmInput) {
        const barras = ['strengthBar1', 'strengthBar2', 'strengthBar3'].map(id => document.getElementById(id));
        const etiqueta = document.getElementById('strengthLabel');

        // Une los requisitos faltantes respetando la gramática: "a", "a y b", "a, b y c"
        function unirRequisitos(requisitos) {
            if (requisitos.length === 1) return requisitos[0];

            const ultimo = requisitos[requisitos.length - 1];
            return requisitos.slice(0, -1).join(', ') + ' ' + registerMessages.wordAnd + ' ' + ultimo;
        }

        // Lista de requisitos que aún faltan (vacía = contraseña válida)
        function getRequisitosFaltantes(valor) {
            const faltantes = [];

            if (valor.length < 8) faltantes.push(registerMessages.passReqLength);
            if (!/[A-Z]/.test(valor)) faltantes.push(registerMessages.passReqUpper);
            if (!/[a-z]/.test(valor)) faltantes.push(registerMessages.passReqLower);
            if (!/[0-9]/.test(valor)) faltantes.push(registerMessages.passReqNumber);
            if (!/[^a-zA-Z0-9]/.test(valor)) faltantes.push(registerMessages.passReqSymbol);
            if (/\s/.test(valor)) faltantes.push(registerMessages.passReqNoSpace);

            return faltantes;
        }

        // Texto dinámico que guía al usuario mientras escribe la contraseña
        function actualizarAvisoPassword() {
            if (!passwordHint) return;

            const valor = passwordInput.value;

            if (valor.length === 0) {
                passwordHint.textContent = '';
                passwordHint.classList.remove('is-visible', 'password-hint--ok');
                return;
            }

            const faltantes = getRequisitosFaltantes(valor);

            if (faltantes.length > 0) {
                passwordHint.textContent = registerMessages.passPrefix + ' ' + unirRequisitos(faltantes) + '.';
                passwordHint.classList.add('is-visible');
                passwordHint.classList.remove('password-hint--ok');
            } else {
                passwordHint.textContent = registerMessages.passOk;
                passwordHint.classList.add('is-visible', 'password-hint--ok');
            }
        }

        function validarPasswords() {
            const val1 = passwordInput.value.trim();
            const val2 = confirmInput.value.trim();

            if (!errorConfirmar) return;

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
            if (/[A-Z]/.test(valor)) puntaje++;
            if (/[a-z]/.test(valor)) puntaje++;
            if (/[0-9]/.test(valor)) puntaje++;
            if (/[^a-zA-Z0-9]/.test(valor)) puntaje++;

            barras.forEach(barra => barra.className = 'strength-bar');

            if (valor.length === 0) {
                etiqueta.textContent = 'Escribe una contraseña';
            } else if (puntaje <= 2) {
                barras[0].classList.add('debil');
                etiqueta.textContent = 'Débil';
            } else if (puntaje <= 4) {
                barras[0].classList.add('media');
                barras[1].classList.add('media');
                etiqueta.textContent = 'Media';
            } else {
                barras.forEach(barra => barra.classList.add('fuerte'));
                etiqueta.textContent = 'Fuerte';
            }

            actualizarAvisoPassword();
            validarPasswords();
        });

        confirmInput.addEventListener('input', validarPasswords);

        // Restaura el aviso si el navegador autocompleta el campo al cargar
        if (passwordInput.value.length > 0) {
            passwordInput.dispatchEvent(new Event('input'));
        }
    }

    // (La validación de edad por rol ahora vive junto a la validación de la
    // contraseña, más arriba: ver getMensajeEdadPorRol / actualizarAvisoEdad.)
</script>

</body>
</html>