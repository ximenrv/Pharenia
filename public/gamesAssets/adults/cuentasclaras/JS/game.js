(function () {
    'use strict';

    // ---- Traducción (clave español -> idioma activo) ----
    const t = (key) => (window.translations && window.translations[key]) ? window.translations[key] : key;

    // ---- Iconos SVG (línea, currentColor) ----
    const I = {
        home:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.6 12 3l9 7.6"/><path d="M5 9.5V21h14V9.5"/><path d="M9.5 21v-6h5v6"/></svg>',
        food:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v7a2 2 0 0 0 4 0V3M8 10v11"/><path d="M17 3c-1.7 0-3 2.2-3 5s1 4 3 4v9"/></svg>',
        bolt:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 4 14h7l-1 8 9-12h-7z"/></svg>',
        bus:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="12" rx="2"/><path d="M4 11h16"/><circle cx="8" cy="19" r="1.6"/><circle cx="16" cy="19" r="1.6"/><path d="M6 16v2M18 16v2"/></svg>',
        piggy: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 9c1.1 0 2 .9 2 2s-.9 2-2 2v2a2 2 0 0 1-2 2v1a1 1 0 0 1-2 0v-1H9v1a1 1 0 0 1-2 0v-1.3A6 6 0 0 1 9 6h4a6 6 0 0 1 5 3z"/><path d="M9 6l1-2M15 10h.01"/></svg>',
        heart: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.5-9.2-8.6C1.3 8.6 2.6 5.5 5.6 5.1 7.6 4.8 9 6 12 8.5 15-6 17 4.8 18.4 5.1c3 .4 4.3 3.5 2.8 6.3C19 15.5 12 20 12 20z"/></svg>',
        alert: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>',
        check: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
        cross: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>',
        star:  '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 5.9 6.6 1-4.8 4.6 1.1 6.5L12 17.9 6.2 21l1.1-6.5L2.5 9.9l6.6-1z"/></svg>',
    };

    // ---- Categorías fijas ----
    const CATS = [
        { id: 'rent',      label: 'Renta',      type: 'need',   color: '#2c525a', icon: I.home },
        { id: 'food',      label: 'Comida',     type: 'need',   color: '#bf9f2e', icon: I.food },
        { id: 'services',  label: 'Servicios',  type: 'need',   color: '#7c4dff', icon: I.bolt },
        { id: 'transport', label: 'Transporte', type: 'need',   color: '#2f9aa8', icon: I.bus },
        { id: 'savings',   label: 'Ahorro',     type: 'saving', color: '#2f9e6f', icon: I.piggy },
        { id: 'wants',     label: 'Gustos',     type: 'want',   color: '#e0699b', icon: I.heart },
    ];

    // ---- Niveles ----
    const LEVELS = [
        {
            salary: 1000, savingsTarget: 120,
            mins: { rent: 350, food: 150, services: 80, transport: 60 },
            event: null,
            tip: 'Empieza cubriendo lo esencial: renta, comida, servicios y transporte.',
        },
        {
            salary: 950, savingsTarget: 110,
            mins: { rent: 380, food: 160, services: 90, transport: 70 },
            event: { min: 60, text: 'Imprevisto: se descompuso un electrodoméstico y hay que repararlo.' },
            tip: 'Este mes hay un imprevisto. Recuerda: primero lo necesario, luego el ahorro.',
        },
        {
            salary: 850, savingsTarget: 80,
            mins: { rent: 390, food: 160, services: 90, transport: 60 },
            event: { min: 60, text: 'Imprevisto: una consulta médica que tu seguro no cubre.' },
            tip: 'El sueldo es más ajustado. Prioriza lo esencial y ajusta los gustos.',
        },
    ];

    const money = (n) => '$' + Math.round(n).toLocaleString('en-US');

    const app = document.getElementById('cc-app');
    let currentLevel = 0;
    let assign = {};

    // ============================================================
    // INTRO
    // ============================================================
    function renderIntro() {
        app.innerHTML =
            '<section class="cc-screen cc-screen--active">' +
                '<div class="cc-intro">' +
                    '<span class="cc-intro__kicker">' + t('Finanzas de la adultez') + '</span>' +
                    '<h1 class="cc-intro__title">' + t('Cuentas Claras') + '</h1>' +
                    '<div class="cc-intro__stage">' +
                        '<div class="cc-intro__glow"></div>' +
                        '<img class="cc-intro__lumen" src="' + window.GAME_ASSETS.lumen + '" alt="Lumen">' +
                    '</div>' +
                    '<div class="cc-intro__bubble">' + t('Hola, soy Lumen. Cada mes recibes tu sueldo y tú decides cómo repartirlo: cubre primero lo <b>esencial</b>, guarda algo de <b>ahorro</b> y date un <b>gusto</b> si te alcanza. ¡Vamos a tomar buenas decisiones!') + '</div>' +
                    '<button class="cc-btn cc-btn--play" id="cc-start">' + t('Empezar') +
                        '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>' +
                    '</button>' +
                '</div>' +
            '</section>';
        document.getElementById('cc-start').addEventListener('click', () => startLevel(0));
    }

    // ============================================================
    // NIVEL (presupuesto)
    // ============================================================
    function activeCats() {
        const lvl = LEVELS[currentLevel];
        const list = CATS.slice();
        if (lvl.event) {
            list.push({ id: 'event', label: 'Imprevisto', type: 'event', color: '#d99a20', icon: I.alert });
        }
        return list;
    }

    function minFor(catId) {
        const lvl = LEVELS[currentLevel];
        if (catId === 'event') return lvl.event ? lvl.event.min : 0;
        return lvl.mins[catId] || 0;
    }

    function startLevel(index) {
        currentLevel = index;
        assign = {};
        activeCats().forEach(c => { assign[c.id] = 0; });
        renderGame();
    }

    function renderGame() {
        const lvl = LEVELS[currentLevel];
        const cats = activeCats();

        let cards = '';
        cats.forEach(c => {
            const min = minFor(c.id);
            const hint = c.type === 'saving' ? (t('meta') + ' ' + money(lvl.savingsTarget))
                       : c.type === 'want' ? t('lo que te alcance')
                       : (t('mín.') + ' ' + money(min));
            cards +=
                '<div class="cc-cat" id="cat-' + c.id + '" style="--cat:' + c.color + '">' +
                    '<div class="cc-cat__head">' +
                        '<span class="cc-cat__icon" style="background:' + c.color + '">' + c.icon + '</span>' +
                        '<div class="cc-cat__meta">' +
                            '<div class="cc-cat__label">' + t(c.label) + '</div>' +
                            '<div class="cc-cat__min">' + hint + '</div>' +
                        '</div>' +
                        '<div class="cc-cat__amount" id="amt-' + c.id + '">' + money(0) + '</div>' +
                    '</div>' +
                    '<input type="range" class="cc-cat__slider" id="sl-' + c.id + '" min="0" max="' + lvl.salary + '" step="10" value="0">' +
                '</div>';
        });

        const eventHtml = lvl.event
            ? '<div class="cc-event">' +
                '<span class="cc-event__icon">' + I.alert + '</span>' +
                '<span class="cc-event__text">' + t(lvl.event.text) + '</span>' +
              '</div>'
            : '';

        app.innerHTML =
            '<section class="cc-screen cc-screen--active">' +
                '<div class="cc-topbar">' +
                    '<div class="cc-topbar__left">' +
                        '<span class="cc-level">' + t('Nivel') + ' ' + (currentLevel + 1) + ' / ' + LEVELS.length + '</span>' +
                        '<span class="cc-month">' + t('Mes') + ' ' + (currentLevel + 1) + '</span>' +
                    '</div>' +
                    '<div class="cc-salary">' +
                        '<span class="cc-salary__label">' + t('Sueldo del mes') + '</span>' +
                        '<span class="cc-salary__value">' + money(lvl.salary) + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="cc-layout">' +
                    '<div class="cc-categories">' + cards + '</div>' +
                    '<aside class="cc-summary">' +
                        '<div class="cc-meter">' +
                            '<div class="cc-meter__top">' +
                                '<span class="cc-meter__label">' + t('Sin asignar') + '</span>' +
                                '<span class="cc-meter__value" id="cc-remaining">' + money(lvl.salary) + '</span>' +
                            '</div>' +
                            '<div class="cc-meter__bar"><div class="cc-meter__fill" id="cc-fill"></div></div>' +
                        '</div>' +
                        eventHtml +
                        '<div class="cc-tip">' +
                            '<img class="cc-tip__lumen" src="' + window.GAME_ASSETS.lumen + '" alt="Lumen">' +
                            '<span class="cc-tip__text">' + t(lvl.tip) + '</span>' +
                        '</div>' +
                        '<button class="cc-btn cc-confirm" id="cc-confirm">' + t('Confirmar mes') +
                            '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>' +
                        '</button>' +
                    '</aside>' +
                '</div>' +
            '</section>';

        cats.forEach(c => {
            const slider = document.getElementById('sl-' + c.id);
            slider.addEventListener('input', () => {
                assign[c.id] = parseInt(slider.value, 10) || 0;
                updateSummary();
            });
        });
        document.getElementById('cc-confirm').addEventListener('click', evaluate);
        updateSummary();
    }

    function updateSummary() {
        const lvl = LEVELS[currentLevel];
        const total = Object.values(assign).reduce((a, b) => a + b, 0);
        const remaining = lvl.salary - total;

        const remEl = document.getElementById('cc-remaining');
        remEl.textContent = money(remaining);
        remEl.classList.toggle('is-over', remaining < 0);

        const fill = document.getElementById('cc-fill');
        const pct = Math.min(100, (total / lvl.salary) * 100);
        fill.style.width = pct + '%';
        fill.classList.toggle('is-over', remaining < 0);

        // Marcar tarjetas de necesidad cubiertas / faltantes y montos
        activeCats().forEach(c => {
            document.getElementById('amt-' + c.id).textContent = money(assign[c.id]);
            const card = document.getElementById('cat-' + c.id);
            card.classList.remove('cc-cat--ok', 'cc-cat--miss');
            if (c.type === 'need' || c.type === 'event') {
                card.classList.add(assign[c.id] >= minFor(c.id) ? 'cc-cat--ok' : 'cc-cat--miss');
            } else if (c.type === 'saving') {
                if (assign[c.id] >= lvl.savingsTarget) card.classList.add('cc-cat--ok');
            }
        });
    }

    // ============================================================
    // EVALUACIÓN
    // ============================================================
    function evaluate() {
        const lvl = LEVELS[currentLevel];
        const total = Object.values(assign).reduce((a, b) => a + b, 0);
        const remaining = lvl.salary - total;
        const overspent = remaining < 0;

        const needsCovered = ['rent', 'food', 'services', 'transport'].every(id => assign[id] >= minFor(id));
        const eventCovered = !lvl.event || assign.event >= lvl.event.min;
        const savedEnough = assign.savings >= lvl.savingsTarget;

        let stars;
        if (overspent) {
            stars = 1;
        } else if (!needsCovered || !eventCovered) {
            stars = 2;
        } else {
            stars = 3;
            if (savedEnough) stars++;
            if (savedEnough && remaining === 0) stars++;
        }
        stars = Math.max(1, Math.min(5, stars));

        const feedback = [
            { ok: needsCovered && eventCovered, yes: 'Cubriste todos los gastos esenciales', no: 'La próxima puedes cubrir primero los gastos esenciales' },
            { ok: savedEnough, yes: 'Alcanzaste tu meta de ahorro', no: 'La próxima puedes guardar un poco más de ahorro' },
            { ok: !overspent, yes: 'No te pasaste de tu sueldo', no: 'Esta vez gastaste un poco más que tu sueldo' },
        ];

        saveStars(stars);
        renderResult(stars, feedback);
    }

    // ============================================================
    // RESULTADO
    // ============================================================
    function renderResult(stars, feedback) {
        const isLast = currentLevel >= LEVELS.length - 1;
        const titles = { 5: '¡Presupuesto perfecto!', 4: '¡Muy bien administrado!', 3: '¡Buen trabajo!', 2: '¡Vas aprendiendo!', 1: '¡Sigue practicando!' };

        let starsHtml = '';
        for (let i = 0; i < 5; i++) {
            starsHtml += '<span class="cc-star' + (i < stars ? ' is-on' : '') + '" style="animation-delay:' + (i * 0.09) + 's">' + I.star + '</span>';
        }
        let fbHtml = '';
        feedback.forEach(f => {
            fbHtml += '<li class="' + (f.ok ? 'cc-fb-ok' : 'cc-fb-no') + '">' +
                (f.ok ? I.check : I.cross) + '<span>' + t(f.ok ? f.yes : f.no) + '</span></li>';
        });

        const actions = isLast
            ? '<button class="cc-btn cc-btn--ghost" id="cc-retry">' + t('Jugar de nuevo') + '</button>' +
              '<button class="cc-btn" id="cc-finish">' + t('Volver al inicio') + '</button>'
            : '<button class="cc-btn cc-btn--ghost" id="cc-retry">' + t('Reintentar') + '</button>' +
              '<button class="cc-btn" id="cc-next">' + t('Siguiente mes') +
                '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>' +
              '</button>';

        const finalNote = (isLast && stars >= 3)
            ? '<div class="cc-result__feedback" style="margin-top:-6px"><b>' + t('¡Completaste Cuentas Claras! Ya sabes repartir tu sueldo con cabeza.') + '</b></div>'
            : '';

        // Mensaje amable cuando el resultado es bajo, sin regañar (tono para usuarios TEA).
        const encourageNote = (stars < 3)
            ? '<div class="cc-result__feedback" style="margin-top:-4px;justify-content:center;text-align:center;opacity:.9">' + t('No te preocupes, así se aprende y cada mes lo haces mejor. Puedes intentarlo otra vez cuando quieras. ¡Lumen confía en ti!') + '</div>'
            : '';

        const overlay = document.createElement('div');
        overlay.className = 'cc-result';
        overlay.innerHTML =
            '<div class="cc-result__card">' +
                '<img class="cc-result__lumen" src="' + window.GAME_ASSETS.lumen + '" alt="Lumen">' +
                '<h2 class="cc-result__title">' + t(titles[stars]) + '</h2>' +
                '<div class="cc-stars">' + starsHtml + '</div>' +
                '<ul class="cc-result__feedback">' + fbHtml + '</ul>' +
                encourageNote +
                finalNote +
                '<div class="cc-result__actions">' + actions + '</div>' +
            '</div>';
        document.body.appendChild(overlay);
        requestAnimationFrame(() => overlay.classList.add('is-visible'));

        const close = (cb) => {
            overlay.classList.remove('is-visible');
            setTimeout(() => { overlay.remove(); if (cb) cb(); }, 300);
        };
        const retry = document.getElementById('cc-retry');
        if (retry) retry.addEventListener('click', () => close(() => startLevel(isLast ? 0 : currentLevel)));
        const next = document.getElementById('cc-next');
        if (next) next.addEventListener('click', () => close(() => startLevel(currentLevel + 1)));
        const finish = document.getElementById('cc-finish');
        if (finish) finish.addEventListener('click', () => {
            if (window.GAME_MENU_URL) window.location.href = window.GAME_MENU_URL;
        });
    }

    // ============================================================
    // GUARDAR ESTRELLAS EN LA BASE DE DATOS
    // ============================================================
    function saveStars(stars) {
        if (!window.SAVE_RECORD_URL || !window.CSRF_TOKEN) return;
        fetch(window.SAVE_RECORD_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ game: 'stars_CuentasClaras', score: stars }),
        }).catch(() => {});
    }

    // ---- Inicio ----
    renderIntro();
})();
