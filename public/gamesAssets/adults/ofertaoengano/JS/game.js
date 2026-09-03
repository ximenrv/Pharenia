document.addEventListener('DOMContentLoaded', () => {
    // Traducción: devuelve la versión traducida de la clave (o la clave si no existe)
    const t = (key) => (window.translations && window.translations[key]) ? window.translations[key] : key;

    // ===== MAPA BASE (niveles 1-3): 19×15 =====
    const mazeSmall = [
        [1,1,1,1,1,1,1,3,3,3,3,3,1,1,1,1,1,1,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,0,1,0,1,0,1,1,0,1,0,1,1,0,1,0,1,0,1],
        [1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,1],
        [1,0,1,1,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1],
        [1,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,1],
        [1,1,1,0,1,1,1,0,1,0,1,0,1,1,1,0,1,1,1],
        [1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1],
        [1,1,1,0,1,1,1,0,1,0,1,0,1,1,1,0,1,1,1],
        [1,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,1],
        [1,0,1,1,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1],
        [1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,1],
        [1,0,1,0,1,0,1,1,0,1,0,1,1,0,1,0,1,0,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,1,1,1,1,1,1,4,4,4,4,4,1,1,1,1,1,1,1],
    ];

    // ===== MAPA GRANDE (nivel 4+): 21×17 =====
    const mazeBig = [
        [1,1,1,1,1,1,1,1,3,3,3,3,3,1,1,1,1,1,1,1,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1],
        [1,0,1,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0,1],
        [1,0,1,1,1,0,1,1,1,0,1,0,1,1,1,0,1,1,1,0,1],
        [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1],
        [1,1,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1,0,1,1,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,2,0,0,1,1,1,0,1,0,1,0,1,0,1,1,1,0,0,0,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,1,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1,0,1,1,1],
        [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1],
        [1,0,1,1,1,0,1,1,1,0,1,0,1,1,1,0,1,1,1,0,1],
        [1,0,1,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0,1],
        [1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,1,1,1,1,1,1,1,4,4,4,4,4,1,1,1,1,1,1,1,1],
    ];

    // ===== MAPA ENORME (nivel 5): 25×17 =====
    const mazeHuge = [
        [1,1,1,1,1,1,1,1,1,1,3,3,3,3,3,1,1,1,1,1,1,1,1,1,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,0,1,0,1,0,1,1,1,0,1,0,1,0,1,0,1,1,1,0,1,0,1,0,1],
        [1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,1],
        [1,0,1,0,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1,0,1,0,1,0,1],
        [1,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,1],
        [1,1,1,0,1,1,1,0,1,0,1,1,1,1,1,0,1,0,1,1,1,0,1,1,1],
        [1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1],
        [1,2,0,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,1,0,0,0,1],
        [1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1],
        [1,1,1,0,1,1,1,0,1,0,1,1,1,1,1,0,1,0,1,1,1,0,1,1,1],
        [1,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,1],
        [1,0,1,0,1,0,1,0,1,1,1,0,1,0,1,1,1,0,1,0,1,0,1,0,1],
        [1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,1],
        [1,0,1,0,1,0,1,1,1,0,1,0,1,0,1,0,1,1,1,0,1,0,1,0,1],
        [1,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
        [1,1,1,1,1,1,1,1,1,1,4,4,4,4,4,1,1,1,1,1,1,1,1,1,1],
    ];

    // ===== DATOS DE TODOS LOS NIVELES =====
    const levels = [
        {
            level: 1,
            theme: 'Compras',
            situation: 'Recibes un mensaje de WhatsApp de un número desconocido que dice: "¡Felicidades! Ganaste un celular último modelo totalmente gratis. Solo debes pagar el envío de $15.000 a esta cuenta para recibirlo." Tú no participaste en ningún sorteo ni concurso.',
            answer: 'engano',
            timerDuration: 5000,
            maze: mazeSmall,
            enemies: [
                { x: 5, y: 3, shape: 'circle', interval: 400, behavior: 'random' },
                { x: 13, y: 11, shape: 'triangle', interval: 420, behavior: 'random' },
            ],
            resultCorrect: {
                title: '¡Muy bien hecho!',
                explanation: 'Esa situación era un engaño. Nadie regala celulares así porque sí. Si no participaste en un sorteo, no puedes ganar nada. Pedir dinero por "envío" es una forma muy común de estafa. Recuerda: si suena demasiado bueno para ser verdad, ¡probablemente lo es!'
            },
            resultIncorrect: {
                title: 'Vamos a intentarlo de nuevo',
                explanation: 'No te preocupes, esto es parte de aprender. Esa situación era un engaño. Cuando alguien te dice que ganaste algo sin haber participado y te pide dinero por "envío", es una señal clara de estafa. Nadie regala celulares a desconocidos. Vuelve al laberinto y busca la otra salida, ¡tú puedes!'
            }
        },
        {
            level: 2,
            theme: 'Internet',
            situation: 'Encuentras una tienda en redes sociales que vende zapatillas de marca con 70% de descuento. La página fue creada hace 3 días, tiene muy pocas reseñas y todas son del mismo día. Solo aceptan pago por transferencia bancaria y te dicen que la oferta es "solo por hoy".',
            answer: 'engano',
            timerDuration: 5500,
            maze: mazeSmall,
            enemies: [
                { x: 5, y: 3, shape: 'circle', interval: 320, behavior: 'random' },
                { x: 13, y: 3, shape: 'triangle', interval: 330, behavior: 'random' },
                { x: 9, y: 5, shape: 'circle', interval: 340, behavior: 'random' },
                { x: 3, y: 9, shape: 'triangle', interval: 335, behavior: 'random' },
                { x: 15, y: 11, shape: 'circle', interval: 325, behavior: 'random' },
            ],
            resultCorrect: {
                title: '¡Excelente trabajo!',
                explanation: 'Esa situación era un engaño. Las páginas nuevas con pocas reseñas, descuentos exagerados y que solo aceptan transferencia son señales de fraude. Las tiendas reales aceptan múltiples formas de pago y no te presionan con "solo por hoy". ¡Siempre investiga antes de comprar!'
            },
            resultIncorrect: {
                title: 'Casi, intentemos otra vez',
                explanation: 'No pasa nada, estás aprendiendo. Esa situación era un engaño. Fíjate en las señales: la página era muy nueva, las reseñas parecían falsas, solo aceptaban transferencia y te presionaban con urgencia. Las tiendas legítimas no necesitan esas tácticas. Vuelve al laberinto, ¡tú puedes!'
            }
        },
        {
            level: 3,
            theme: 'Mensajes',
            situation: 'La app oficial de tu supermercado te envía una notificación: "Por ser cliente frecuente, tienes un cupón del 20% de descuento en tu próxima compra." El cupón aparece directamente dentro de la app, tiene fecha de vencimiento de 2 semanas y no te pide compartir datos personales ni hacer clic en enlaces externos.',
            answer: 'oferta',
            timerDuration: 6000,
            maze: mazeSmall,
            enemies: [
                { x: 5, y: 3, shape: 'diamond', interval: 380, behavior: 'chase' },
                { x: 13, y: 3, shape: 'diamond', interval: 395, behavior: 'chase' },
                { x: 9, y: 9, shape: 'diamond', interval: 400, behavior: 'chase' },
                { x: 3, y: 11, shape: 'diamond', interval: 385, behavior: 'chase' },
                { x: 15, y: 11, shape: 'diamond', interval: 390, behavior: 'chase' },
            ],
            resultCorrect: {
                title: '¡Increíble, lo lograste!',
                explanation: 'Esta vez sí era una oferta real. El cupón venía de la app oficial que ya tenías instalada, el descuento era razonable (20%), aparecía dentro de la app sin enlaces externos y no pedía datos personales. Las apps oficiales sí te envían promociones reales. ¡No todo es engaño!'
            },
            resultIncorrect: {
                title: 'Esta vez era diferente',
                explanation: 'No te preocupes, es normal dudar. Esta situación sí era una oferta real. Venía de la app oficial, el descuento era razonable, aparecía dentro de la app y no pedía datos. Aprender a confiar cuando es seguro también es importante. Vuelve al laberinto, ¡tú puedes!'
            }
        },
        {
            level: 4,
            theme: 'Dinero',
            situation: 'Tu empresa de servicios de agua te envía un correo desde su dirección oficial informándote que te cobraron de más el mes pasado. El saldo a favor se descontará automáticamente de tu próxima factura. Puedes verificar el detalle entrando a la sección "Mi cuenta" de su página web oficial.',
            answer: 'oferta',
            timerDuration: 6500,
            maze: mazeBig,
            enemies: [
                { x: 5, y: 3, shape: 'spitter', interval: 350, behavior: 'random', spitRange: 5, spitCooldown: 2800 },
                { x: 15, y: 3, shape: 'spitter', interval: 360, behavior: 'random', spitRange: 5, spitCooldown: 3000 },
                { x: 3, y: 13, shape: 'spitter', interval: 370, behavior: 'random', spitRange: 5, spitCooldown: 2600 },
                { x: 17, y: 13, shape: 'spitter', interval: 355, behavior: 'random', spitRange: 5, spitCooldown: 2900 },
                { x: 7, y: 7, shape: 'spitter', interval: 340, behavior: 'chase', spitRange: 5, spitCooldown: 2500 },
                { x: 13, y: 9, shape: 'spitter', interval: 345, behavior: 'chase', spitRange: 5, spitCooldown: 2700 },
            ],
            resultCorrect: {
                title: '¡Brillante decisión!',
                explanation: 'Esta situación era una oferta real. El correo venía de la dirección oficial, puedes verificar en la web oficial, el ajuste se aplica automáticamente y no te piden ningún dato. Las empresas de servicios sí hacen ajustes cuando cobran de más. ¡Aprendiste a reconocer lo legítimo!'
            },
            resultIncorrect: {
                title: '¡Cuidado con desconfiar de todo!',
                explanation: 'Entiendo la duda, pero esta vez sí era real. El correo venía de la dirección oficial, el ajuste se aplicaba solo y podías verificarlo en la web oficial. Las empresas de servicios sí devuelven cobros extra. Reconocer lo legítimo también es importante. ¡Vuelve a intentarlo!'
            }
        },
        // ===== NIVEL 5: RETO FINAL (3 sub-situaciones, laberinto enorme) =====
        {
            level: 5,
            theme: 'Reto Final',
            maze: mazeHuge,
            subLevels: [
                {
                    label: 'Reto 1 de 3 — Compra',
                    situation: 'Ves un anuncio en redes sociales: "Liquidación total por cierre de tienda. Electrodomésticos al 80% de descuento, solo quedan 5 unidades." Al hacer clic, la página te pide pagar por adelantado con transferencia y no muestra dirección física, teléfono ni datos de la empresa.',
                    answer: 'engano',
                    timerDuration: 5000,
                    enemies: [
                        { x: 5, y: 3, shape: 'diamond', interval: 330, behavior: 'chase' },
                        { x: 19, y: 3, shape: 'diamond', interval: 340, behavior: 'chase' },
                        { x: 12, y: 5, shape: 'triangle', interval: 320, behavior: 'random' },
                        { x: 5, y: 13, shape: 'diamond', interval: 335, behavior: 'chase' },
                        { x: 19, y: 13, shape: 'circle', interval: 325, behavior: 'random' },
                    ],
                    resultCorrect: {
                        title: '¡Bien detectado!',
                        explanation: 'Las páginas sin datos de contacto, con descuentos exagerados y que piden pago anticipado por transferencia son señales claras de estafa. Siempre busca la dirección y datos de la empresa antes de comprar. ¡Vamos con el siguiente reto!'
                    },
                    resultIncorrect: {
                        title: 'Recuerda lo que aprendimos',
                        explanation: 'Esa situación era un engaño. Una página sin dirección, sin teléfono y que pide transferencia anticipada es muy riesgosa. Siempre verifica los datos de la empresa antes de comprar. Vuelve al laberinto, ¡tú puedes!'
                    }
                },
                {
                    label: 'Reto 2 de 3 — Mensaje',
                    situation: 'Tu operador de celular te envía un SMS desde su número oficial invitándote a renovar tu plan con un 10% de descuento. Al abrir su app oficial, ves la misma promoción en la sección de ofertas. Puedes contratar directamente desde la app sin ingresar datos adicionales.',
                    answer: 'oferta',
                    timerDuration: 5000,
                    enemies: [
                        { x: 5, y: 3, shape: 'diamond', interval: 330, behavior: 'chase', teleportInterval: 5000 },
                        { x: 19, y: 3, shape: 'diamond', interval: 340, behavior: 'chase', teleportInterval: 5500 },
                        { x: 12, y: 7, shape: 'diamond', interval: 350, behavior: 'chase', teleportInterval: 6000 },
                        { x: 5, y: 13, shape: 'diamond', interval: 335, behavior: 'chase', teleportInterval: 4500 },
                        { x: 19, y: 13, shape: 'diamond', interval: 345, behavior: 'chase', teleportInterval: 5200 },
                    ],
                    resultCorrect: {
                        title: '¡Excelente!',
                        explanation: 'Supiste identificar una oferta real. El SMS venía del número oficial, la promoción estaba en la app y no pedían datos extra. Las empresas legítimas sí envían promociones reales por sus canales oficiales. ¡Un reto más!'
                    },
                    resultIncorrect: {
                        title: 'No todo es engaño',
                        explanation: 'Esta vez era una oferta real. El SMS venía del número oficial, la promoción aparecía en la app y no pedían datos extra. Si puedes verificar en canales oficiales, es confiable. Vuelve al laberinto, ¡tú puedes!'
                    }
                },
                {
                    label: 'Reto 3 de 3 — Hogar',
                    situation: 'Una persona toca tu puerta y dice que trabaja para tu compañía de internet. Te ofrece cambiar a un plan más rápido y barato, pero no muestra identificación, no da un número de referencia y te pide que firmes un contrato y pagues la primera cuota en efectivo en ese momento.',
                    answer: 'engano',
                    timerDuration: 5000,
                    enemies: [
                        { x: 5, y: 3, shape: 'spitter', interval: 320, behavior: 'chase', spitRange: 6, spitCooldown: 2500, teleportInterval: 4500 },
                        { x: 19, y: 3, shape: 'spitter', interval: 330, behavior: 'chase', spitRange: 6, spitCooldown: 2800, teleportInterval: 5000 },
                        { x: 12, y: 9, shape: 'diamond', interval: 310, behavior: 'chase', teleportInterval: 4000 },
                        { x: 5, y: 13, shape: 'spitter', interval: 325, behavior: 'chase', spitRange: 6, spitCooldown: 2600, teleportInterval: 4800 },
                        { x: 19, y: 13, shape: 'diamond', interval: 315, behavior: 'chase', teleportInterval: 4200 },
                    ],
                    resultCorrect: {
                        title: '¡Perfecto!',
                        explanation: 'Nunca confíes en alguien que llega sin identificación y te pide dinero o firma en el momento. Si alguien dice ser de una empresa, pide su identificación y llama tú directamente a la compañía para confirmarlo. ¡Siempre es mejor verificar!'
                    },
                    resultIncorrect: {
                        title: 'Cuidado en casa también',
                        explanation: 'Esa situación era un engaño. Cuando alguien llega a tu puerta sin identificación y te pide firmar o pagar de inmediato, es una señal de alerta. Siempre verifica llamando directamente a la compañía. Vuelve al laberinto, ¡tú puedes!'
                    }
                }
            ]
        }
    ];

    let currentLevel = 0;
    let currentSubLevel = 0;

    // ===== VIDAS =====
    const MAX_LIVES = 5;
    let lives = MAX_LIVES;

    // ===== ESTADO DEL LABERINTO (dinámico por nivel) =====
    let mazeMap = [];
    let COLS = 0;
    let ROWS = 0;
    let MAZE_CENTER = { x: 0, y: 0 };
    let PLAYER_START = { x: 0, y: 0 };
    const TILE = 38;
    const PLAYER_LERP = 0.22;
    const ENEMY_LERP = 0.18;

    // ===== ELEMENTOS =====
    const phaseLevel = document.getElementById('phase-level');
    const phaseSituation = document.getElementById('phase-situation');
    const phaseMaze = document.getElementById('phase-maze');
    const btnLevelNext = document.getElementById('btn-level-next');
    const levelLabel = document.getElementById('level-label');
    const levelTitle = document.getElementById('level-title');
    const situationLabel = document.getElementById('situation-label');
    const situationText = document.getElementById('situation-text');
    const timerBar = document.getElementById('timer-bar');
    const situationMiniText = document.getElementById('situation-mini-text');
    const situationMini = document.getElementById('situation-mini');
    const heartsContainer = document.getElementById('hearts-container');
    const canvas = document.getElementById('maze-canvas');
    const ctx = canvas ? canvas.getContext('2d') : null;

    if (!canvas || !ctx) return;

    // ===== ESTADO =====
    let player = {
        x: 0, y: 0,
        drawX: 0, drawY: 0,
        hit: false,
        hitTimer: 0,
        invincible: false
    };
    let enemies = [];
    let projectiles = [];
    let enemyTimers = [];
    let gameRunning = false;
    let lumenImg = new Image();
    lumenImg.src = window.GAME_ASSETS.lumen;

    // ===== HELPER: DATOS ACTIVOS (maneja sub-niveles) =====
    function getActiveData() {
        const data = levels[currentLevel];
        if (data.subLevels) {
            const sub = data.subLevels[currentSubLevel];
            return {
                level: data.level,
                theme: data.theme,
                maze: data.maze,
                situation: sub.situation,
                answer: sub.answer,
                timerDuration: sub.timerDuration,
                enemies: sub.enemies,
                resultCorrect: sub.resultCorrect,
                resultIncorrect: sub.resultIncorrect,
                label: sub.label
            };
        }
        return data;
    }

    // ===== CORAZONES =====
    function renderHearts() {
        heartsContainer.innerHTML = '';
        for (let i = 0; i < MAX_LIVES; i++) {
            const heart = document.createElement('span');
            heart.className = 'heart' + (i >= lives ? ' broken' : '');
            heart.textContent = '🛡';
            heart.id = 'heart-' + i;
            heartsContainer.appendChild(heart);
        }
    }

    function breakHeart() {
        lives--;
        const heartEl = document.getElementById('heart-' + lives);
        if (heartEl) {
            heartEl.classList.add('breaking');
            setTimeout(() => {
                heartEl.classList.remove('breaking');
                heartEl.classList.add('broken');
            }, 600);
        }
    }

    // ===== ESTRELLAS =====
    let totalStars = 0;

    function getStars() {
        if (lives >= 4) return 3;
        if (lives >= 2) return 2;
        if (lives >= 1) return 1;
        return 0;
    }

    function saveStarsToServer(stars) {
        if (!window.SAVE_RECORD_URL || !window.CSRF_TOKEN) return;
        fetch(window.SAVE_RECORD_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN,
            },
            body: JSON.stringify({
                game: 'stars_OfertaOEngano',
                score: stars,
            }),
        }).catch(function () {});
    }

    function buildStarsHTML(count) {
        let html = '<div class="stars-container">';
        for (let i = 0; i < 3; i++) {
            const cls = i < count ? 'filled' : 'empty';
            const ch = i < count ? '★' : '☆';
            html += '<span class="star ' + cls + '">' + ch + '</span>';
        }
        html += '</div>';
        return html;
    }

    // ===== MINI SITUACIÓN EXPANDIBLE =====
    situationMini.addEventListener('click', () => {
        situationMini.classList.toggle('expanded');
    });

    // ===== INICIAR NIVEL =====
    function initLevel(levelIndex, preserveLives) {
        const data = levels[levelIndex];
        const active = getActiveData();

        // Actualizar cartel
        levelLabel.textContent = t('Nivel') + ' ' + data.level;
        levelTitle.textContent = t(data.theme);

        // Etiqueta de situación
        if (data.subLevels) {
            situationLabel.textContent = t(data.subLevels[currentSubLevel].label);
        } else {
            situationLabel.textContent = t('Situación') + ' ' + data.level;
        }

        // Clonar mapa del nivel
        const baseMaze = data.maze;
        mazeMap = baseMaze.map(row => [...row]);
        ROWS = mazeMap.length;
        COLS = mazeMap[0].length;

        // Redimensionar canvas
        canvas.width = COLS * TILE;
        canvas.height = ROWS * TILE;

        // Limpiar marcador de inicio
        for (let r = 0; r < ROWS; r++) {
            for (let c = 0; c < COLS; c++) {
                if (mazeMap[r][c] === 2) {
                    mazeMap[r][c] = 0;
                }
            }
        }

        // Calcular centro caminable (buscar celda libre más cercana al centro real)
        const idealX = Math.floor(COLS / 2);
        const idealY = Math.floor(ROWS / 2);
        let bestDist = Infinity;
        MAZE_CENTER = { x: idealX, y: idealY };
        for (let r = 0; r < ROWS; r++) {
            for (let c = 0; c < COLS; c++) {
                if (mazeMap[r][c] === 0) {
                    const dist = Math.abs(c - idealX) + Math.abs(r - idealY);
                    if (dist < bestDist) {
                        bestDist = dist;
                        MAZE_CENTER = { x: c, y: r };
                    }
                }
            }
        }

        // Resetear jugador — siempre inicia en el centro (zona segura)
        player.x = MAZE_CENTER.x;
        player.y = MAZE_CENTER.y;
        player.drawX = MAZE_CENTER.x * TILE;
        player.drawY = MAZE_CENTER.y * TILE;
        player.hit = false;
        player.hitTimer = 0;
        player.invincible = false;

        // Crear enemigos (del sub-nivel si aplica)
        const enemyData = active.enemies;
        enemies = enemyData.map(e => ({
            x: e.x,
            y: e.y,
            drawX: e.x * TILE,
            drawY: e.y * TILE,
            shape: e.shape,
            interval: e.interval,
            behavior: e.behavior || 'random',
            spitRange: e.spitRange || 0,
            spitCooldown: e.spitCooldown || 3000,
            lastSpit: 0,
            prevX: -1,
            prevY: -1,
            teleportInterval: e.teleportInterval || 0,
            lastTeleport: Date.now(),
            teleportFlash: 0
        }));

        // Limpiar proyectiles y timers
        projectiles = [];
        enemyTimers.forEach(t => clearTimeout(t));
        enemyTimers = [];

        // Vidas: resetear solo si no se preservan
        if (!preserveLives) {
            lives = MAX_LIVES;
        }
        renderHearts();

        // Colapsar mini situación
        situationMini.classList.remove('expanded');

        gameRunning = false;
    }

    // Iniciar nivel 1
    initLevel(0);

    // ===== FASES =====
    btnLevelNext.addEventListener('click', () => {
        phaseLevel.style.display = 'none';
        phaseSituation.style.display = 'flex';
        showSituation();
    });

    function showSituation() {
        const active = getActiveData();
        const text = t(active.situation);
        situationText.textContent = '';
        timerBar.style.width = '100%';

        // Actualizar label para sub-niveles
        if (levels[currentLevel].subLevels) {
            situationLabel.textContent = t(levels[currentLevel].subLevels[currentSubLevel].label);
        }

        let i = 0;
        function type() {
            if (i < text.length) {
                situationText.textContent += text[i];
                i++;
                setTimeout(type, 25);
            } else {
                startTimer();
            }
        }
        type();
    }

    function startTimer() {
        const active = getActiveData();
        const duration = active.timerDuration;
        const start = Date.now();
        const TICK_INTERVAL = 30;
        function tick() {
            const remaining = Math.max(0, 1 - (Date.now() - start) / duration);
            timerBar.style.width = (remaining * 100) + '%';
            if (remaining > 0) {
                setTimeout(tick, TICK_INTERVAL);
            } else {
                setTimeout(() => {
                    phaseSituation.style.display = 'none';
                    phaseMaze.style.display = 'flex';
                    situationMiniText.textContent = t(active.situation);
                    startMaze();
                }, 400);
            }
        }
        setTimeout(tick, TICK_INTERVAL);
    }

    // ===== LABERINTO =====
    function startMaze() {
        gameRunning = true;
        gameLoop();
        enemies.forEach(e => moveEnemy(e));
        projectileLoop();
    }

    function resumeMaze() {
        gameRunning = true;
        gameLoop();
        enemies.forEach(e => moveEnemy(e));
        projectileLoop();
    }

    function lerp(a, b, t) {
        return a + (b - a) * t;
    }

    // ===== ENEMIGOS =====
    function getWalkable(x, y) {
        const out = [];
        [[0,-1],[0,1],[-1,0],[1,0]].forEach(([dx,dy]) => {
            const nx = x + dx, ny = y + dy;
            if (nx >= 0 && nx < COLS && ny >= 0 && ny < ROWS && mazeMap[ny][nx] === 0) {
                out.push({ x: nx, y: ny });
            }
        });
        return out;
    }

    function moveEnemy(enemy) {
        if (!gameRunning) return;

        const options = getWalkable(enemy.x, enemy.y);
        if (options.length > 0) {
            let pick;

            if (enemy.behavior === 'chase') {
                let choices = options.filter(o => !(o.x === enemy.prevX && o.y === enemy.prevY));
                if (choices.length === 0) choices = options;
                choices.sort((a, b) => {
                    const distA = Math.abs(a.x - player.x) + Math.abs(a.y - player.y);
                    const distB = Math.abs(b.x - player.x) + Math.abs(b.y - player.y);
                    return distA - distB;
                });
                pick = (Math.random() < 0.75) ? choices[0] : choices[Math.floor(Math.random() * choices.length)];
            } else {
                let choices = options.filter(o => !(o.x === enemy.prevX && o.y === enemy.prevY));
                if (choices.length === 0) choices = options;
                pick = choices[Math.floor(Math.random() * choices.length)];
            }

            enemy.prevX = enemy.x;
            enemy.prevY = enemy.y;
            enemy.x = pick.x;
            enemy.y = pick.y;
        }

        // Teletransporte
        if (enemy.teleportInterval > 0) {
            const now = Date.now();
            if (now - enemy.lastTeleport > enemy.teleportInterval) {
                // Buscar celdas caminables lejos del jugador
                const walkable = [];
                for (let ty = 0; ty < ROWS; ty++) {
                    for (let tx = 0; tx < COLS; tx++) {
                        if (mazeMap[ty][tx] === 0) {
                            const distToPlayer = Math.abs(tx - player.x) + Math.abs(ty - player.y);
                            if (distToPlayer >= 4 && distToPlayer <= 10) {
                                walkable.push({ x: tx, y: ty });
                            }
                        }
                    }
                }
                if (walkable.length > 0) {
                    const dest = walkable[Math.floor(Math.random() * walkable.length)];
                    enemy.x = dest.x;
                    enemy.y = dest.y;
                    enemy.drawX = dest.x * TILE;
                    enemy.drawY = dest.y * TILE;
                    enemy.prevX = -1;
                    enemy.prevY = -1;
                    enemy.teleportFlash = Date.now();
                }
                enemy.lastTeleport = now;
            }
        }

        // Escupir si tiene rango
        if (enemy.spitRange > 0) {
            const dist = Math.abs(enemy.x - player.x) + Math.abs(enemy.y - player.y);
            const now = Date.now();
            if (dist <= enemy.spitRange && now - enemy.lastSpit > enemy.spitCooldown) {
                spitProjectile(enemy);
                enemy.lastSpit = now;
            }
        }

        checkCollision();
        const timer = setTimeout(() => moveEnemy(enemy), enemy.interval);
        enemyTimers.push(timer);
    }

    // ===== PROYECTILES =====
    function spitProjectile(enemy) {
        const dx = player.x - enemy.x;
        const dy = player.y - enemy.y;
        let dirX = 0, dirY = 0;
        if (Math.abs(dx) >= Math.abs(dy)) {
            dirX = dx > 0 ? 1 : -1;
        } else {
            dirY = dy > 0 ? 1 : -1;
        }
        projectiles.push({
            x: enemy.x, y: enemy.y,
            dirX: dirX, dirY: dirY,
            drawX: enemy.x * TILE, drawY: enemy.y * TILE,
            life: 8
        });
    }

    function moveProjectiles() {
        if (!gameRunning) return;
        for (let i = projectiles.length - 1; i >= 0; i--) {
            const p = projectiles[i];
            const nx = p.x + p.dirX;
            const ny = p.y + p.dirY;

            if (nx < 0 || nx >= COLS || ny < 0 || ny >= ROWS || mazeMap[ny][nx] === 1) {
                projectiles.splice(i, 1);
                continue;
            }

            p.x = nx;
            p.y = ny;
            p.life--;

            if (p.life <= 0) {
                projectiles.splice(i, 1);
                continue;
            }

            if (p.x === player.x && p.y === player.y && !player.hit && !player.invincible && !isInSafeZone()) {
                projectiles.splice(i, 1);
                triggerHit();
            }
        }
    }

    function projectileLoop() {
        if (!gameRunning) return;
        moveProjectiles();
        setTimeout(projectileLoop, 120);
    }

    // ===== COLISIÓN =====
    function isInSafeZone() {
        return player.x === MAZE_CENTER.x && player.y === MAZE_CENTER.y;
    }

    function checkCollision() {
        if (player.hit || player.invincible || isInSafeZone()) return;
        for (const e of enemies) {
            if (e.x === player.x && e.y === player.y) {
                triggerHit();
                return;
            }
        }
        for (let i = projectiles.length - 1; i >= 0; i--) {
            if (projectiles[i].x === player.x && projectiles[i].y === player.y) {
                projectiles.splice(i, 1);
                triggerHit();
                return;
            }
        }
    }

    function triggerHit() {
        player.hit = true;
        player.hitTimer = Date.now();
        player.invincible = true;

        breakHeart();

        if (lives <= 0) {
            setTimeout(() => {
                player.hit = false;
                gameRunning = false;
                showGameOver();
            }, 900);
            return;
        }

        setTimeout(() => {
            player.x = MAZE_CENTER.x;
            player.y = MAZE_CENTER.y;
            player.drawX = MAZE_CENTER.x * TILE;
            player.drawY = MAZE_CENTER.y * TILE;
            player.hit = false;

            setTimeout(() => {
                player.invincible = false;
            }, 500);
        }, 900);
    }

    // ===== GAME OVER (amigable para TEA) =====
    function showGameOver() {
        const ov = document.createElement('div');
        ov.className = 'result-overlay';

        ov.innerHTML =
            '<div class="result-card" style="border-color: rgba(201, 168, 76, 0.35);">' +
                '<div class="result-header">' +
                    '<img src="' + window.GAME_ASSETS.lumen + '" class="result-lumen" alt="Lumen">' +
                    '<div class="result-title-area">' +
                        '<h2 class="result-title" style="color: #c9a84c;">' + t('¡Necesitamos recargar energías!') + '</h2>' +
                    '</div>' +
                '</div>' +
                buildStarsHTML(0) +
                '<p class="result-explanation">' + t('Los enemigos eran muchos esta vez, pero cada intento nos enseña algo nuevo. Lumen sabe que puedes lograrlo. ¿Vamos de nuevo? ¡Ya conoces mejor el camino!') + '</p>' +
                '<button class="result-btn" id="btn-gameover-retry">' + t('¡Vamos de nuevo!') + '</button>' +
            '</div>';

        document.body.appendChild(ov);
        requestAnimationFrame(() => ov.classList.add('visible'));

        document.getElementById('btn-gameover-retry').addEventListener('click', () => {
            ov.classList.remove('visible');
            setTimeout(() => {
                ov.remove();
                // Si es nivel con sub-niveles, reiniciar desde el primer reto
                currentSubLevel = 0;
                initLevel(currentLevel);
                phaseMaze.style.display = 'none';
                phaseSituation.style.display = 'none';
                phaseLevel.style.display = 'flex';

                const card = phaseLevel.querySelector('.level-card');
                card.style.animation = 'none';
                card.offsetHeight;
                card.style.animation = '';
            }, 300);
        });
    }

    // ===== PANTALLA FINAL: ¡MISIÓN COMPLETADA! =====
    function showFinalCompletion() {
        gameRunning = false;
        const stars = getStars();

        // Guardar estrellas del nivel 5 (sub-niveles) en BD
        totalStars += stars;
        saveStarsToServer(totalStars);

        const ov = document.createElement('div');
        ov.className = 'result-overlay';

        ov.innerHTML =
            '<div class="result-card" style="border-color: rgba(74, 222, 128, 0.4); box-shadow: 0 10px 50px rgba(74, 222, 128, 0.15), 0 10px 40px rgba(0,0,0,0.5);">' +
                '<div class="result-header">' +
                    '<img src="' + window.GAME_ASSETS.lumen + '" class="result-lumen" alt="Lumen">' +
                    '<div class="result-title-area">' +
                        '<h2 class="result-title" style="color: #4ade80; font-size: 1.6rem;">' + t('¡Misión Completada!') + '</h2>' +
                        '<span class="result-badge" style="background: rgba(74,222,128,0.15); color: #4ade80; border-color: rgba(74,222,128,0.3);">' +
                            t('Has completado Oferta o Engaño') +
                        '</span>' +
                    '</div>' +
                '</div>' +
                buildStarsHTML(stars) +
                '<p class="result-explanation" style="font-size: 0.95rem;">' +
                    t('Demostraste que puedes identificar situaciones del día a día y tomar buenas decisiones. Recuerda siempre: observa las señales, no te apresures y, si algo parece demasiado bueno, ¡investiga antes de actuar!') + '<br><br>' +
                    '<strong style="color: #c9a84c;">' + t('Lumen está muy orgulloso de ti.') + '</strong>' +
                '</p>' +
                '<button class="result-btn" id="btn-final-home">' + t('Volver al inicio') + '</button>' +
            '</div>';

        document.body.appendChild(ov);
        requestAnimationFrame(() => ov.classList.add('visible'));

        document.getElementById('btn-final-home').addEventListener('click', () => {
            ov.classList.remove('visible');
            setTimeout(() => {
                ov.remove();
                // Si hay URL de menú (integración con Pharenia), redirigir allí
                if (window.GAME_MENU_URL) {
                    window.location.href = window.GAME_MENU_URL;
                    return;
                }
                const transitionOverlay = document.getElementById('transition-overlay');
                transitionOverlay.classList.add('active');
                setTimeout(() => {
                    document.getElementById('screen-game').classList.remove('active');
                    document.getElementById('screen-intro').classList.add('active');
                    currentLevel = 0;
                    currentSubLevel = 0;
                    initLevel(0);
                    phaseLevel.style.display = 'flex';
                    phaseSituation.style.display = 'none';
                    phaseMaze.style.display = 'none';
                    setTimeout(() => transitionOverlay.classList.remove('active'), 600);
                }, 800);
            }, 300);
        });
    }

    // ===== DIBUJAR =====
    function drawMaze() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let r = 0; r < ROWS; r++) {
            for (let c = 0; c < COLS; c++) {
                const x = c * TILE, y = r * TILE;
                const cell = mazeMap[r][c];

                if (cell === 1) {
                    ctx.fillStyle = '#1a1045';
                    ctx.fillRect(x, y, TILE, TILE);
                    ctx.strokeStyle = '#3a2880';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(x + 1, y + 1, TILE - 2, TILE - 2);
                } else if (cell === 0) {
                    ctx.fillStyle = '#0d0825';
                    ctx.fillRect(x, y, TILE, TILE);
                } else if (cell === 3) {
                    ctx.fillStyle = '#0a2e1a';
                    ctx.fillRect(x, y, TILE, TILE);
                } else if (cell === 4) {
                    ctx.fillStyle = '#2e1a0a';
                    ctx.fillRect(x, y, TILE, TILE);
                }
            }
        }

        // Zona segura (brillo suave en el centro)
        const safeX = MAZE_CENTER.x * TILE;
        const safeY = MAZE_CENTER.y * TILE;
        const safePulse = 0.3 + Math.sin(Date.now() / 800) * 0.15;
        ctx.save();
        ctx.fillStyle = 'rgba(96, 176, 224, ' + safePulse + ')';
        ctx.fillRect(safeX, safeY, TILE, TILE);
        ctx.strokeStyle = 'rgba(96, 176, 224, 0.5)';
        ctx.lineWidth = 1.5;
        ctx.strokeRect(safeX + 1, safeY + 1, TILE - 2, TILE - 2);
        ctx.restore();

        // Etiquetas
        const centerX = Math.floor(COLS / 2) * TILE + TILE / 2;

        ctx.save();
        ctx.fillStyle = '#4ade80';
        ctx.font = 'bold 18px Fredoka';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.shadowColor = '#4ade8060';
        ctx.shadowBlur = 10;
        ctx.fillText(t('OFERTA'), centerX, TILE / 2);
        ctx.restore();

        ctx.save();
        ctx.fillStyle = '#f0a050';
        ctx.font = 'bold 18px Fredoka';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.shadowColor = '#f0a05060';
        ctx.shadowBlur = 10;
        ctx.fillText(t('ENGAÑO'), centerX, (ROWS - 1) * TILE + TILE / 2);
        ctx.restore();

        drawArrow(centerX, TILE - 4, 'up', '#4ade80');
        drawArrow(centerX, (ROWS - 1) * TILE + 4, 'down', '#f0a050');

        // Proyectiles
        projectiles.forEach(p => {
            p.drawX = lerp(p.drawX, p.x * TILE, 0.35);
            p.drawY = lerp(p.drawY, p.y * TILE, 0.35);
            const px = p.drawX + TILE / 2;
            const py = p.drawY + TILE / 2;
            const pulse = 0.8 + Math.sin(Date.now() / 100) * 0.2;

            ctx.save();
            ctx.shadowColor = '#00ffcc';
            ctx.shadowBlur = 12;
            ctx.fillStyle = '#00e6b8';
            ctx.beginPath();
            ctx.arc(px, py, 5 * pulse, 0, Math.PI * 2);
            ctx.fill();
            ctx.shadowBlur = 0;
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(px, py, 2, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        });

        // Enemigos
        enemies.forEach(enemy => {
            enemy.drawX = lerp(enemy.drawX, enemy.x * TILE, ENEMY_LERP);
            enemy.drawY = lerp(enemy.drawY, enemy.y * TILE, ENEMY_LERP);
            const ex = enemy.drawX + TILE / 2;
            const ey = enemy.drawY + TILE / 2;
            const size = TILE * 0.32;

            // Efecto visual de teletransporte (resplandor cyan)
            if (enemy.teleportFlash > 0) {
                const elapsed = Date.now() - enemy.teleportFlash;
                if (elapsed < 500) {
                    const alpha = 1 - (elapsed / 500);
                    const radius = TILE * 0.6 + (elapsed / 500) * TILE * 0.4;
                    ctx.save();
                    ctx.globalAlpha = alpha * 0.6;
                    ctx.beginPath();
                    ctx.arc(ex, ey, radius, 0, Math.PI * 2);
                    ctx.fillStyle = '#00e5ff';
                    ctx.shadowColor = '#00e5ff';
                    ctx.shadowBlur = 20;
                    ctx.fill();
                    ctx.restore();
                } else {
                    enemy.teleportFlash = 0;
                }
            }

            // ── DUDA (?) — representa la confusión, color azul grisáceo
            if (enemy.shape === 'circle') {
                const pulse = 0.9 + Math.sin(Date.now() / 300) * 0.1;
                ctx.save();
                ctx.translate(ex, ey);
                // Cuerpo circular
                ctx.beginPath();
                ctx.arc(0, 0, size * pulse, 0, Math.PI * 2);
                ctx.fillStyle = '#5a7a9a';
                ctx.shadowColor = '#7a9aba';
                ctx.shadowBlur = 6;
                ctx.fill();
                ctx.strokeStyle = '#7a9aba';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.shadowBlur = 0;
                // Signo de interrogación
                ctx.fillStyle = '#fff';
                ctx.font = 'bold ' + Math.round(size * 1.6) + 'px Fredoka';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('?', 0, 1);
                ctx.restore();

            // ── PRISA (reloj) — representa la presión/urgencia, color naranja suave
            } else if (enemy.shape === 'triangle') {
                const angle = Math.sin(Date.now() / 400) * 0.15;
                const handAngle = (Date.now() / 300) % (Math.PI * 2);
                ctx.save();
                ctx.translate(ex, ey);
                ctx.rotate(angle);
                // Cuerpo circular (reloj)
                ctx.beginPath();
                ctx.arc(0, 0, size, 0, Math.PI * 2);
                ctx.fillStyle = '#c08030';
                ctx.shadowColor = '#e0a050';
                ctx.shadowBlur = 6;
                ctx.fill();
                ctx.strokeStyle = '#e0a050';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.shadowBlur = 0;
                // Manecilla del reloj girando
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(0, 0);
                ctx.lineTo(Math.cos(handAngle) * size * 0.6, Math.sin(handAngle) * size * 0.6);
                ctx.stroke();
                // Centro
                ctx.fillStyle = '#fff';
                ctx.beginPath();
                ctx.arc(0, 0, 2, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();

            // ── TRAMPA (ojo vigilante) — representa el engaño, color púrpura
            } else if (enemy.shape === 'diamond') {
                const pulse = 0.85 + Math.sin(Date.now() / 200) * 0.15;
                ctx.save();
                ctx.translate(ex, ey);
                // Forma de ojo (dos arcos)
                ctx.shadowColor = '#b060d0';
                ctx.shadowBlur = 8;
                ctx.fillStyle = '#7a30a0';
                ctx.beginPath();
                ctx.moveTo(-size * pulse, 0);
                ctx.quadraticCurveTo(0, -size * pulse * 1.2, size * pulse, 0);
                ctx.quadraticCurveTo(0, size * pulse * 1.2, -size * pulse, 0);
                ctx.closePath();
                ctx.fill();
                ctx.strokeStyle = '#b060d0';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.shadowBlur = 0;
                // Pupila que sigue al jugador
                const ddx = player.drawX - enemy.drawX;
                const ddy = player.drawY - enemy.drawY;
                const ddist = Math.max(1, Math.sqrt(ddx*ddx + ddy*ddy));
                const ppx = (ddx / ddist) * 2;
                const ppy = (ddy / ddist) * 1.5;
                ctx.fillStyle = '#fff';
                ctx.beginPath();
                ctx.arc(ppx * 0.3, ppy * 0.3, size * 0.4, 0, Math.PI * 2);
                ctx.fill();
                ctx.fillStyle = '#1a1045';
                ctx.beginPath();
                ctx.arc(ppx, ppy, size * 0.2, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();

            // ── RUMOR (boca que habla) — representa la desinformación, color teal
            } else if (enemy.shape === 'spitter') {
                const pulse = 0.9 + Math.sin(Date.now() / 250) * 0.1;
                const mouthOpen = 0.3 + Math.abs(Math.sin(Date.now() / 400)) * 0.4;
                ctx.save();
                ctx.translate(ex, ey);
                ctx.shadowColor = '#40b0a0';
                ctx.shadowBlur = 10;
                // Cuerpo redondeado
                ctx.beginPath();
                ctx.arc(0, 0, size * pulse, 0, Math.PI * 2);
                ctx.fillStyle = '#1a7a6a';
                ctx.fill();
                ctx.strokeStyle = '#40b0a0';
                ctx.lineWidth = 2;
                ctx.stroke();
                ctx.shadowBlur = 0;
                // Ojos pequeños
                ctx.fillStyle = '#fff';
                ctx.beginPath();
                ctx.arc(-4, -4, 2.5, 0, Math.PI * 2);
                ctx.arc(4, -4, 2.5, 0, Math.PI * 2);
                ctx.fill();
                const sdx = player.drawX - enemy.drawX;
                const sdy = player.drawY - enemy.drawY;
                const sdist = Math.max(1, Math.sqrt(sdx*sdx + sdy*sdy));
                const spx = (sdx / sdist) * 1;
                const spy = (sdy / sdist) * 1;
                ctx.fillStyle = '#0a3a2f';
                ctx.beginPath();
                ctx.arc(-4 + spx, -4 + spy, 1.2, 0, Math.PI * 2);
                ctx.arc(4 + spx, -4 + spy, 1.2, 0, Math.PI * 2);
                ctx.fill();
                // Boca abierta (hablando)
                ctx.fillStyle = '#003d33';
                ctx.beginPath();
                ctx.ellipse(0, 3, size * 0.35, size * mouthOpen, 0, 0, Math.PI * 2);
                ctx.fill();
                // Ondas de sonido saliendo
                const waveAlpha = 0.3 + Math.sin(Date.now() / 200) * 0.2;
                ctx.strokeStyle = 'rgba(64, 176, 160, ' + waveAlpha + ')';
                ctx.lineWidth = 1.5;
                for (let w = 1; w <= 2; w++) {
                    const wRadius = size * (1.2 + w * 0.3) * pulse;
                    ctx.beginPath();
                    ctx.arc(0, 0, wRadius, -0.4, 0.4);
                    ctx.stroke();
                }
                ctx.restore();
            }
        });

        // Jugador
        player.drawX = lerp(player.drawX, player.x * TILE, PLAYER_LERP);
        player.drawY = lerp(player.drawY, player.y * TILE, PLAYER_LERP);

        if (player.hit) {
            const elapsed = Date.now() - player.hitTimer;
            const blink = Math.floor(elapsed / 100) % 2 === 0;

            if (blink) {
                ctx.save();
                ctx.globalAlpha = 0.5;
                ctx.fillStyle = '#e0a040';
                ctx.beginPath();
                ctx.arc(player.drawX + TILE/2, player.drawY + TILE/2, TILE * 0.4, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();

                if (lumenImg.complete) {
                    ctx.save();
                    ctx.globalAlpha = 0.6;
                    ctx.drawImage(lumenImg, player.drawX + 2, player.drawY + 2, TILE - 4, TILE - 4);
                    ctx.restore();
                }
            }
        } else if (player.invincible) {
            const show = Math.floor(Date.now() / 120) % 2 === 0;
            if (show && lumenImg.complete) {
                ctx.save();
                ctx.globalAlpha = 0.6;
                ctx.drawImage(lumenImg, player.drawX + 2, player.drawY + 2, TILE - 4, TILE - 4);
                ctx.restore();
            }
        } else {
            if (lumenImg.complete) {
                ctx.drawImage(lumenImg, player.drawX + 2, player.drawY + 2, TILE - 4, TILE - 4);
            } else {
                ctx.fillStyle = '#c9a84c';
                ctx.beginPath();
                ctx.arc(player.drawX + TILE/2, player.drawY + TILE/2, TILE * 0.35, 0, Math.PI * 2);
                ctx.fill();
            }
        }
    }

    function drawArrow(x, y, dir, color) {
        ctx.save();
        ctx.fillStyle = color;
        ctx.globalAlpha = 0.5 + Math.sin(Date.now() / 500) * 0.3;
        ctx.beginPath();
        if (dir === 'up') {
            ctx.moveTo(x, y - 6);
            ctx.lineTo(x - 6, y + 2);
            ctx.lineTo(x + 6, y + 2);
        } else {
            ctx.moveTo(x, y + 6);
            ctx.lineTo(x - 6, y - 2);
            ctx.lineTo(x + 6, y - 2);
        }
        ctx.closePath();
        ctx.fill();
        ctx.restore();
    }

    function gameLoop() {
        if (!gameRunning) return;
        drawMaze();
        checkCollision();
        if (document.hidden) {
            setTimeout(gameLoop, 33);
        } else {
            requestAnimationFrame(gameLoop);
        }
    }

    // ===== CONTROLES =====
    document.addEventListener('keydown', (e) => {
        if (!gameRunning || player.hit) return;

        let nx = player.x, ny = player.y;

        switch (e.key) {
            case 'ArrowUp':    case 'w': case 'W': ny--; break;
            case 'ArrowDown':  case 's': case 'S': ny++; break;
            case 'ArrowLeft':  case 'a': case 'A': nx--; break;
            case 'ArrowRight': case 'd': case 'D': nx++; break;
            default: return;
        }
        e.preventDefault();

        if (nx < 0 || nx >= COLS || ny < 0 || ny >= ROWS) return;
        if (mazeMap[ny][nx] === 1) return;

        player.x = nx;
        player.y = ny;

        const cell = mazeMap[ny][nx];
        if (cell === 3) { gameRunning = false; showResult('oferta'); }
        else if (cell === 4) { gameRunning = false; showResult('engano'); }
    });

    // ===== RESULTADO =====
    function showResult(choice) {
        const active = getActiveData();
        const levelData = levels[currentLevel];
        const correct = choice === active.answer;
        const iconColor = correct ? '#4ade80' : '#f0a050';
        const borderColor = correct ? 'rgba(74,222,128,0.35)' : 'rgba(240,160,80,0.35)';

        const answerLabel = active.answer === 'engano' ? t('Engaño') : t('Oferta');
        const resultData = correct ? active.resultCorrect : active.resultIncorrect;
        const title = t(resultData.title);
        const explanation = t(resultData.explanation);

        // Estrellas: solo en niveles regulares completos (no sub-niveles intermedios)
        const isSubLevel = !!levelData.subLevels;
        const isLastSub = isSubLevel && currentSubLevel >= levelData.subLevels.length - 1;
        const showStars = correct && !isSubLevel; // estrellas solo en niveles regulares
        const starsHTML = showStars ? buildStarsHTML(getStars()) : '';

        // Guardar estrellas en BD después de cada nivel regular completado
        if (correct && !isSubLevel) {
            totalStars += getStars();
            saveStarsToServer(totalStars);
        }

        // Texto del botón
        let btnText;
        if (!correct) {
            btnText = t('Volver al laberinto');
        } else if (isSubLevel && !isLastSub) {
            btnText = t('Siguiente reto ▶');
        } else if (isSubLevel && isLastSub) {
            btnText = t('Ver resultado final');
        } else {
            btnText = t('Continuar');
        }

        const ov = document.createElement('div');
        ov.className = 'result-overlay';

        ov.innerHTML =
            '<div class="result-card" style="border-color: ' + borderColor + ';">' +
                '<div class="result-header">' +
                    '<img src="' + window.GAME_ASSETS.lumen + '" class="result-lumen" alt="Lumen">' +
                    '<div class="result-title-area">' +
                        '<h2 class="result-title" style="color: ' + iconColor + ';">' + title + '</h2>' +
                        '<span class="result-badge" style="background: ' + iconColor + '15; color: ' + iconColor + '; border-color: ' + iconColor + '30;">' +
                            t('La respuesta era:') + ' ' + answerLabel +
                        '</span>' +
                    '</div>' +
                '</div>' +
                starsHTML +
                '<p class="result-explanation">' + explanation + '</p>' +
                '<button class="result-btn" id="btn-result-continue">' + btnText + '</button>' +
            '</div>';

        document.body.appendChild(ov);
        requestAnimationFrame(() => ov.classList.add('visible'));

        document.getElementById('btn-result-continue').addEventListener('click', () => {
            ov.classList.remove('visible');

            setTimeout(() => {
                ov.remove();

                if (!correct) {
                    // Volver al laberinto
                    resumeMaze();
                    return;
                }

                // ===== CORRECTO =====
                const transitionOverlay = document.getElementById('transition-overlay');

                if (isSubLevel && !isLastSub) {
                    // Siguiente sub-situación dentro del mismo nivel
                    currentSubLevel++;
                    initLevel(currentLevel, true); // preservar vidas

                    transitionOverlay.classList.add('active');
                    setTimeout(() => {
                        phaseMaze.style.display = 'none';
                        phaseSituation.style.display = 'flex';
                        showSituation();
                        setTimeout(() => transitionOverlay.classList.remove('active'), 600);
                    }, 800);

                } else if (isSubLevel && isLastSub) {
                    // Último sub-nivel completado → pantalla final
                    showFinalCompletion();

                } else if (currentLevel < levels.length - 1) {
                    // Siguiente nivel normal
                    currentLevel++;
                    currentSubLevel = 0;
                    initLevel(currentLevel);

                    transitionOverlay.classList.add('active');
                    setTimeout(() => {
                        phaseMaze.style.display = 'none';
                        phaseSituation.style.display = 'none';
                        phaseLevel.style.display = 'flex';

                        const card = phaseLevel.querySelector('.level-card');
                        card.style.animation = 'none';
                        card.offsetHeight;
                        card.style.animation = '';

                        setTimeout(() => transitionOverlay.classList.remove('active'), 600);
                    }, 800);

                } else {
                    // Último nivel (sin sub-niveles) — volver a intro
                    transitionOverlay.classList.add('active');
                    setTimeout(() => {
                        document.getElementById('screen-game').classList.remove('active');
                        document.getElementById('screen-intro').classList.add('active');
                        currentLevel = 0;
                        currentSubLevel = 0;
                        initLevel(0);
                        phaseLevel.style.display = 'flex';
                        phaseSituation.style.display = 'none';
                        phaseMaze.style.display = 'none';
                        setTimeout(() => transitionOverlay.classList.remove('active'), 600);
                    }, 800);
                }
            }, 300);
        });
    }

});
