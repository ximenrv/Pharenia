<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Mundo de la Niñez') }} - Pharenia</title>
    @vite(['resources/css/stages.css', 'resources/css/navbar.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/footer.css', 'resources/css/dark-theme.css'])
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    <script>
     window.translations = @json($translations ?? []);
    </script>

</head>
<body style="background-color: #eef7f9; margin: 0; padding: 0;">
    @include('components.loader')
    @if(session()->has('active_child_id'))
        <div style="max-width: 1200px; margin: 3rem auto -6rem auto; padding: 0 2rem; box-sizing: border-box;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; ">
                <div>
                    <h1 style="color: #2c525a; font-size: 1.8rem; font-family: Segoe UI, Tahoma,  Verdana, sans-serif; font-weight: 700; margin: 0;">
                        ¡{{ __('Hola') }}, {{ session('active_child_name') }}! 
                    </h1>
                    <p style="color: #4a5568; font-size: 1.05rem; font-family: Segoe UI, Tahoma,  Verdana, sans-serif; margin: 4px 0 0 0;">
                        {{ __('Bienvenido a tu espacio seguro. ¡Disfruta tus actividades!') }}
                    </p>
                </div>
                <a href="{{ route('child.logout.form') }}" style="color: #e53e3e; text-decoration: none; font-size: 1.05rem; font-family: Segoe UI, Tahoma,  Verdana, sans-serif; font-weight: 600; border-bottom: 2px solid #e53e3e; padding-bottom: 2px; transition: opacity 0.2s, transform 0.2s;" onmouseover="this.style.opacity='0.7'; this.style.transform='scale(1.05)';" onmouseout="this.style.opacity='1'; this.style.transform='scale(1)';">
                    {{ __('Cerrar sesión') }}
                </a>
            </div>
            <div style="display: flex; justify-content: center;">
                <div style="width: 90%; height: 2px; background: linear-gradient(90deg, transparent, #2c525a, transparent); margin-top: 2rem;"></div>
            </div>
        </div>
    @else
        @include('components.navbar')
    @endif

    <div class="stage-page">
        <div class="stage-container">
            <header class="stage-header" style="padding-top: 20px;">
                <span class="stage-header__subtitle" style="color: #2c525a">{{ __('Nivel 1 — Explorando figuras y colores') }}</span>
                <h1 class="stage-header__title">{{ __('Mundo de la Niñez') }}</h1>
                <p class="stage-header__intro">{{ __('Selecciona cualquiera de nuestros tres módulos interactivos para comenzar a jugar y poner a prueba tus habilidades') }}.</p>
            </header>

            <div class="games-grid">
                <!-- Cazador de Burbujas -->
                <div class="game-card" data-name-key="Cazador de Burbujas" data-url="{{ route('games.cazador') }}">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-ninez-1.png') }}" alt="Cazador de Burbujas" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #2c525a;">
                            <span class="game-card__play-btn">{{ __('¡JUGAR AHORA!') }}</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">{{ __('Cazador de Burbujas') }}</h3>
                        <p class="game-card__description">{{ __('Atrapa las burbujas con la forma que te pida Lumen') }}.</p>
                    </div>
                </div>

                <!-- Guardianes del Planeta -->
                <div class="game-card" data-name-key="Guardianes del Planeta" data-url="{{ route('games.guardianes') }}">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-ninez-2.png') }}" alt="Guardianes del Planeta" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #2c525a;">
                            <span class="game-card__play-btn">{{ __('¡JUGAR AHORA!') }}</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">{{ __('Guardianes del Planeta') }}</h3>
                        <p class="game-card__description">{{ __('Ayuda a Lumen a limpiar la playa') }}.</p>
                    </div>
                </div>

                <!-- Eco de los Colores -->
                <div class="game-card" data-name-key="Eco de los Colores" data-url="{{ route('games.eco') }}">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-ninez-3.png') }}" alt="Eco de los Colores" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #2c525a;">
                            <span class="game-card__play-btn">{{ __('¡JUGAR AHORA!') }}</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">{{ __('Eco de los Colores') }}</h3>
                        <p class="game-card__description">{{ __('Memoriza el patrón de burbujas mágicas') }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <h2 id="modalText" class="modal-title">{{ __('¿Quieres jugar?') }}</h2>
            <div class="modal-actions">
                <button class="modal-btn modal-btn--cancel" onclick="closeModal()">{{ __('Cancelar') }}</button>
                <button id="confirmPlayBtn" class="modal-btn modal-btn--confirm">{{ __('¡Sí, jugar!') }}</button>
            </div>
        </div>
    </div>

    @if(!session()->has('active_child_id'))
        @include('components.footer')
    @endif

    <x-settings-menu />

   <script>
    let targetGameUrl = '';

    document.querySelectorAll('.game-card').forEach(card => {
        card.addEventListener('click', function() {
            const translatedGameName = this.querySelector('.game-card__title').innerText.trim();
            targetGameUrl = this.getAttribute('data-url');

            const translatedTemplate = "{{ __('¿Quieres jugar :game?') }}";

            const translatedText = translatedTemplate.replace(':game', translatedGameName);

            document.getElementById('modalText').innerText = translatedText;
            document.getElementById('confirmModal').classList.add('active');
        });
    });

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('active');
    }

    document.getElementById('confirmPlayBtn').addEventListener('click', function() {
        if (targetGameUrl) {
            window.location.href = targetGameUrl;
        }
    });
</script>
</body>
</html>