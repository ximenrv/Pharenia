/**
 * Lumen — Widget de chat flotante.
 * Vanilla JS: abrir/cerrar, enviar mensajes, indicador de escritura,
 * censura suave visible y avatar reactivo según el estado de ánimo.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const root = document.getElementById('lumen-chat');
        if (!root) return;

        const bubble = document.getElementById('lumen-bubble');
        const window_ = document.getElementById('lumen-window');
        const closeBtn = document.getElementById('lumen-close');
        const messagesEl = document.getElementById('lumen-messages');
        const typingEl = document.getElementById('lumen-typing');
        const typingText = document.getElementById('lumen-typing-text');
        const form = document.getElementById('lumen-form');
        const input = document.getElementById('lumen-input');
        const sendBtn = document.getElementById('lumen-send');

        const endpoint = root.dataset.endpoint;
        const csrf = root.dataset.csrf;
        const loggedIn = root.dataset.loggedIn === '1';
        const userAvatar = root.dataset.userAvatar;
        const lumenAvatar = root.dataset.lumenAvatar;

        // Historial en memoria (máx. 12 mensajes como contexto para la IA).
        const history = [];
        const MAX_HISTORY = 12;

        // Textos del indicador de escritura vienen traducidos desde Blade.
        let TYPING_PHRASES = [];
        try {
            TYPING_PHRASES = JSON.parse(root.dataset.typingPhrases || '[]');
        } catch (e) {
            TYPING_PHRASES = [];
        }
        if (!TYPING_PHRASES.length) {
            TYPING_PHRASES = ['Lumen está pensando…', 'Lumen está escribiendo…', 'Espera un poco más…'];
        }

        let ERROR_PHRASES = {};
        try {
            ERROR_PHRASES = JSON.parse(root.dataset.errorPhrases || '{}');
        } catch (e) {
            ERROR_PHRASES = {};
        }

        let typingInterval = null;
        let sending = false;

        /* ---------- Abrir / cerrar ---------- */

        function openChat() {
            window_.hidden = false;
            bubble.setAttribute('aria-expanded', 'true');
            scrollToBottom();
            if (loggedIn) input.focus();
        }

        function closeChat() {
            window_.hidden = true;
            bubble.setAttribute('aria-expanded', 'false');
            bubble.focus();
        }

        bubble.addEventListener('click', function () {
            window_.hidden ? openChat() : closeChat();
        });

        closeBtn.addEventListener('click', closeChat);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !window_.hidden) closeChat();
        });

        /* ---------- Render de mensajes ---------- */

        function addMessage(text, who) {
            const wrap = document.createElement('div');
            wrap.className = 'lumen-msg lumen-msg--' + who;

            const avatar = document.createElement('img');
            avatar.className = 'lumen-msg__avatar';
            avatar.alt = who === 'lumen' ? 'Lumen' : '';
            avatar.src = who === 'lumen' ? lumenAvatar : userAvatar;

            const bubbleEl = document.createElement('div');
            bubbleEl.className = 'lumen-msg__bubble';
            bubbleEl.textContent = text;

            wrap.appendChild(avatar);
            wrap.appendChild(bubbleEl);
            messagesEl.appendChild(wrap);
            scrollToBottom();

            return bubbleEl;
        }

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        /* ---------- Indicador de escritura ---------- */

        function showTyping() {
            typingEl.hidden = false;
            let i = 0;
            typingText.textContent = TYPING_PHRASES[0];
            typingInterval = setInterval(function () {
                i = (i + 1) % TYPING_PHRASES.length;
                typingText.textContent = TYPING_PHRASES[i];
            }, 2600);
        }

        function hideTyping() {
            typingEl.hidden = true;
            if (typingInterval) {
                clearInterval(typingInterval);
                typingInterval = null;
            }
        }

        /* ---------- Envío ---------- */

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!loggedIn || sending) return;

            const text = input.value.trim();
            if (!text) return;

            sending = true;
            input.value = '';
            sendBtn.disabled = true;

            // Burbuja del usuario al instante (luego se reemplaza por la versión censurada si aplica).
            const userBubble = addMessage(text, 'user');
            showTyping();

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    message: text,
                    history: history.slice(-MAX_HISTORY),
                }),
            })
                .then(function (res) {
                    return res.json().then(function (data) {
                        return { ok: res.ok, status: res.status, data: data };
                    });
                })
                .then(function (result) {
                    hideTyping();

                    if (result.status === 401) {
                        // Muro: por si la sesión expiró con el chat abierto.
                        addMessage(result.data.reply, 'lumen');
                        input.disabled = true;
                        sendBtn.disabled = true;
                        return;
                    }

                    if (!result.ok) {
                        addMessage(
                            ERROR_PHRASES.api || 'Ups… algo se movió raro. ¿Intentamos de nuevo?',
                            'lumen'
                        );
                        return;
                    }

                    // Nivel 2 visible: si el mensaje fue censurado, la burbuja se actualiza.
                    if (result.data.censored_message && result.data.censored_message !== text) {
                        userBubble.textContent = result.data.censored_message;
                    }

                    history.push({ role: 'user', content: result.data.censored_message || text });
                    history.push({ role: 'assistant', content: result.data.reply });
                    if (history.length > MAX_HISTORY) {
                        history.splice(0, history.length - MAX_HISTORY);
                    }

                    addMessage(result.data.reply, 'lumen');
                })
                .catch(function () {
                    hideTyping();
                    addMessage(
                        ERROR_PHRASES.network || 'Parece que la conexión se tomó un descanso. Inténtalo otra vez; yo no me muevo de aquí.',
                        'lumen'
                    );
                })
                .finally(function () {
                    sending = false;
                    if (loggedIn) {
                        sendBtn.disabled = false;
                        input.focus();
                    }
                });
        });
    });
})();
