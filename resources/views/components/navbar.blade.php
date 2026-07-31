<nav class="navbar">
    <div class="navbar__logo-container">
        <a href="/">
            <img src="{{ asset('img/logo.png') }}" alt="Pharenia Logo" class="navbar__logo-img">
        </a>
    </div>

    <ul class="navbar__menu">
        <li class="navbar__item">
            <a href="/" class="navbar__link {{ Request::is('/') ? 'navbar__link--active' : '' }}">Inicio</a>
        </li>
        <li class="navbar__item">
            <a href="/information" class="navbar__link {{ Request::is('information*') ? 'navbar__link--active' : '' }}">Información</a>
        </li>

        @auth
            {{-- Si es Adulto Autogestor, ve Actividades --}}
            @if(auth()->user()->role === 'adult_tea')
                <li class="navbar__item">
                    <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">Actividades</a>
                </li>
            @endif

            {{-- Si es Tutor / Aliado, ve el Panel Familiar y se ocultan actividades --}}
            @if(auth()->user()->role === 'ally_no_tea')
                <li class="navbar__item">
                    <a href="{{ route('family-panel') }}" class="navbar__link {{ Request::is('family*') ? 'navbar__link--active' : '' }}">Panel Familiar</a>
                </li>
            @endif
        @else
            <li class="navbar__item">
                <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">Actividades</a>
            </li>
        @endauth
    </ul>

    <div class="navbar__profile">
        @auth
            <a href="{{ route('profile.edit') }}">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('img/profile.png') }}" alt="Perfil" class="tu-clase-avatar">
            </a>

            <form action="{{ route('logout') }}" method="POST" class="navbar__logout-form">
                @csrf
                <button type="submit" class="navbar__logout-btn">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="navbar__login-link">Iniciar sesión</a>
        @endauth
    </div>
</nav>
