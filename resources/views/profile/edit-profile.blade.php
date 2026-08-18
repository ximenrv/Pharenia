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
    @vite(['resources/css/navbar.css', 'resources/css/footer.css', 'resources/css/edit-profile.css',  'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body class="profile-body">

    @include('components.navbar')

    <div class="profile-page">
        <div class="profile-container">
            
            <header class="profile-header">
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
                    <div class="profile-avatar-container">
                        <img id="avatar-preview" 
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('/img/profile.png') }}" 
                             alt="Avatar">
                    </div>
                    
                    <label for="avatar-input" class="profile-avatar-label">
                        {{ __('Cambiar Imagen') }}
                    </label>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
                </div>

                <div class="profile-form-group">
                    <label>{{ __('Nombre de Usuario') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="profile-form-group">
                    <label>{{ __('Correo Electrónico') }}</label>
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