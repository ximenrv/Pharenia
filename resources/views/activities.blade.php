<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Actividades') }}</title>
    
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    
    @vite(['resources/css/activities.css', 'resources/css/navbar.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css' ])
</head>
<body class="act-body">

    @include('components.loader')

    <x-navbar />

    <div class="act-container">
        <header class="act-header">
            <div class="act-header__center">
                <div class="act-header__title-wrapper">
                    <span class="act-header__word">{{ __('ACTIV') }}</span>
                    <img src="{{ asset('/img/lighthouse.png') }}" alt="Faro Pharenia" class="act-header__lighthouse">
                    <span class="act-header__word">{{ __('DADES') }}</span>
                </div>
            </div>
            
            <div class="act-header__actions">
                <a href="{{ auth()->check() && auth()->user()->role === 'teen' ? route('activities.youth') : route('activities.start') }}" class="act-btn-wrapper">
                    <button class="act-btn act-btn--primary">
                        {{ __('COMENZAR') }}
                        <span class="act-btn__arrow">→</span>
                    </button>
                </a>
                <a href="#" id="btn-info-act" class="act-link">{{ __('¿ACTIVIDADES?') }}</a>
            </div>
        </header>
    </div>

    <div id="act-modal" class="act-modal">
        <div class="act-modal__content">
            <span id="close-modal" class="act-modal__close">&times;</span>
            <h2 class="act-modal__title">{{ __('¿Qué son las Actividades?') }}</h2>
            <p class="act-modal__text">
                {{ __('En Pharenia, las actividades están diseñadas como módulos interactivos adaptados para diferentes etapas de la vida: niñez, juventud y adultez.') }}
            </p>
            <p class="act-modal__text">
                {{ __('Cada etapa cuenta con tres juegos específicos que ayudan a estimular habilidades cognitivas, la gestión emocional, la autonomía y la resolución de retos cotidianos de una forma divertida y visualmente atractiva. ¡Explora los faros y pon a prueba tus habilidades!') }}
            </p>
        </div>
    </div>

    <!-- Componente del Menú Flotante -->
    <x-settings-menu />

    <script>
        const openBtn = document.getElementById('btn-info-act');
        const modal = document.getElementById('act-modal');
        const closeBtn = document.getElementById('close-modal');

        // Abrir modal
        openBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            modal.classList.add('act-modal--show');
        });

        // Cerrar modal con la 'X'
        closeBtn.addEventListener('click', () => {
            modal.classList.remove('act-modal--show');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('act-modal--show');
            }
        });
    </script>
</body>
</html>