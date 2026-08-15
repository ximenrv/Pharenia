<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafíos de la Juventud - Pharenia</title>
    @vite(['resources/css/stages.css', 'resources/css/navbar.css', 'resources/css/footer.css'])
</head>
<body style="background-color: #fbf7e3; margin: 0; padding: 0;">

    @if(session()->has('active_child_id'))
        <div style="max-width: 600px; margin: 40px auto -70px auto; padding: 30px; border-radius: 12px; text-align: center; border: 1px solid #fef08a;">
            <h1 style="color: #bfa12b; font-size: 28px; font-weight: bold; margin: 0 0 8px 0;">
                ¡Hola, {{ session('active_child_name') }}!
            </h1>
            <p style="color: #4a5568; font-size: 15px; margin: 0 0 24px 0;">
                Bienvenido a tu espacio seguro de juventud. ¡Disfruta tus actividades!
            </p>
            <a href="{{ route('child.logout.form') }}" style="display: inline-flex; align-items: center; justify-content: center; background: #c53030; color: white; text-decoration: none; padding: 10px 22px; border-radius: 8px; font-weight: bold; font-size: 14px;">
                Cerrar sesión
            </a>
        </div>
    @else
        @include('components.navbar')
    @endif

    <div class="stage-page">
        <div class="stage-container">
            <header class="stage-header" style="padding-top: 20px;">
                <span class="stage-header__subtitle" style="color: #bfa12b">Nivel 2 — Habilidades sociales y rutina</span>
                <h1 class="stage-header__title">Desafíos de la Juventud</h1>
                <p class="stage-header__intro">Selecciona cualquiera de nuestros tres módulos interactivos para comenzar a jugar y poner a prueba tus habilidades.</p>
            </header>

            <div class="games-grid">
                <a href="/juegos/juventud/rutina" class="game-card">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-juve-1.png') }}" alt="Planificador Diario" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #bfa12b;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Planificador Diario</h3>
                        <p class="game-card__description">Organiza las tareas de la semana de forma eficiente.</p>
                    </div>
                </a>

                <a href="/juegos/juventud/social" class="game-card">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-juve-2.png') }}" alt="Conversaciones en el Aula" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #bfa12b;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Conversaciones en el Aula</h3>
                        <p class="game-card__description">Elige las mejores respuestas para interactuar con amigos.</p>
                    </div>
                </a>

                <a href="/juegos/juventud/mapa" class="game-card">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-juve-3.png') }}" alt="Descifra el Mapa" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #bfa12b;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Descifra el Mapa</h3>
                        <p class="game-card__description">Juego de lógica para orientarse en la ciudad.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @include('components.footer')
</body>
</html>