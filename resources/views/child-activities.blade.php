<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] ?? 'Módulo de Niñez' }} - Pharenia</title>
    
    @vite(['resources/css/stages.css', 'resources/css/footer.css'])
</head>
<body style="background-color: {{ $data['bg_color'] ?? '#f0fdf4' }}; margin: 0; padding: 0;">

    {{-- SI ES UN MENOR: Muestra la tarjeta moderna con su saludo y botón de salida seguro --}}
    @if(session()->has('active_child_id'))
        <div style="max-width: 500px; margin: 40px auto 20px auto; background: #ffffff; padding: 25px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; border: 2px solid #e2f8ec;">
            <div style="font-size: 36px; margin-bottom: 8px;">👋</div>
            <h1 style="color: #1f2937; font-size: 26px; font-weight: 700; margin: 0 0 6px 0;">
                ¡Hola, {{ session('active_child_name') }}!
            </h1>
            <p style="color: #4b5563; font-size: 14px; margin: 0 0 20px 0;">
                Bienvenido a tu espacio seguro de niñez. ¡Disfruta tus actividades!
            </p>
            <a href="{{ route('child.logout.form') }}" style="display: inline-flex; align-items: center; justify-content: center; background: #dc2626; color: white; text-decoration: none; padding: 10px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25); transition: background 0.2s ease;">
                Cerrar sesión
            </a>
        </div>
    @else
        {{-- SI ES UN ADULTO TEA: Muestra el navbar completo de la plataforma --}}
        @include('components.navbar')
    @endif

    <div class="stage-page">
        <div class="stage-container">
            
            <header class="stage-header" style="padding-top: 20px;">
                <span class="stage-header__subtitle" style="color: {{ $data['accent_color'] ?? '#2f4f4f' }}">{{ $data['subtitle'] ?? 'ACTIVIDADES INTERACTIVAS' }}</span>
                <h1 class="stage-header__title">{{ $data['title'] ?? 'Módulo de Niñez' }}</h1>
                <p class="stage-header__intro">Selecciona cualquiera de nuestros tres módulos interactivos para comenzar a jugar y poner a prueba tus habilidades.</p>
            </header>

            <div class="games-grid">
                @foreach($data['games'] as $game)
                    <a href="{{ $game['url'] }}" class="game-card">
                        <div class="game-card__image-wrapper">
                            <img src="{{ asset('img/' . $game['img']) }}" alt="{{ $game['title'] }}" class="game-card__img">
                            <div class="game-card__overlay" style="background-color: {{ $data['accent_color'] ?? '#2f4f4f' }};">
                                <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                            </div>
                        </div>
                        <div class="game-card__info">
                            <h3 class="game-card__title">{{ $game['title'] }}</h3>
                            <p class="game-card__description">{{ $game['desc'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>

    @include('components.footer')

</body>
</html>