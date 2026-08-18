<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Panel Familiar') }}</title>
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/css/footer.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body>

    <x-navbar/>

    <div class="family-panel-container">
        
        <header class="family-panel__header">
            <span class="family-panel__badge">{{ __('Gestión de Tutoría') }}</span>
            <h1>{{ __('Panel Familiar') }}</h1>
            <p>{{ __('Administra de forma segura los perfiles y supervisa el progreso a tu cargo.') }}</p>
        </header>

        <div class="family-panel__grid">
            
            <div class="pharenia-card">
                <div class="pharenia-card__header">
                    <h3>{{ __('Registrar menor') }}</h3>
                </div>
                <p class="pharenia-card__desc">{{ __('Añade un perfil (menor de 12 años) para habilitar su acceso exclusivo a las actividades.') }}</p>

                <form action="{{ route('family-panel.store') }}" method="POST" class="pharenia-form">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="pharenia-alert-error" style="color: #c53030; background: #fff5f5; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="pharenia-form__group">
                        <label for="name">{{ __('Nombre del menor') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('Ej. Mateo') }}" required>
                    </div>

                    <div class="pharenia-form__group">
                        <label for="birthdate">{{ __('Fecha de nacimiento') }}</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                    </div>

                    <div class="pharenia-form__group">
                        <label for="parent_pin">{{ __('PIN de seguridad (4 dígitos)') }}</label>
                        <input type="password" id="parent_pin" name="parent_pin" maxlength="4" placeholder="••••" required>
                        <small style="color: #616f7a; font-size: 11px;">{{ __('Este PIN se usará para que el menor no pueda salir de su sesión por error.') }}</small>
                    </div>

                    <button type="submit" class="pharenia-btn">{{ __('Crear Perfil') }}</button>
                </form>
            </div>

            <div class="pharenia-card">
                <div class="pharenia-card__header">
                    <h3>{{ __('Menores Registrados') }}</h3>
                </div>
                <p class="pharenia-card__desc">{{ __('Menores vinculados actualmente a tu cuenta de tutor.') }}</p>

                <ul class="pharenia-list">
                    @forelse($children as $child)
                        <li class="pharenia-list__item" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #edf2f7;">
                            <div class="pharenia-list__info">
                                <strong>{{ $child->name }}</strong>
                                <span style="font-size: 12px; color: #616f7a; display: block;">{{ __('Nacimiento:') }} {{ $child->birthdate }}</span>
                            </div>
                            
                            <a href="{{ route('child.login.form', $child->id) }}" style="background-color: #2f4f4f; color: #ffffff; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500;">
                                {{ __('Ingresar') }} →
                            </a>
                        </li>
                    @empty
                        <li class="pharenia-list__item" style="justify-content: center; color: #616f7a; padding: 20px 0;">
                            {{ __('No hay perfiles registrados aún.') }}
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="pharenia-card" style="grid-column: span 2;">
                <div class="pharenia-card__header">
                    <h3>{{ __('Jóvenes a tu cargo') }}</h3>
                </div>
                <p class="pharenia-card__desc">{{ __('Adolescentes que te han seleccionado como su adulto supervisor desde su panel.') }}</p>

                <ul class="pharenia-list">
                    @forelse($supervisedTeens as $teen)
                        <li class="pharenia-list__item" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #edf2f7;">
                            <div class="pharenia-list__info">
                                <strong>{{ $teen->name }}</strong>
                                <span style="font-size: 12px; color: #616f7a; display: block;">{{ __('Correo:') }} {{ $teen->email }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="pharenia-list__item" style="justify-content: center; color: #616f7a; padding: 20px 0;">
                            {{ __('No hay jóvenes vinculados a tu cuenta todavía.') }}
                        </li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>

    <x-footer/>

    <x-settings-menu />
</body>
</html>