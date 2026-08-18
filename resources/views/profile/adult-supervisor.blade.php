<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Adulto Supervisor') }}</title>
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/css/footer.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body class="supervisor-body">
    <x-navbar/>

    <div class="family-panel-container">
        <header class="family-panel__header">
            <span class="family-panel__badge">{{ __('Gestión de Acompañamiento') }}</span>
            <h1>{{ __('Tu Adulto Supervisor') }}</h1>
            <p>{{ __('Conecta tu cuenta con un tutor o adulto responsable para supervisar tu progreso de forma segura.') }}</p>
        </header>

        <div class="family-panel__grid">
            
            <!-- TARJETA 1: Supervisor Actual -->
            <div class="pharenia-card">
                <div class="pharenia-card__header">
                    <h3>{{ __('Supervisor Vinculado') }}</h3>
                </div>
                <p class="pharenia-card__desc">{{ __('Este es el adulto actualmente a cargo de supervisar tu cuenta en Pharenia.') }}</p>

                <ul class="pharenia-list">
                    @if($supervisor)
                        <li class="pharenia-list__item supervisor-item">
                            <div class="pharenia-list__info">
                                <strong class="supervisor-name">{{ $supervisor->name }}</strong>
                                <span class="supervisor-email">{{ __('Correo:') }} {{ $supervisor->email }}</span>
                            </div>
                            <span class="supervisor-badge">{{ __('Vinculado') }}</span>
                        </li>
                    @else
                        <li class="pharenia-list__item supervisor-empty">
                            {{ __('No tienes ningún adulto supervisor vinculado todavía. Utiliza el formulario de al lado para registrar uno.') }}
                        </li>
                    @endif
                </ul>
            </div>

            <!-- TARJETA 2: Formulario para Vincular / Cambiar Supervisor -->
            <div class="pharenia-card">
                <div class="pharenia-card__header">
                    <h3>{{ __('Vincular Tutor') }}</h3>
                </div>
                <p class="pharenia-card__desc">{{ __('Ingresa el correo electrónico registrado en la plataforma por tu adulto responsable.') }}</p>

                <form action="{{ route('supervisor.store') }}" method="POST" class="pharenia-form">
                    @csrf

                    @if ($errors->any())
                        <div class="supervisor-alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="supervisor-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="pharenia-form__group">
                        <label for="supervisor_email">{{ __('Correo del adulto supervisor') }}</label>
                        <input type="email" id="supervisor_email" name="supervisor_email" value="{{ old('supervisor_email') }}" placeholder="{{ __('tutor@correo.com') }}" required>
                        <small class="supervisor-help-text">{{ __('El correo debe pertenecer a un usuario registrado con rol de Tutor / Aliado.') }}</small>
                    </div>

                    <button type="submit" class="pharenia-btn">{{ __('Vincular Supervisor') }}</button>
                </form>
            </div>

        </div>
    </div>

    <x-footer/>
    <x-settings-menu />
</body>
</html>