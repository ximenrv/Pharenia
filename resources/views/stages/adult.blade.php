<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estación de la Adultez - Pharenia</title>
    @vite(['resources/css/stages.css', 'resources/css/navbar.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/footer.css', 'resources/css/dark-theme.css'])
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    <style>
        /* Zoom sutil para recortar bordes decorativos de las portadas */
        .game-card__img {
            transform: scale(1.06);
        }
        .game-card:hover .game-card__img {
            transform: scale(1.14);
        }
    </style>
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
                <a href="#" class="game-card" data-game-url="{{ route('games.adults.ofertaoengano') }}" data-game-name="Oferta o Engaño">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('gamesAssets/adults/ofertaoengano/ASSETS/backgrounds/ofertaoenganoport.png') }}" alt="Oferta o Engaño" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #7c4dff;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Oferta o Engaño</h3>
                        <p class="game-card__description">Aprende a identificar ofertas reales y engaños del día a día.</p>
                    </div>
                </a>

                <a href="#" class="game-card" data-game-url="{{ route('games.adults.siguelareceta') }}" data-game-name="Sigue la Receta">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('gamesAssets/adults/siguelareceta/assets/backgrounds/siguelarecetaport.png') }}" alt="Sigue la Receta" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #7c4dff;">
                            <span class="game-card__play-btn">¡JUGAR AHORA!</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">Sigue la Receta</h3>
                        <p class="game-card__description">Aprende a cocinar paso a paso siguiendo las instrucciones.</p>
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

    {{-- Modal de confirmación antes de entrar al juego --}}
    <div class="modal-overlay" id="game-confirm-modal" aria-hidden="true">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <h2 class="modal-title" id="modal-title">¿Quieres jugar?</h2>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn--cancel" id="modal-cancel">Cancelar</button>
                <button type="button" class="modal-btn modal-btn--confirm" id="modal-confirm">¡Sí, jugar!</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('game-confirm-modal');
            const modalTitle = document.getElementById('modal-title');
            const btnCancel = document.getElementById('modal-cancel');
            const btnConfirm = document.getElementById('modal-confirm');
            let targetUrl = '';

            document.querySelectorAll('.game-card').forEach(function (card) {
                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    targetUrl = card.getAttribute('data-game-url');
                    var gameName = card.getAttribute('data-game-name');
                    if (!targetUrl || !gameName) return;
                    modalTitle.textContent = '¿Quieres jugar ' + gameName + '?';
                    modal.classList.add('active');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            function closeModal() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            btnCancel.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            btnConfirm.addEventListener('click', function () {
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) {
                    closeModal();
                }
            });
        });
    </script>

    @include('components.footer')

    <x-settings-menu />
</body>
</html>