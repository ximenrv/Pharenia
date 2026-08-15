<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adulto Supervisor - Pharenia</title>
    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/css/footer.css'])
</head>
<body>
    <x-navbar/>

    <div class="family-panel-container">
        <header class="family-panel__header">
            <span class="family-panel__badge">Gestión de Acompañamiento</span>
            <h1>Tu Adulto Supervisor</h1>
            <p>Conecta tu cuenta con un tutor o adulto responsable para supervisar tu progreso de forma segura.</p>
        </header>

        <div class="family-panel__grid">
            
            <!-- TARJETA 1: Supervisor Actual -->
            <div class="pharenia-card">
                <div class="pharenia-card__header">
                    <h3>Supervisor Vinculado</h3>
                </div>
                <p class="pharenia-card__desc">Este es el adulto actualmente a cargo de supervisar tu cuenta en Pharenia.</p>

                <ul class="pharenia-list">
                    @if($supervisor)
                        <li class="pharenia-list__item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 18px; border: 1px solid #e3dec9; border-radius: 10px; background: #ffffff;">
                            <div class="pharenia-list__info">
                                <strong style="color: #2f4f4f; font-size: 15px;">{{ $supervisor->name }}</strong>
                                <span style="font-size: 12px; color: #616f7a; display: block; margin-top: 2px;">Correo: {{ $supervisor->email }}</span>
                            </div>
                            <span class="pharenia-badge-active" style="background-color: #e2ebd8; color: #2f4f32; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 12px;">Vinculado</span>
                        </li>
                    @else
                        <li class="pharenia-list__item" style="justify-content: center; color: #616f7a; padding: 25px 0; border: 1px solid #e3dec9; border-radius: 10px; background: #ffffff; text-align: center;">
                            No tienes ningún adulto supervisor vinculado todavía. Utiliza el formulario de al lado para registrar uno.
                        </li>
                    @endif
                </ul>
            </div>

            <!-- TARJETA 2: Formulario para Vincular / Cambiar Supervisor -->
            <div class="pharenia-card">
                <div class="pharenia-card__header">
                    <h3>Vincular Tutor</h3>
                </div>
                <p class="pharenia-card__desc">Ingresa el correo electrónico registrado en la plataforma por tu adulto responsable.</p>

                <form action="{{ route('supervisor.store') }}" method="POST" class="pharenia-form">
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

                    @if (session('success'))
                        <div class="pharenia-alert-success" style="color: #2f4f32; background: #e2ebd8; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="pharenia-form__group">
                        <label for="supervisor_email">Correo del adulto supervisor</label>
                        <input type="email" id="supervisor_email" name="supervisor_email" value="{{ old('supervisor_email') }}" placeholder="tutor@correo.com" required>
                        <small style="color: #616f7a; font-size: 11px; display: block; margin-top: 6px;">El correo debe pertenecer a un usuario registrado con rol de Tutor / Aliado.</small>
                    </div>

                    <button type="submit" class="pharenia-btn">Vincular Supervisor</button>
                </form>
            </div>

        </div>
    </div>

    <x-footer/>
</body>
</html>-