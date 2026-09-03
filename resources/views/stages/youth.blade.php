<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('youth.page_title') }}</title>
    @vite(['resources/css/stages.css', 'resources/css/navbar.css', 'resources/css/footer.css', 'resources/css/dark-theme.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js'])
    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.dataset.theme = savedTheme;
    </script>
</head>
<body style="background-color: #fbf7e3; margin: 0; padding: 0;">
    @include('components.loader')
    @if(session()->has('active_child_id'))
        <div style="max-width: 600px; margin: 40px auto -70px auto; padding: 30px; border-radius: 12px; text-align: center; border: 1px solid #fef08a;">
            <h1 style="color: #bfa12b; font-size: 28px; font-weight: bold; margin: 0 0 8px 0;">
                {{ __('youth.welcome_title', ['name' => session('active_child_name')]) }}
            </h1>
            <p style="color: #4a5568; font-size: 15px; margin: 0 0 24px 0;">
                {{ __('youth.welcome_text') }}
            </p>
            <a href="{{ route('child.logout.form') }}" style="display: inline-flex; align-items: center; justify-content: center; background: #c53030; color: white; text-decoration: none; padding: 10px 22px; border-radius: 8px; font-weight: bold; font-size: 14px;">
                {{ __('youth.logout') }}
            </a>
        </div>
    @else
        @include('components.navbar')
    @endif

    <div class="stage-page">
        <div class="stage-container">
            <header class="stage-header" style="padding-top: 20px;">
                <span class="stage-header__subtitle" style="color: #bfa12b">{{ __('youth.subtitle') }}</span>
                <h1 class="stage-header__title">{{ __('youth.title') }}</h1>
                <p class="stage-header__intro">{{ __('youth.intro') }}</p>
            </header>

            <div class="games-grid">
                <a href="#" class="game-card" data-game-url="{{ route('games.youth.quizzsense') }}" data-game-name="{{ __('youth.game.quizzsense.title') }}">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-juve-1.svg') }}" alt="{{ __('youth.game.quizzsense.title') }}" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #bfa12b;">
                            <span class="game-card__play-btn">{{ __('youth.play_button') }}</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">{{ __('youth.game.quizzsense.title') }}</h3>
                        <p class="game-card__description">{{ __('youth.game.quizzsense.description') }}</p>
                    </div>
                </a>

                <a href="#" class="game-card" data-game-url="{{ route('games.youth.paises') }}" data-game-name="{{ __('youth.game.paises.title') }}">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-juve-2.svg') }}" alt="{{ __('youth.game.paises.title') }}" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #bfa12b;">
                            <span class="game-card__play-btn">{{ __('youth.play_button') }}</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">{{ __('youth.game.paises.title') }}</h3>
                        <p class="game-card__description">{{ __('youth.game.paises.description') }}</p>
                    </div>
                </a>

                <a href="#" class="game-card" data-game-url="{{ route('games.youth.centinela') }}" data-game-name="{{ __('youth.game.centinela.title') }}">
                    <div class="game-card__image-wrapper">
                        <img src="{{ asset('img/game-juve-3.svg') }}" alt="{{ __('youth.game.centinela.title') }}" class="game-card__img">
                        <div class="game-card__overlay" style="background-color: #bfa12b;">
                            <span class="game-card__play-btn">{{ __('youth.play_button') }}</span>
                        </div>
                    </div>
                    <div class="game-card__info">
                        <h3 class="game-card__title">{{ __('youth.game.centinela.title') }}</h3>
                        <p class="game-card__description">{{ __('youth.game.centinela.description') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación antes de entrar al juego --}}
    <div class="modal-overlay" id="game-confirm-modal" aria-hidden="true">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <h2 class="modal-title" id="modal-title">{{ __('youth.modal.title', ['name' => '']) }}</h2>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn--cancel" id="modal-cancel">{{ __('youth.modal.cancel') }}</button>
                <button type="button" class="modal-btn modal-btn--confirm" id="modal-confirm">{{ __('youth.modal.confirm') }}</button>
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
                    const gameName = card.getAttribute('data-game-name');
                    modalTitle.textContent = "{{ __('youth.modal.title', ['name' => ':name']) }}".replace(':name', gameName);
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
