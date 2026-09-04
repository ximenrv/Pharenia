<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Editar Visitante') }}</title>

    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>

    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/js/navbar.js', 'resources/css/footer.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body>
    @include('components.loader')

    <x-navbar/>

    <div class="family-panel-container">
        <header class="family-panel__header">
            <span class="family-panel__badge">{{ __('Área Restringida') }}</span>
            <h1>{{ __('Editar Visitante') }}</h1>
            <p>{{ __('Actualiza los datos del Visitante General (mayor de 12 años).') }}</p>
        </header>

        @if ($errors->any())
            <div class="pharenia-alert pharenia-alert--error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pharenia-card" style="margin-bottom: 25px; max-width: 560px;">
            <h3 style="margin: 0 0 15px 0; color: #2f4f4f;">{{ __('Editar Visitante') }}</h3>
            <form action="{{ route('admin.visitors.update', $visitor->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" name="name" value="{{ old('name', $visitor->name) }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" name="email" value="{{ old('email', $visitor->email) }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('FECHA DE NACIMIENTO') }}</label><input type="date" name="birthdate" value="{{ old('birthdate', $visitor->birthdate ? \Carbon\Carbon::parse($visitor->birthdate)->toDateString() : '') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('CONTRASEÑA TEMPORAL (opcional)') }}</label><input type="password" name="password" placeholder="{{ __('Dejar en blanco para no cambiar') }}"></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('ACTUALIZAR VISITANTE') }}</button>
            </form>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 30px;">
            <a href="{{ route('admin.visitors') }}" class="pharenia-btn-secondary" style="padding: 10px 18px; text-decoration: none;">← {{ __('Volver') }}</a>
            <a href="{{ route('admin.visitors.show', $visitor->id) }}" class="pharenia-btn-primary" style="padding: 10px 18px; text-decoration: none;">{{ __('Ver') }}</a>
        </div>
    </div>

    <x-settings-menu />
    <x-footer/>
</body>
</html>