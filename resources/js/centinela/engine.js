/**
 * Motor de la patrulla de Centinela.
 *
 * La nave dispara sola; el jugador solo controla el movimiento horizontal
 * (teclado ◄ ► / A D, o arrastrando el puntero). La habilidad que se
 * entrena es la distinción: colocarse bajo las figuras rojas y apartar
 * la línea de fuego cuando pasa una figura verde.
 *
 * Reglas:
 * - Destruir una figura roja (amenaza)      → +1 punto
 * - Alcanzar una figura verde (protegida)   → −1 de integridad (empieza en 10)
 * - Dejar pasar una verde                   → queda protegida, no resta nada
 * - Dejar pasar una roja                    → oportunidad perdida, no resta nada
 * - La partida termina al completar el relevo de figuras o si la
 *   integridad llega a 0.
 *
 * Render: entidades DOM movidas con transform (GPU), bucle con rAF y dt
 * acotado. Con prefers-reduced-motion se reducen partículas y destellos,
 * pero la jugabilidad (velocidades) no cambia: la elige la dificultad.
 */
import { PROTECT_FIGURES, THREAT_FIGURES, DIFFICULTIES, START_INTEGRITY, POWERUPS, POWERUP_DURATION, POWERUP_EVERY } from './figures.js';
import { sndHitThreat, sndHitProtect, sndEnd, sndPower } from './audio.js';

const FIG_R = 24;          // radio de colisión de una figura
const SHIP_Y_OFFSET = 58;  // distancia de la nave al borde inferior
const BOLT_SPEED = 520;    // px/s
const FIRE_EVERY = 0.38;   // s entre disparos automáticos
const SHIP_KEYS_SPEED = 360; // px/s con teclado
const CATCH_DX = 38;       // margen horizontal para recoger tarjetas
const CATCH_DY = 44;       // margen vertical para recoger tarjetas

function pick(arr) {
    return arr[Math.floor(Math.random() * arr.length)];
}

function rand(min, max) {
    return min + Math.random() * (max - min);
}

export function createGame({ arena, onScore, onIntegrity, onProgress, onEnd, onPower }) {
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

    /* ------------------------------------------------------------
     * Nave (se crea una vez y se reutiliza entre partidas)
     * ------------------------------------------------------------ */
    const ship = document.createElement('div');
    ship.className = 'arena-entity ship';
    ship.innerHTML =
        `<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.3"
              stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#fig-nave"/></svg>
         <div class="ship-flame"></div>
         <div class="ship-muzzle"></div>`;
    const shipMuzzle = ship.querySelector('.ship-muzzle');

    /* ------------------------------------------------------------
     * Estado
     * ------------------------------------------------------------ */
    let cfg = null;
    let W = 0;
    let H = 0;
    let rafId = null;
    let lastTime = 0;
    let running = false;
    let paused = false;

    let shipX = 0;
    let pointerX = null;      // objetivo del puntero (null si no se usa)
    const keys = new Set();

    let bolts = [];
    let figures = [];
    let powerups = [];      // tarjetas doradas cayendo
    let fireTimer = 0;
    let spawnTimer = 0;
    let powerTimer = 0;     // reloj para la próxima tarjeta
    let nextPowerIn = 0;    // segundos hasta la próxima tarjeta
    let elapsed = 0;        // reloj de partida (solo avanza jugando)
    let rapidUntil = 0;     // disparo veloz activo hasta este tiempo
    let doubleUntil = 0;    // doble disparo activo hasta este tiempo
    let stats = null;

    function freshStats() {
        return {
            integrity: START_INTEGRITY,
            score: 0,
            redHits: 0,       // amenazas eliminadas
            greenHits: 0,     // protegidas alcanzadas (errores)
            protectedCount: 0,// verdes que pasaron sanas
            escaped: 0,       // rojas que pasaron
            spawned: 0,
            resolved: 0,      // figuras ya resueltas (para el progreso)
        };
    }

    /* ------------------------------------------------------------
     * Creación de entidades
     * ------------------------------------------------------------ */
    function makeBolt(x, silent = false) {
        const el = document.createElement('div');
        el.className = 'arena-entity bolt';
        arena.appendChild(el);
        bolts.push({ x, y: H - SHIP_Y_OFFSET - 30, el });

        // Destello en la punta de la nave (una vez por ráfaga)
        if (!silent) {
            shipMuzzle.classList.remove('is-firing');
            void shipMuzzle.offsetWidth; // reinicia la animación CSS
            shipMuzzle.classList.add('is-firing');
        }
    }

    function makeFigure(type, x, y, speed, pattern) {
        const catalog = type === 'threat' ? THREAT_FIGURES : PROTECT_FIGURES;
        const def = pick(catalog);
        const el = document.createElement('div');
        el.className = `arena-entity fig fig--${type} is-spawning`;
        el.innerHTML =
            `<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.3"
                  stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#${def.id}"/></svg>`;
        arena.appendChild(el);

        const fig = {
            type,
            label: def.label,
            baseX: x,
            x,
            y,
            speed,
            pattern,
            t: rand(0, Math.PI * 2),
            swayAmp: pattern === 'recto' ? 0 : rand(26, 54),
            swayFreq: pattern === 'zigzag' ? rand(1.6, 2.4) : rand(0.9, 1.5),
            el,
            done: false,
        };
        figures.push(fig);
        stats.spawned += 1;
        setTimeout(() => el.classList.remove('is-spawning'), 450);
        return fig;
    }

    /** Aparición normal: una figura sola. */
    function spawnSingle() {
        const type = Math.random() < cfg.redRatio ? 'threat' : 'protect';
        const x = rand(46, W - 46);
        const speed = rand(cfg.speed[0], cfg.speed[1]);
        makeFigure(type, x, -40, speed, pick(cfg.patterns));
    }

    /**
     * Mezcla deliberada (solo difícil): una amenaza escoltada por dos
     * figuras protegidas muy cerca. Obliga a esperar el momento justo.
     */
    function spawnEscort() {
        const cx = rand(120, W - 120);
        const speed = rand(cfg.speed[0], cfg.speed[1]);
        const pattern = pick(cfg.patterns);
        makeFigure('threat', cx, -40, speed, pattern);
        makeFigure('protect', Math.max(46, cx - 68), -104, speed, pattern);
        makeFigure('protect', Math.min(W - 46, cx + 68), -104, speed, pattern);
    }

    function spawnTick(dt) {
        if (stats.spawned >= cfg.total) return;
        spawnTimer += dt;
        if (spawnTimer < cfg.spawnEvery) return;
        if (figures.length >= cfg.maxOnScreen) return;
        spawnTimer = 0;

        const roomForGroup = stats.spawned <= cfg.total - 3;
        if (cfg.escorts && roomForGroup && Math.random() < 0.3) {
            spawnEscort();
        } else {
            spawnSingle();
        }
    }

    /* ------------------------------------------------------------
     * Tarjetas doradas (coleccionables de disparo)
     * ------------------------------------------------------------ */
    function makePowerup() {
        const type = Math.random() < 0.5 ? 'rapid' : 'double';
        const def = POWERUPS[type];
        const el = document.createElement('div');
        el.className = 'arena-entity powerup is-spawning';
        el.innerHTML =
            `<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.3"
                  stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#${def.id}"/></svg>`;
        arena.appendChild(el);

        const baseX = rand(50, W - 50);
        powerups.push({
            type,
            label: def.label,
            baseX,
            x: baseX,
            y: -34,
            speed: 26,
            t: rand(0, Math.PI * 2),
            el,
        });
        setTimeout(() => el.classList.remove('is-spawning'), 450);
    }

    function powerTick(dt) {
        // Nada de tarjetas al principio: primero se aprende lo básico
        if (stats.spawned < 3) return;
        powerTimer += dt;
        if (powerTimer < nextPowerIn) return;
        if (powerups.length >= 1) return;
        powerTimer = 0;
        nextPowerIn = rand(POWERUP_EVERY[0], POWERUP_EVERY[1]);
        makePowerup();
    }

    function collectPowerup(p) {
        if (p.type === 'rapid') rapidUntil = elapsed + POWERUP_DURATION;
        else doubleUntil = elapsed + POWERUP_DURATION;

        p.el.remove();
        powerups = powerups.filter((x) => x !== p);
        burst(p.x, p.y, 'gold');
        floatScore(p.x, p.y, p.label, 'up');
        sndPower();
        onPower?.({ type: p.type, duration: POWERUP_DURATION });
    }

    function removePowerup(p) {
        p.el.classList.add('is-passing');
        setTimeout(() => p.el.remove(), 540);
        powerups = powerups.filter((x) => x !== p);
    }

    /* ------------------------------------------------------------
     * Efectos visuales (partículas y marcadores flotantes)
     * ------------------------------------------------------------ */
    function burst(x, y, kind) {
        const total = motionQuery.matches ? 3 : 9;
        for (let i = 0; i < total; i++) {
            const el = document.createElement('div');
            el.className = `arena-entity particle particle--${kind}`;
            const angle = (Math.PI * 2 * i) / total + rand(-0.3, 0.3);
            const dist = rand(26, 58);
            el.style.setProperty('--dx', `${Math.cos(angle) * dist}px`);
            el.style.setProperty('--dy', `${Math.sin(angle) * dist}px`);
            el.style.transform = `translate3d(${x}px, ${y}px, 0)`;
            arena.appendChild(el);
            setTimeout(() => el.remove(), 620);
        }
    }

    function floatScore(x, y, text, kind) {
        const el = document.createElement('div');
        el.className = `arena-entity score-float score-float--${kind}`;
        el.textContent = text;
        el.style.transform = `translate3d(${x - 8}px, ${y - 10}px, 0)`;
        arena.appendChild(el);
        setTimeout(() => el.remove(), 950);
    }

    /* ------------------------------------------------------------
     * Resolución de figuras
     * ------------------------------------------------------------ */
    function removeFigure(fig) {
        fig.done = true;
        figures = figures.filter((f) => f !== fig);
    }

    function hitThreat(fig) {
        stats.redHits += 1;
        stats.score += 1;
        stats.resolved += 1;
        fig.el.classList.add('is-destroyed');
        setTimeout(() => fig.el.remove(), 360);
        removeFigure(fig);
        burst(fig.x, fig.y, 'gold');
        burst(fig.x, fig.y, 'ember');
        floatScore(fig.x, fig.y, '+1', 'up');
        sndHitThreat();
        onScore(stats);
        onProgress(stats);
    }

    function hitProtect(fig) {
        stats.greenHits += 1;
        stats.integrity -= 1;
        stats.resolved += 1;
        fig.el.classList.add('is-harmed');
        setTimeout(() => fig.el.remove(), 580);
        removeFigure(fig);
        burst(fig.x, fig.y, 'leaf');
        floatScore(fig.x, fig.y, '−1', 'down');
        sndHitProtect();
        onIntegrity(stats);
        onProgress(stats);

        if (stats.integrity <= 0) {
            endGame(true);
        }
    }

    function passFigure(fig) {
        stats.resolved += 1;
        if (fig.type === 'protect') {
            stats.protectedCount += 1;
            burst(fig.x, H - 24, 'leaf');
        } else {
            stats.escaped += 1;
        }
        fig.el.classList.add('is-passing');
        setTimeout(() => fig.el.remove(), 540);
        removeFigure(fig);
        onProgress(stats);
    }

    /* ------------------------------------------------------------
     * Bucle principal
     * ------------------------------------------------------------ */
    function update(dt) {
        elapsed += dt;

        // --- Nave ---
        let dir = 0;
        if (keys.has('arrowleft') || keys.has('a')) dir -= 1;
        if (keys.has('arrowright') || keys.has('d')) dir += 1;

        if (dir !== 0) {
            shipX += dir * SHIP_KEYS_SPEED * dt;
            pointerX = null; // el teclado toma el mando
        } else if (pointerX !== null) {
            // Seguimiento suave del puntero (sin tirones)
            const ease = Math.min(1, dt * 12);
            shipX += (pointerX - shipX) * ease;
        }
        shipX = Math.max(30, Math.min(W - 30, shipX));
        ship.style.transform = `translate3d(${shipX}px, ${H - SHIP_Y_OFFSET}px, 0)`;

        // --- Efectos de tarjetas activos ---
        const rapid = elapsed < rapidUntil;
        const double = elapsed < doubleUntil;
        ship.classList.toggle('is-powered', rapid || double);

        // --- Disparo automático ---
        const fireEvery = rapid ? FIRE_EVERY / 2 : FIRE_EVERY;
        fireTimer += dt;
        if (fireTimer >= fireEvery && bolts.length < 16) {
            fireTimer = 0;
            if (double) {
                makeBolt(shipX - 13);
                makeBolt(shipX + 13, true);
            } else {
                makeBolt(shipX);
            }
        }

        // --- Disparos ---
        for (const b of [...bolts]) {
            b.y -= BOLT_SPEED * dt;
            if (b.y < -24) {
                b.el.remove();
                bolts = bolts.filter((x) => x !== b);
                continue;
            }
            b.el.style.transform = `translate3d(${b.x}px, ${b.y}px, 0)`;
        }

        // --- Figuras ---
        for (const fig of [...figures]) {
            fig.t += dt;
            fig.y += fig.speed * dt;

            if (fig.pattern === 'ondas') {
                fig.x = fig.baseX + Math.sin(fig.t * fig.swayFreq * Math.PI) * fig.swayAmp;
            } else if (fig.pattern === 'zigzag') {
                // asin(sin) = onda triangular: cambios de dirección marcados
                const tri = Math.asin(Math.sin(fig.t * fig.swayFreq * Math.PI)) / (Math.PI / 2);
                fig.x = fig.baseX + tri * fig.swayAmp;
            }
            fig.x = Math.max(30, Math.min(W - 30, fig.x));

            if (fig.y > H + 34) {
                passFigure(fig);
                continue;
            }
            fig.el.style.transform = `translate3d(${fig.x}px, ${fig.y}px, 0)`;
        }

        // --- Tarjetas doradas: caen despacio y se recogen con la nave ---
        for (const p of [...powerups]) {
            p.t += dt;
            p.y += p.speed * dt;
            p.x = Math.max(30, Math.min(W - 30, p.baseX + Math.sin(p.t * 1.1) * 18));

            const shipY = H - SHIP_Y_OFFSET;
            if (Math.abs(p.x - shipX) < CATCH_DX && Math.abs(p.y - shipY) < CATCH_DY) {
                collectPowerup(p);
                continue;
            }
            if (p.y > H + 40) {
                removePowerup(p); // se fue: sin penalización
                continue;
            }
            p.el.style.transform = `translate3d(${p.x}px, ${p.y}px, 0)`;
        }

        // --- Colisiones disparo ↔ figura ---
        for (const b of [...bolts]) {
            for (const fig of [...figures]) {
                const dx = b.x - fig.x;
                const dy = b.y - fig.y;
                if (dx * dx + dy * dy < FIG_R * FIG_R) {
                    b.el.remove();
                    bolts = bolts.filter((x) => x !== b);
                    if (fig.type === 'threat') hitThreat(fig);
                    else hitProtect(fig);
                    if (!running) return; // la integridad llegó a 0 en este impacto
                    break;
                }
            }
        }

        // --- Aparición ---
        spawnTick(dt);
        powerTick(dt);

        // --- Fin por relevo completado ---
        if (stats.spawned >= cfg.total && figures.length === 0 && running) {
            endGame(false);
        }
    }

    function tick(now) {
        if (!running || paused) return;
        const t = now / 1000;
        const dt = Math.min(0.05, t - lastTime);
        lastTime = t;
        update(dt);
        if (running && !paused) rafId = requestAnimationFrame(tick);
    }

    /* ------------------------------------------------------------
     * Fin de partida
     * ------------------------------------------------------------ */
    function endGame(early) {
        if (!running) return;
        running = false;
        if (rafId) cancelAnimationFrame(rafId);
        rafId = null;

        // Limpiar lo que queda en pantalla con calma
        bolts.forEach((b) => b.el.remove());
        bolts = [];
        figures.forEach((f) => {
            f.el.classList.add('is-passing');
            setTimeout(() => f.el.remove(), 540);
        });
        figures = [];
        powerups.forEach((p) => {
            p.el.classList.add('is-passing');
            setTimeout(() => p.el.remove(), 540);
        });
        powerups = [];
        ship.classList.remove('is-powered');

        const total = stats.redHits + stats.greenHits;
        const precision = total === 0 ? 100 : Math.round((stats.redHits / total) * 100);

        sndEnd();
        onEnd({
            earlyEnd: early,
            score: stats.score,
            integrity: Math.max(0, stats.integrity),
            precision,
            protectedCount: stats.protectedCount,
            threats: stats.redHits,
            escaped: stats.escaped,
            difficulty: cfg,
        });
    }

    /* ------------------------------------------------------------
     * Entrada: teclado y puntero
     * ------------------------------------------------------------ */
    function onKeyDown(e) {
        const k = e.key.toLowerCase();
        if (['arrowleft', 'arrowright', 'a', 'd'].includes(k) && running && !paused) {
            e.preventDefault();
            keys.add(k);
        }
    }

    function onKeyUp(e) {
        keys.delete(e.key.toLowerCase());
    }

    function arenaPointer(e) {
        if (!running || paused) return;
        const rect = arena.getBoundingClientRect();
        pointerX = e.clientX - rect.left;
    }

    function onResize() {
        measure();
        shipX = Math.max(30, Math.min(W - 30, shipX));
    }

    function measure() {
        const rect = arena.getBoundingClientRect();
        W = rect.width;
        H = rect.height;
    }

    /* ------------------------------------------------------------
     * API pública
     * ------------------------------------------------------------ */
    function start(difficultyKey) {
        stop();
        cfg = DIFFICULTIES[difficultyKey] ?? DIFFICULTIES.facil;
        measure();

        stats = freshStats();
        shipX = W / 2;
        pointerX = null;
        fireTimer = FIRE_EVERY * 0.6; // primer disparo casi inmediato
        spawnTimer = cfg.spawnEvery * 0.5; // la primera figura no tarda
        elapsed = 0;
        rapidUntil = 0;
        doubleUntil = 0;
        powerTimer = 0;
        nextPowerIn = rand(POWERUP_EVERY[0], POWERUP_EVERY[1]);
        keys.clear();

        arena.appendChild(ship);
        ship.style.transform = `translate3d(${shipX}px, ${H - SHIP_Y_OFFSET}px, 0)`;

        running = true;
        paused = false;
        lastTime = performance.now() / 1000;
        rafId = requestAnimationFrame(tick);

        onScore(stats);
        onIntegrity(stats);
        onProgress(stats);
    }

    function pause() {
        if (!running || paused) return;
        paused = true;
        if (rafId) cancelAnimationFrame(rafId);
        rafId = null;
        keys.clear();
    }

    function resume() {
        if (!running || !paused) return;
        paused = false;
        lastTime = performance.now() / 1000;
        rafId = requestAnimationFrame(tick);
    }

    /** Detiene la partida sin emitir resultados (salir al menú). */
    function stop() {
        running = false;
        paused = false;
        if (rafId) cancelAnimationFrame(rafId);
        rafId = null;
        bolts.forEach((b) => b.el.remove());
        bolts = [];
        figures.forEach((f) => f.el.remove());
        figures = [];
        powerups.forEach((p) => p.el.remove());
        powerups = [];
        ship.classList.remove('is-powered');
        ship.remove();
        keys.clear();
    }

    function isRunning() {
        return running && !paused;
    }

    function isPaused() {
        return running && paused;
    }

    // Listeners globales (una sola vez por instancia de juego)
    window.addEventListener('keydown', onKeyDown);
    window.addEventListener('keyup', onKeyUp);
    arena.addEventListener('pointermove', arenaPointer);
    arena.addEventListener('pointerdown', arenaPointer);
    window.addEventListener('resize', onResize);

    return { start, pause, resume, stop, isRunning, isPaused };
}
