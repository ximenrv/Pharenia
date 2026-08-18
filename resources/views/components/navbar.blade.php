<nav class="navbar">
    <div class="navbar__logo-container">
        <a href="/">
            <img src="{{ asset('img/logo.png') }}" alt="Pharenia Logo" class="navbar__logo-img">
        </a>
    </div>

    <ul class="navbar__menu">
        <li class="navbar__item">
            <a href="/home" class="navbar__link {{ Request::is('/') ? 'navbar__link--active' : '' }}">{{ __('Inicio') }}</a>
        </li>
        <li class="navbar__item">
            <a href="/information" class="navbar__link {{ Request::is('information*') ? 'navbar__link--active' : '' }}">{{ __('Información') }}</a>
        </li>

        @auth
            {{-- Si es Administrador --}}
            @if(auth()->user()->role === 'admin')
                <li class="navbar__item">
                    <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }}</a>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('admin.dashboard') }}" class="navbar__link {{ Request::is('admin*') ? 'navbar__link--active' : '' }}">{{ __('Panel Admin') }}</a>
                </li>

            {{-- Si es Adulto Autogestor, ve Actividades --}}
            @elseif(auth()->user()->role === 'adult_tea')
                <li class="navbar__item">
                    <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }}</a>
                </li>

            {{-- Si es Tutor / Aliado, ve el Panel Familiar --}}
            @elseif(auth()->user()->role === 'ally_no_tea')
                <li class="navbar__item">
                    <a href="{{ route('family-panel') }}" class="navbar__link {{ Request::is('family*') ? 'navbar__link--active' : '' }}">{{ __('Panel Familiar') }}</a>
                </li>

            {{-- Si es Joven / Adolescente, ve su Stage y la opción de Vincular Adulto Supervisor --}}
            @elseif(auth()->user()->role === 'teen')
                <li class="navbar__item">
                    <a href="{{ route('stage.youth') }}" class="navbar__link {{ Request::is('stage-youth*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }}</a>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('supervisor.vincular') }}" class="navbar__link {{ Request::is('vincular-adulto*') ? 'navbar__link--active' : '' }}">{{ __('Adulto Supervisor') }}</a>
                </li>
            @endif
        @else
            <li class="navbar__item">
                <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }}</a>
            </li>
        @endauth
    </ul>

    <div class="navbar__profile">
        @auth
            <a href="{{ route('profile.edit') }}">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('img/profile.png') }}" alt="{{ __('Perfil') }}" class="nav-avatar">
            </a>

            <form action="{{ route('logout') }}" method="POST" class="navbar__logout-form">
                @csrf
                <button type="submit" class="navbar__logout-btn">{{ __('Cerrar sesión') }}</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="navbar__login-link">{{ __('Iniciar sesión') }}</a>
        @endauth
    </div>
</nav>