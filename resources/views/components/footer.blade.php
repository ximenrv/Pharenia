<footer class="main-footer">
    <div class="main-footer__container">
        <!-- Sección Superior: Logo y Columnas de Enlaces -->
        <div class="main-footer__top">
            <div class="main-footer__brand">
                <img src="{{ asset('img/logo-footer.png') }}" alt="Logo Pharenia" class="main-footer__logo">
            </div>

            <div class="main-footer__links-grid">
                <!-- Columna 1: Navegación -->
                <div class="main-footer__column">
                    <h4>{{ __('Navegación') }}</h4>
                    <ul>
                        <li><a href="/home">{{ __('Inicio') }}</a></li>
                        <li><a href="/information">{{ __('Información') }}</a></li>
                        <li><a href="/activities">{{ __('Actividades') }}</a></li>
                    </ul>
                </div>

                <!-- Columna 2: Información TEA (Dividida en 2 subcolumnas) -->
                <div class="main-footer__column main-footer__column--split">
                    <h4>{{ __('Información TEA') }}</h4>
                    <div class="main-footer__subgrid">
                        <ul class="main-footer__list">
                            <li><a href="/information#section01">{{ __('Sección 01') }}</a></li>
                            <li><a href="/information#section02">{{ __('Sección 02') }}</a></li>
                            <li><a href="/information#section03">{{ __('Sección 03') }}</a></li>
                        </ul>
                        <ul class="main-footer__list">
                            <li><a href="/information#section04">{{ __('Sección 04') }}</a></li>
                            <li><a href="/information#section05">{{ __('Sección 05') }}</a></li>
                            <li><a href="/information#section06">{{ __('Sección 06') }}</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Columna 3: Actividades -->
                <div class="main-footer__column">
                    <h4>{{ __('Actividades') }}</h4>
                    <ul>
                        <li><a href="/activities/ninez">{{ __('Niños') }}</a></li>
                        <li><a href="/activities/juventud">{{ __('Jóvenes') }}</a></li>
                        <li><a href="/activities/adultez">{{ __('Adultos') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sección Inferior: Créditos -->
        <div class="main-footer__bottom">
            <p class="main-footer__credits">
                {{ __('© 2026 Pharenia • Proyecto CREA J • Especialidad Desarrollo de Software • 2°B') }}
            </p>
            <p class="main-footer__slogan">{{ __('Hecho con propósito para quienes ven el mundo diferente.') }}</p>
        </div>
    </div>
</footer>