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

    @vite(['resources/css/home.css', 'resources/css/navbar.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css', 'resources/css/lumen-chat.css', 'resources/js/lumen-chat.js'])
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
                    <a href="/information" class="hero__btn hero__btn--secondary">{{ __('CONOCER MÁS') }}</a>
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

    <!-- Componente del Menú Flotante -->
    <x-settings-menu />

    <!-- Lumen: compañero virtual con IA -->
    <x-lumen-chat />

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