<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Mi Perfil') }}</title>
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    @vite(['resources/css/navbar.css', 'resources/js/navbar.js', 'resources/css/footer.css', 'resources/css/edit-profile.css',  'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body class="profile-body">

    @include('components.navbar')

    <div class="profile-page">
        <div class="profile-container">
            
            <header class="profile-header">
                <span class="profile-header__badge">{{ __('Configuración de cuenta') }}</span>
                <h1 class="profile-header__title">{{ __('Mi Perfil') }}</h1>
                <p class="profile-header__subtitle">{{ __('Administra tu información personal y personaliza tu avatar dentro del mundo de Pharenia.') }}</p>
            </header>

            @if(session('success'))
                <div class="profile-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-card">
                @csrf
                @method('PUT')

                <div class="profile-avatar-wrapper">
                    <label for="avatar-input" class="profile-avatar-container" title="{{ __('Cambiar imagen') }}">
                        <img id="avatar-preview" 
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('/img/profile.png') }}" 
                             alt="Avatar">
                        <span class="profile-avatar-edit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </span>
                    </label>
                    
                    <label for="avatar-input" class="profile-avatar-label">
                        {{ __('Cambiar Imagen') }}
                    </label>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
                </div>

                <div class="profile-form-group">
                    <label>
                        <svg class="profile-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ __('Nombre de Usuario') }}
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="profile-form-group">
                    <label>
                        <svg class="profile-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        {{ __('Correo Electrónico') }}
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <button type="submit" class="profile-submit-btn">
                    {{ __('GUARDAR CAMBIOS') }}
                </button>
            </form>

        </div>
    </div>

    @include('components.footer')
    <x-settings-menu />
    
    <script>
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('avatar-preview').src = event.target.result;
            }
            if(e.target.files[0]) {
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>

    
</body>
</html>