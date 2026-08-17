<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estación de la Adultez - Pharenia</title>
    @vite(['resources/css/stages.css', 'resources/css/navbar.css', 'resources/css/footer.css'])
</head>
<body style="background-color: #f5efff; margin: 0; padding: 0;">
    @include('components.loader')
    @if(session()->has('active_child_id'))
        <div style="max-width: 600px; margin: 40px auto -70px auto; padding: 30px; border-radius: 12px; text-align: center; border: 1px solid #e9d5ff;">
            <h1 style="color: #7c4dff; font-size: 28px; font-weight: bold; margin: 0 0 8px 0;">
                ¡Hola, {{ session('active_child_name') }}!
            </h1>
            <p style="color: #4a5568; font-size: 15px; margin: 0 0 24px 0;">
                Bienvenido a tu espacio seguro de adultez. ¡Disfruta tus actividades!
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
                <span class="stage-header__subtitle" style="color: #7c4dff">Nivel 3 — Autonomía y vida independiente</span>
                <h1 class="stage-header__title">Estación de la Adultez</h1>
                <p class="stage-header__intro">Selecciona cualquiera de nuestros tres módulos interactivos para comenzar a jugar y poner a prueba tus habilidades.</p>
            </header>

            <div class="games-grid">
                <a href="/juegos/adultez/compras" class="game-card">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-adul-1.png') }}" alt="Simulador de Compras" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #7c4dff;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Simulador de Compras</h3>
                        <p class="game-card__description">Administra tu dinero comprando los víveres necesarios.</p>
                    </div>
                </a>

                <a href="/juegos/adultez/entrevista" class="game-card">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-adul-2.png') }}" alt="Entrevista de Trabajo" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #7c4dff;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Entrevista de Trabajo</h3>
                        <p class="game-card__description">Practica el lenguaje corporal y respuestas clave.</p>
                    </div>
                </a>

                <a href="/juegos/adultez/hogar" class="game-card">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-adul-3.png') }}" alt="Organiza tu Espacio" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #7c4dff;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Organiza tu Espacio</h3>
                        <p class="game-card__description">Mantén ordenado tu hogar en el menor tiempo posible.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @include('components.footer')
</body>
</html>