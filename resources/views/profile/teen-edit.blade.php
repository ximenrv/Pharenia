<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Editar Joven (Teen)') }}</title>

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
        <a href="{{ route('admin.teens') }}" class="family-panel__back-btn" style="margin-bottom: 20px;">← {{ __('Regresar') }}</a>

        <header class="family-panel__header">
            <span class="family-panel__badge">{{ __('Área Restringida') }}</span>
            <h1>{{ __('Editar Joven (Teen)') }}</h1>
            <p>{{ __('Actualiza los datos del Joven / Adolescente (13-17 años).') }}</p>
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
            <h3 style="margin: 0 0 15px 0; color: #2f4f4f;">{{ __('Editar Joven (Teen)') }}</h3>
            <form action="{{ route('admin.users.update', $teen->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" name="name" value="{{ old('name', $teen->name) }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" name="email" value="{{ old('email', $teen->email) }}" required></div>
                <div class="pharenia-form-group">
                    <label>{{ __('SUPERVISOR ID') }}</label>
                    <input type="number" name="supervisor_id" value="{{ old('supervisor_id', $teen->supervisor_id) }}" list="allies-list" required>
                    <datalist id="allies-list">
                        @foreach($allies as $ally)
                            <option value="{{ $ally->id }}">{{ $ally->name }}</option>
                        @endforeach
                    </datalist>
                </div>
                <div class="pharenia-form-group"><label>{{ __('Rol (guardado en la base de datos)') }}</label><input type="text" value="{{ $teen->role }}" disabled></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('ACTUALIZAR JOVEN') }}</button>
            </form>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 30px;">
            <a href="{{ route('admin.teens') }}" class="pharenia-btn-secondary" style="padding: 10px 18px; text-decoration: none;">← {{ __('Regresar') }}</a>
            <a href="{{ route('admin.teens.show', $teen->id) }}" class="pharenia-btn-primary" style="padding: 10px 18px; text-decoration: none;">{{ __('Ver') }}</a>
        </div>
    </div>

    <x-settings-menu />
    <x-footer/>
</body>
</html>