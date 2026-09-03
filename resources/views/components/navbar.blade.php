<nav class="navbar">
    <div class="navbar__logo-container">
        <a href="/">
            <img src="{{ asset('img/logo.png') }}" alt="Pharenia Logo" class="navbar__logo-img">
        </a>
    </div>

    {{-- Botón hamburguesa: solo visible en el breakpoint móvil --}}
    <button type="button" class="navbar__toggle" id="navbarToggle" aria-label="{{ __('Abrir menú de navegación') }}" aria-expanded="false" aria-controls="navbarCollapse">
        <span class="navbar__toggle-bar"></span>
        <span class="navbar__toggle-bar"></span>
        <span class="navbar__toggle-bar"></span>
    </button>

    {{-- Contenedor colapsable: fila en escritorio / drawer (overlay) en móvil --}}
    <div class="navbar__collapse" id="navbarCollapse">

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
                <li class="navbar__item navbar__item--dropdown">
                    <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }} ▾</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('activities.child') }}" class="dropdown-link">{{ __('Niñez') }}</a></li>
                        <li><a href="{{ route('activities.youth') }}" class="dropdown-link">{{ __('Juventud') }}</a></li>
                        <li><a href="{{ route('activities.adultez') }}" class="dropdown-link">{{ __('Adultez') }}</a></li>
                    </ul>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('forum.index') }}" class="navbar__link {{ Request::is('lumenia*') ? 'navbar__link--active' : '' }}">{{ __('Lumenia') }}</a>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('admin.dashboard') }}" class="navbar__link {{ Request::is('admin*') ? 'navbar__link--active' : '' }}">{{ __('Panel Admin') }}</a>
                </li>

            {{-- Si es Adulto Autogestor --}}
            @elseif(auth()->user()->role === 'adult_tea')
                <li class="navbar__item navbar__item--dropdown">
                    <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }} ▾</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('activities.child') }}" class="dropdown-link">{{ __('Niñez') }}</a></li>
                        <li><a href="{{ route('activities.youth') }}" class="dropdown-link">{{ __('Juventud') }}</a></li>
                        <li><a href="{{ route('activities.adultez') }}" class="dropdown-link">{{ __('Adultez') }}</a></li>
                    </ul>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('forum.index') }}" class="navbar__link {{ Request::is('lumenia*') ? 'navbar__link--active' : '' }}">{{ __('Lumenia') }}</a>
                </li>

            {{-- Si es Tutor / Aliado --}}
            @elseif(auth()->user()->role === 'ally_no_tea')
                 <li class="navbar__item navbar__item--dropdown">
                    <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }} ▾</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('activities.child') }}" class="dropdown-link">{{ __('Niñez') }}</a></li>
                        <li><a href="{{ route('activities.youth') }}" class="dropdown-link">{{ __('Juventud') }}</a></li>
                        <li><a href="{{ route('activities.adultez') }}" class="dropdown-link">{{ __('Adultez') }}</a></li>
                    </ul>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('forum.index') }}" class="navbar__link {{ Request::is('lumenia*') ? 'navbar__link--active' : '' }}">{{ __('Lumenia') }}</a>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('family-panel') }}" class="navbar__link {{ Request::is('family*') ? 'navbar__link--active' : '' }}">{{ __('Panel Familiar') }}</a>
                </li>

            {{-- Si es Joven / Adolescente --}}
            @elseif(auth()->user()->role === 'teen')
                <li class="navbar__item navbar__item--dropdown">
                    <a href="{{ route('activities') }}" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }} ▾</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('activities.youth') }}" class="dropdown-link">{{ __('Juventud') }}</a></li>
                    </ul>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('forum.index') }}" class="navbar__link {{ Request::is('lumenia*') ? 'navbar__link--active' : '' }}">{{ __('Lumenia') }}</a>
                </li>
                <li class="navbar__item">
                    <a href="{{ route('supervisor.vincular') }}" class="navbar__link {{ Request::is('vincular-adulto*') ? 'navbar__link--active' : '' }}">{{ __('Adulto Supervisor') }}</a>
                </li>
            @endif
        @else
            <li class="navbar__item navbar__item--dropdown">
                <a href="/activities" class="navbar__link {{ Request::is('activities*') ? 'navbar__link--active' : '' }}">{{ __('Actividades') }} ▾</a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('activities.child') }}" class="dropdown-link">{{ __('Niñez') }}</a></li>
                    <li><a href="{{ route('activities.youth') }}" class="dropdown-link">{{ __('Juventud') }}</a></li>
                    <li><a href="{{ route('activities.adultez') }}" class="dropdown-link">{{ __('Adultez') }}</a></li>
                </ul>
            </li>
            <li class="navbar__item">
                <a href="{{ route('forum.index') }}" class="navbar__link {{ Request::is('lumenia*') ? 'navbar__link--active' : '' }}">{{ __('Lumenia') }}</a>
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
    </div>
</nav>