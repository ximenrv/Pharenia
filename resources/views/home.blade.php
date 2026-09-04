<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Inicio') }}</title>
    
    <!-- Script síncrono para evitar parpadeo de tema -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>

    @vite(['resources/css/home.css', 'resources/css/navbar.css', 'resources/js/navbar.js', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css', 'resources/css/lumen-chat.css', 'resources/js/lumen-chat.js'])
</head>
<body class="home-body">

    @include('components.loader')

    <x-navbar />

    <main class="hero">
        <div class="hero__container">
            
            <section class="hero__content">
                <h1 class="hero__title">
                    Pharenia<br><span class="hero__title--highlight">{{ __('El TEA.') }}</span>
                </h1>
                
                <p class="hero__description">
                    {{ __('Pharenia es un lugar con el fin de potenciar las capacidades naturales de personas con Trastorno del Espectro Autista.') }}
                </p>

                <div class="hero__actions">
                    <a href="/activities" class="hero__btn hero__btn--primary">{{ __('EXPLORAR ACTIVIDADES') }}</a>
                    <a href="#" id="btn-info-home" class="hero__btn hero__btn--secondary" role="button" aria-haspopup="dialog" aria-controls="home-modal" aria-expanded="false">{{ __('CONOCER MÁS') }}</a>
                </div>
            </section>

            <div class="hero__character-container">
                <img src="{{ asset('img/glow.png') }}" alt="" class="hero__layer hero__layer--glow">
                
                <img src="{{ asset('img/feather1.png') }}" alt="" class="hero__layer hero__layer--feather-1">
                <img src="{{ asset('img/feather2.png') }}" alt="" class="hero__layer hero__layer--feather-2">
                <img src="{{ asset('img/feather3.png') }}" alt="" class="hero__layer hero__layer--feather-3">
                
                <img src="{{ asset('img/lumen.png') }}" alt="Lumen" class="hero__layer hero__layer--lumen">
                
                <img src="{{ asset('img/cloud.png') }}" alt="" class="hero__layer hero__layer--cloud">
            </div>

        </div>
    </main>

    <!-- Pop-up informativo «Sobre Pharenia» (botón "Conocer más").
         Misma estructura de pop-ups informativos que en Actividades (.act-modal). -->
    <div id="home-modal" class="home-modal" role="dialog" aria-modal="true" aria-labelledby="home-modal-title" aria-hidden="true">
        <div class="home-modal__content">
            <button type="button" id="home-modal-close" class="home-modal__close" aria-label="{{ __('Cerrar') }}">&times;</button>

            <h2 id="home-modal-title" class="home-modal__title">{{ __('Sobre Pharenia') }}</h2>

            <p class="home-modal__text">
                {{ __('Pharenia es un espacio con el fin de potenciar las capacidades naturales de personas con Trastorno del Espectro Autista (TEA). A través de la educación, la sensibilización y el acompañamiento, buscamos ser un puente tecnológico entre el hogar y el desarrollo personal de cada persona.') }}
            </p>

            <h3 class="home-modal__subtitle">{{ __('¿Qué encontrarás en Pharenia?') }}</h3>
            <ul class="home-modal__list">
                <li class="home-modal__item">
                    <h4 class="home-modal__item-title">{{ __('Educación y recursos informativos') }}</h4>
                    <p class="home-modal__text">
                        {{ __('Contenido claro y confiable para comprender el TEA y combatir la desinformación que lo rodea.') }}
                    </p>
                </li>

                <li class="home-modal__item">
                    <h4 class="home-modal__item-title">{{ __('Dinámicas y espacios seguros') }}</h4>
                    <p class="home-modal__text">
                        {{ __('Actividades interactivas y entornos respetuosos para aprender y experimentar sin presiones.') }}
                    </p>
                </li>
            </ul>

            <h3 class="home-modal__subtitle">{{ __('Clasificación de Roles y Accesos') }}</h3>
            <ul class="home-modal__list">
                <li class="home-modal__item">
                    <h4 class="home-modal__item-title">{{ __('Visitante General') }}</h4>
                    <p class="home-modal__text">
                        {{ __('Acceso libre a la información sobre el TEA y a la plataforma para personas mayores de 12 años.') }}
                    </p>
                </li>

                <li class="home-modal__item">
                    <h4 class="home-modal__item-title">{{ __('Adultos (TEA y Aliados)') }}</h4>
                    <p class="home-modal__text">
                        {{ __('Personas adultas con TEA y sus tutores o aliados, con acceso a actividades, foro y acompañamiento.') }}
                    </p>
                </li>

                <li class="home-modal__item">
                    <h4 class="home-modal__item-title">{{ __('Jóvenes (Teens de 13 a 17 años)') }}</h4>
                    <p class="home-modal__text">
                        {{ __('Espacios y dinámicas adaptadas a la adolescencia, siempre acompañados por un adulto responsable.') }}
                    </p>
                </li>

                <li class="home-modal__item">
                    <h4 class="home-modal__item-title">{{ __('Menores de edad (0 a 12 años) / Panel Familiar') }}</h4>
                    <p class="home-modal__text">
                        {{ __('Cuentas creadas únicamente por un adulto responsable, con experiencias supervisadas desde el Panel Familiar.') }}
                    </p>
                </li>
            </ul>

            <p class="home-modal__note">
                {{ __('Nota: Pharenia no busca sustituir la valoración clínica profesional. Somos un aliado tecnológico diario que complementa el acompañamiento de los especialistas.') }}
            </p>
        </div>
    </div>

    <!-- Componente del Menú Flotante -->
    <x-settings-menu />

    <!-- Lumen: compañero virtual con IA -->
    <x-lumen-chat />

    <script>
        (function () {
            const openBtn = document.getElementById('btn-info-home');
            const modal = document.getElementById('home-modal');
            const closeBtn = document.getElementById('home-modal-close');

            if (!openBtn || !modal || !closeBtn) return;

            const openModal = () => {
                modal.classList.add('home-modal--show');
                modal.setAttribute('aria-hidden', 'false');
                openBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
                closeBtn.focus();
            };

            const closeModal = () => {
                modal.classList.remove('home-modal--show');
                modal.setAttribute('aria-hidden', 'true');
                openBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
                openBtn.focus();
            };

            // Abrir modal con el botón "Conocer más"
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });

            // Cerrar modal con la 'X'
            closeBtn.addEventListener('click', closeModal);

            // Cerrar modal al hacer clic fuera del contenedor
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Cerrar modal con la tecla Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('home-modal--show')) {
                    closeModal();
                }
            });
        })();
    </script>

    @if(session('error'))
    <!-- Importar la fuente Nunito desde Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Estilos personalizados idénticos a tus modales usando Nunito */
        .swal2-popup.custom-modal-style {
            background-color: var(--modal-bg, #ffffff) !important;
            border-radius: 30px !important;
            padding: 2.5rem !important;
            border: 2px solid rgba(191, 161, 43, 0.3) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
            font-family: 'Nunito', sans-serif !important;
        }
        
        .swal2-title.custom-title-style {
            font-family: 'Nunito', sans-serif !important;
            font-weight: 700 !important;
            color: var(--modal-title-color, #2c525a) !important;
            font-size: 1.4rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        .swal2-html-container.custom-text-style {
            font-family: 'Nunito', sans-serif !important;
            color: var(--modal-text-color, #4a5568) !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
        }
        
        .swal2-confirm.custom-btn-style {
            background-color: #2c525a !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 2.5rem !important;
            font-family: 'Nunito', sans-serif !important;
            font-weight: 700 !important;
            border-radius: 12px !important;
            box-shadow: none !important;
            cursor: pointer !important;
        }
        
        .swal2-confirm.custom-btn-style:hover {
            background-color: #1f3b41 !important;
        }

        /* Variables automáticas para el Modo Oscuro / Claro */
        [data-theme="dark"] .swal2-popup.custom-modal-style {
            --modal-bg: #16222a;
            --modal-title-color: #ffffff;
            --modal-text-color: #cbd5e0;
        }
    </style>

    <script>
        Swal.fire({
            icon: 'error',
            title: 'Acceso Denegado',
            text: "{{ session('error') }}",
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'custom-modal-style',
                title: 'custom-title-style',
                htmlContainer: 'custom-text-style',
                confirmButton: 'custom-btn-style'
            }
        });
    </script>
@endif

</body>
</html>