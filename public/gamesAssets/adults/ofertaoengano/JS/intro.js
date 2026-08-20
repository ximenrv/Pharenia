document.addEventListener('DOMContentLoaded', () => {
    // ===== DIÁLOGOS =====
    const dialogues = [
        {
            text: 'Hola, soy Lumen, tu compañero en esta aventura.\nBienvenido a "Oferta o Engaño", un juego donde juntos aprenderemos a identificar situaciones reales del día a día.',
            showBack: false,
            showNext: true,
            showPlay: false
        },
        {
            text: '¡Te explico cómo funciona! Primero verás una situación de la vida real.\nLéela con mucha atención y piensa bien: ¿es una oferta real o podría ser un engaño?',
            showBack: true,
            showNext: true,
            showPlay: false
        },
        {
            text: 'Después entrarás a un laberinto. Usa las flechas o las teclas W A S D para moverme.\n¿Crees que es una OFERTA real? Llévame a la salida verde de arriba.\n¿Crees que es un ENGAÑO? Llévame a la salida naranja de abajo.',
            showBack: true,
            showNext: true,
            showPlay: false
        },
        {
            text: 'En el laberinto encontrarás obstáculos: la Duda, la Prisa, la Trampa y el Rumor.\nSi nos tocan, volveremos al centro donde es seguro.\nEsquívalos con cuidado. No te preocupes si no aciertas, te explicaré todo después de cada decisión.',
            showBack: true,
            showNext: false,
            showPlay: true
        }
    ];

    let currentDialogue = 0;
    let isTyping = false;
    let typingTimeout = null;
    let fullText = '';

    // ===== ELEMENTOS =====
    const dialogueText = document.getElementById('dialogue-text');
    const dialogueIndicator = document.getElementById('dialogue-indicator');
    const lumenContainer = document.getElementById('lumen-container');
    const btnBack = document.getElementById('btn-back');
    const btnNext = document.getElementById('btn-next');
    const btnPlay = document.getElementById('btn-play');
    const btnGameBack = document.getElementById('btn-game-back');
    const transitionOverlay = document.getElementById('transition-overlay');
    const screenIntro = document.getElementById('screen-intro');
    const screenGame = document.getElementById('screen-game');

    // ===== ANIMACIÓN DE HABLA =====
    function setTalking(talking) {
        if (talking) {
            lumenContainer.classList.add('talking');
        } else {
            lumenContainer.classList.remove('talking');
        }
    }

    // ===== EFECTO DE ESCRITURA =====
    function typeText(text, callback) {
        isTyping = true;
        fullText = text;
        dialogueIndicator.classList.remove('visible');
        dialogueText.textContent = '';
        setTalking(true);

        const lines = text.split('\n');
        let charIndex = 0;
        let totalChars = text.replace(/\n/g, '').length;

        function typeChar() {
            if (charIndex >= totalChars) {
                isTyping = false;
                setTalking(false);
                dialogueIndicator.classList.add('visible');
                if (callback) callback();
                return;
            }

            let currentPos = 0;
            let displayHtml = '';

            for (let l = 0; l < lines.length; l++) {
                for (let c = 0; c < lines[l].length; c++) {
                    if (currentPos <= charIndex) {
                        displayHtml += lines[l][c];
                    }
                    currentPos++;
                }
                if (currentPos <= charIndex && l < lines.length - 1) {
                    displayHtml += '<br>';
                }
            }

            dialogueText.innerHTML = displayHtml;
            charIndex++;
            typingTimeout = setTimeout(typeChar, 28);
        }

        typeChar();
    }

    function skipTyping() {
        if (isTyping) {
            clearTimeout(typingTimeout);
            isTyping = false;
            setTalking(false);

            const lines = fullText.split('\n');
            dialogueText.innerHTML = lines.join('<br>');
            dialogueIndicator.classList.add('visible');
        }
    }

    // ===== MOSTRAR DIÁLOGO =====
    function showDialogue(index) {
        const d = dialogues[index];

        btnBack.classList.toggle('hidden', !d.showBack);

        if (d.showPlay) {
            btnNext.classList.add('hidden');
            btnPlay.classList.add('visible');
        } else {
            btnNext.classList.remove('hidden');
            btnPlay.classList.remove('visible');
        }

        typeText(d.text);
    }

    // ===== EVENTOS =====
    btnNext.addEventListener('click', () => {
        if (isTyping) {
            skipTyping();
            return;
        }
        if (currentDialogue < dialogues.length - 1) {
            currentDialogue++;
            showDialogue(currentDialogue);
        }
    });

    btnBack.addEventListener('click', () => {
        if (isTyping) {
            skipTyping();
            return;
        }
        if (currentDialogue > 0) {
            currentDialogue--;
            showDialogue(currentDialogue);
        }
    });

    document.getElementById('dialogue-bubble').addEventListener('click', () => {
        skipTyping();
    });

    // ===== BOTÓN JUGAR → TRANSICIÓN =====
    btnPlay.addEventListener('click', () => {
        transitionOverlay.classList.add('active');

        setTimeout(() => {
            screenIntro.classList.remove('active');
            screenGame.classList.add('active');

            setTimeout(() => {
                transitionOverlay.classList.remove('active');
            }, 600);
        }, 800);
    });

    // ===== BOTÓN REGRESAR DEL JUEGO =====
    btnGameBack.addEventListener('click', () => {
        transitionOverlay.classList.add('active');

        setTimeout(() => {
            screenGame.classList.remove('active');
            screenIntro.classList.add('active');
            currentDialogue = 0;
            showDialogue(0);

            setTimeout(() => {
                transitionOverlay.classList.remove('active');
            }, 600);
        }, 800);
    });

    // ===== INICIO =====
    showDialogue(0);
});
