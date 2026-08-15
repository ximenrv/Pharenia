<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - Mi Perfil</title>
    @vite(['resources/css/navbar.css', 'resources/css/footer.css', 'resources/css/edit-profile.css', 'resources/css/app.css'])
</head>
<body class="profile-body">

    @include('components.navbar')

    <div class="profile-page">
        <div class="profile-container">
            
            <header class="profile-header">
                <h1 class="profile-header__title">Mi Perfil</h1>
                <p class="profile-header__subtitle">Administra tu información personal y personaliza tu avatar dentro del mundo de Pharenia.</p>
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
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('/img/default-cat.png') }}" 
                             alt="Avatar">
                    </div>
                    
                    <label for="avatar-input" class="profile-avatar-label">
                        Cambiar Imagen
                    </label>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
                </div>

                <div class="profile-form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="profile-form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <button type="submit" class="profile-submit-btn">
                    GUARDAR CAMBIOS
                </button>
            </form>

        </div>
    </div>

    @include('components.footer')

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