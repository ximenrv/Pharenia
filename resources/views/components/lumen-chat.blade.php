@php
    // Muro de acceso: usuario autenticado o niño con sesión PIN activa.
    $lumenAuthUser = auth()->user();
    $lumenChild = null;

    if (! $lumenAuthUser && session()->has('active_child_id')) {
        $lumenChild = \App\Models\ChildProfile::find(session('active_child_id'));
    }

    $lumenName = $lumenAuthUser?->name ?? $lumenChild?->name;
    $lumenRole = $lumenAuthUser
        ? ($lumenAuthUser->role === 'admin' ? 'ally_no_tea' : $lumenAuthUser->role)
        : ($lumenChild ? 'child' : null);

    $lumenUserAvatar = ($lumenAuthUser && $lumenAuthUser->avatar)
        ? asset('storage/' . $lumenAuthUser->avatar)
        : asset('img/profile.png');

    // Foto de perfil oficial de Lumen; si el archivo aún no existe,
    // se usa la expresión feliz como respaldo.
    $lumenProfilePic = file_exists(public_path('img/lumen/lumen-avatar.png'))
        ? asset('img/lumen/lumen-avatar.png')
        : asset('img/lumen/lumenfeliz.png');

    // Bienvenida personalizada según el rol (se renderiza sin gastar API).
    $lumenWelcome = match ($lumenRole) {
        'child' => __('¡Hola, :name! 🧸✨ Soy Lumen, tu nuevo amigo. ¿De qué quieres platicar hoy?', ['name' => $lumenName]),
        'teen' => __('Hola, :name. Soy Lumen. Este es tu espacio seguro: puedes contarme cómo te fue hoy, sin juicios. ¿Cómo estás?', ['name' => $lumenName]),
        'adult_tea' => __('Hola, :name. Soy Lumen. Este es un espacio seguro y sin presiones. Puedes contarme lo que quieras, a tu ritmo. ¿Cómo estás hoy?', ['name' => $lumenName]),
        'ally_no_tea' => __('Hola, :name. Soy Lumen, tu amigo en Pharenia. ¿Cómo va tu día? Aquí puedes desahogarte con confianza.', ['name' => $lumenName]),
        default => null,
    };
@endphp

<div
    id="lumen-chat"
    class="lumen-chat"
    data-endpoint="{{ route('lumen.chat') }}"
    data-csrf="{{ csrf_token() }}"
    data-logged-in="{{ $lumenRole ? '1' : '0' }}"
    data-user-name="{{ $lumenName ?? '' }}"
    data-user-avatar="{{ $lumenUserAvatar }}"
    data-lumen-avatar="{{ $lumenProfilePic }}"
    data-lumen-avatar-base="{{ asset('img/lumen') }}"
    data-typing-phrases="{{ json_encode([__('Lumen está pensando…'), __('Lumen está escribiendo…'), __('Espera un poco más…')]) }}"
    data-error-phrases="{{ json_encode(['api' => __('Ups… algo se movió raro. ¿Intentamos de nuevo?'), 'network' => __('Parece que la conexión se tomó un descanso. Inténtalo otra vez; yo no me muevo de aquí.')]) }}"
>
    {{-- Burbuja flotante (esquina inferior izquierda) --}}
    <button
        type="button"
        id="lumen-bubble"
        class="lumen-chat__bubble"
        aria-label="{{ __('Habla con Lumen') }}"
        aria-expanded="false"
        aria-controls="lumen-window"
    >
        <img src="{{ $lumenProfilePic }}" alt="" class="lumen-chat__bubble-img" id="lumen-bubble-img">
        <span class="lumen-chat__bubble-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                <path d="M12 2C6.48 2 2 6.02 2 11c0 2.4 1.05 4.58 2.77 6.2-.2 1.1-.72 2.4-1.7 3.4-.2.2-.16.55.1.68.13.06.27.09.41.09 1.5 0 3.1-.62 4.28-1.33 1.33.5 2.8.76 4.14.76 5.52 0 10-4.02 10-9s-4.48-9.8-10-9.8z"/>
            </svg>
        </span>
        <span class="lumen-chat__bubble-tooltip" role="tooltip">{{ __('Habla con Lumen') }}</span>
    </button>

    {{-- Ventana de chat --}}
    <section
        id="lumen-window"
        class="lumen-chat__window"
        aria-label="{{ __('Chat con Lumen') }}"
        hidden
    >
        <header class="lumen-chat__header">
            <img
                src="{{ $lumenProfilePic }}"
                alt="Lumen"
                class="lumen-chat__header-avatar"
                id="lumen-header-avatar"
            >
            <div class="lumen-chat__header-text">
                <strong class="lumen-chat__header-name">Lumen</strong>
                <span class="lumen-chat__header-status" id="lumen-status">{{ __('Tu amigo en Pharenia') }}</span>
            </div>
            <button type="button" id="lumen-close" class="lumen-chat__close" aria-label="{{ __('Cerrar chat') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
                    <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
                </svg>
            </button>
        </header>

        <div class="lumen-chat__messages" id="lumen-messages" aria-live="polite">
            @if ($lumenWelcome)
                <div class="lumen-msg lumen-msg--lumen">
                    <img src="{{ $lumenProfilePic }}" alt="Lumen" class="lumen-msg__avatar">
                    <div class="lumen-msg__bubble">{{ $lumenWelcome }}</div>
                </div>
            @endif
        </div>

        @if (! $lumenRole)
            {{-- Muro de inicio de sesión --}}
            <div class="lumen-chat__wall" id="lumen-wall">
                <img src="{{ asset('img/lumen/lumendedo.png') }}" alt="Lumen" class="lumen-chat__wall-img">
                <p class="lumen-chat__wall-text">
                    {{ __('¡Hola! Me encantaría platicar contigo, pero necesito que inicies sesión o te registres para que seamos amigos oficiales. ¿Creamos tu cuenta?') }}
                </p>
                <div class="lumen-chat__wall-actions">
                    <a href="{{ route('login') }}" class="lumen-chat__wall-btn lumen-chat__wall-btn--primary">{{ __('Iniciar sesión') }}</a>
                    <a href="{{ route('register') }}" class="lumen-chat__wall-btn lumen-chat__wall-btn--secondary">{{ __('Crear cuenta') }}</a>
                </div>
            </div>
        @endif

        {{-- Indicador de escritura --}}
        <div class="lumen-chat__typing" id="lumen-typing" hidden>
            <img src="{{ $lumenProfilePic }}" alt="" class="lumen-msg__avatar">
            <div class="lumen-chat__typing-bubble">
                <span class="lumen-chat__typing-text" id="lumen-typing-text">{{ __('Lumen está pensando…') }}</span>
                <span class="lumen-chat__dots" aria-hidden="true"><i></i><i></i><i></i></span>
            </div>
        </div>

        <footer class="lumen-chat__footer">
            <form id="lumen-form" class="lumen-chat__form" autocomplete="off">
                <input
                    type="text"
                    id="lumen-input"
                    class="lumen-chat__input"
                    placeholder="{{ __('Escribe un mensaje…') }}"
                    maxlength="1000"
                    {{ $lumenRole ? '' : 'disabled' }}
                >
                <button type="submit" id="lumen-send" class="lumen-chat__send" aria-label="{{ __('Enviar mensaje') }}" {{ $lumenRole ? '' : 'disabled' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                        <path d="M3.4 20.4l17.4-7.5c.8-.35.8-1.45 0-1.8L3.4 3.6c-.66-.29-1.39.2-1.39.91L2 9.12c0 .5.37.93.87.99L17 12 2.87 13.88c-.5.07-.87.5-.87 1l.01 4.61c0 .71.73 1.2 1.39.91z"/>
                    </svg>
                </button>
            </form>
        </footer>
    </section>
</div>
