/**
 * Controlador de UI de Centinela.
 * Conecta el motor (engine.js) con las pantallas del Blade.
 *
 * Flujo: inicio → dificultad → patrulla → telón (cierre de escena) →
 * resultados sobre el telón → jugar de nuevo o volver al menú.
 */
import { createGame } from './engine.js';
import { DIFFICULTIES, START_INTEGRITY, closingMessage } from './figures.js';
import { initSky } from './sky.js';
import { soundEnabled, toggleSound } from './audio.js';

const $ = (id) => document.getElementById(id);

const els = {
    // home
    difficultyGrid: $('difficulty-grid'),
    btnHowto: $('btn-howto'),
    // howto
    btnHowtoBack: $('btn-howto-back'),
    // game
    btnExit: $('btn-exit'),
    btnPause: $('btn-pause'),
    btnSound: $('btn-sound'),
    pauseOverlay: $('pause-overlay'),
    btnResume: $('btn-resume'),
    btnQuitPause: $('btn-quit-pause'),
    arena: $('arena'),
    badgeRapid: $('badge-rapid'),
    badgeDouble: $('badge-double'),
    hudIntegrity: $('hud-integrity'),
    integrityValue: $('integrity-value'),
    integrityPips: $('integrity-pips'),
    hudScore: $('hud-score'),
    scoreValue: $('score-value'),
    progressLabel: $('progress-label'),
    progressFill: $('progress-fill'),
    difficultyChip: $('difficulty-chip'),
    // curtain + results
    curtain: $('curtain'),
    resultsSubtitle: $('results-subtitle'),
    resultsMessage: $('results-message'),
    statScore: $('stat-score'),
    statPrecision: $('stat-precision'),
    statProtected: $('stat-protected'),
    statThreats: $('stat-threats'),
    btnReplay: $('btn-replay'),
    btnHome: $('btn-home'),
};

const BEST_KEY = 'centinela:best';
const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

let game = null;
let currentDifficulty = 'facil';
let lastResult = null;

/* ------------------------------------------------------------
 * Navegación entre pantallas
 * ------------------------------------------------------------ */
function showScreen(name) {
    document.querySelectorAll('.screen').forEach((s) => {
        s.classList.toggle('is-active', s.dataset.screen === name);
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ------------------------------------------------------------
 * Mejores puntuaciones (localStorage, por dificultad)
 * ------------------------------------------------------------ */
function loadBest() {
    try {
        return { ...JSON.parse(localStorage.getItem(BEST_KEY) || '{}') };
    } catch {
        return {};
    }
}

function saveBest(best) {
    try {
        localStorage.setItem(BEST_KEY, JSON.stringify(best));
    } catch {
        // localStorage lleno o deshabilitado: no pasa nada.
    }
}

/* ------------------------------------------------------------
 * Inicio: tarjetas de dificultad
 * ------------------------------------------------------------ */
function renderDifficulties() {
    const best = loadBest();
    els.difficultyGrid.innerHTML = '';

    Object.values(DIFFICULTIES).forEach((d, i) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'difficulty-card';
        btn.style.animationDelay = `${i * 90}ms`;

        const name = document.createElement('span');
        name.className = 'difficulty-card-name';
        name.textContent = d.name;

        const level = document.createElement('span');
        level.className = 'difficulty-card-level';
        level.textContent = `${d.level} · ${d.total} figuras`;

        const desc = document.createElement('span');
        desc.className = 'difficulty-card-desc';
        desc.textContent = d.desc;

        btn.append(name, level, desc);

        if (best[d.key] !== undefined) {
            const record = document.createElement('span');
            record.className = 'difficulty-card-best';
            record.textContent = `Tu mejor puntuación: ${best[d.key]}`;
            btn.appendChild(record);
        }

        btn.addEventListener('click', () => startGame(d.key));
        els.difficultyGrid.appendChild(btn);
    });
}

/* ------------------------------------------------------------
 * HUD
 * ------------------------------------------------------------ */
function renderPips() {
    els.integrityPips.innerHTML = '';
    for (let i = 0; i < START_INTEGRITY; i++) {
        const pip = document.createElement('span');
        pip.className = 'hud-pip';
        els.integrityPips.appendChild(pip);
    }
}

function onScore(stats) {
    els.scoreValue.textContent = String(stats.score);
}

function onIntegrity(stats) {
    els.integrityValue.textContent = String(Math.max(0, stats.integrity));
    [...els.integrityPips.children].forEach((pip, i) => {
        pip.classList.toggle('is-lost', i >= stats.integrity);
    });

    // Pulso suave de aviso cuando baja la integridad
    if (stats.integrity < START_INTEGRITY) {
        els.hudIntegrity.classList.remove('is-hit');
        void els.hudIntegrity.offsetWidth;
        els.hudIntegrity.classList.add('is-hit');
    }
}

function onProgress(stats) {
    const cfg = currentCfg();
    const remaining = Math.max(0, cfg.total - stats.resolved);
    els.progressLabel.textContent =
        remaining === 0 ? 'Últimas figuras…' : `Quedan ${remaining} de ${cfg.total}`;
    els.progressFill.style.width = `${(stats.resolved / cfg.total) * 100}%`;
}

function currentCfg() {
    return DIFFICULTIES[currentDifficulty];
}

/* ------------------------------------------------------------
 * Tarjetas doradas: insignia con cuenta atrás visual
 * ------------------------------------------------------------ */
function onPower({ type, duration }) {
    const badge = type === 'rapid' ? els.badgeRapid : els.badgeDouble;
    if (!badge) return;
    badge.classList.remove('is-visible');
    void badge.offsetWidth; // reinicia la animación de la barra
    badge.style.setProperty('--pow-duration', `${duration}s`);
    badge.classList.add('is-visible');
    clearTimeout(badge._hideTimer);
    badge._hideTimer = setTimeout(
        () => badge.classList.remove('is-visible'),
        duration * 1000 + 250
    );
}

/* ------------------------------------------------------------
 * Partida
 * ------------------------------------------------------------ */
function startGame(difficultyKey) {
    currentDifficulty = difficultyKey;
    const cfg = currentCfg();

    els.difficultyChip.textContent = `${cfg.name} · ${cfg.level}`;
    els.progressFill.style.width = '0%';
    els.progressLabel.textContent = `Quedan ${cfg.total} de ${cfg.total}`;
    els.pauseOverlay.classList.remove('is-visible');

    showScreen('game');
    // El motor mide la arena: necesita que la pantalla ya sea visible.
    requestAnimationFrame(() => game.start(difficultyKey));
}

function onEnd(result) {
    lastResult = result;

    // Guardar mejor puntuación de la dificultad
    const best = loadBest();
    if (best[result.difficulty.key] === undefined || result.score > best[result.difficulty.key]) {
        best[result.difficulty.key] = result.score;
        saveBest(best);
    }

    closeCurtain(() => {
        renderResults(result);
        showScreen('results');
        openCurtain();
    });
}

/* ------------------------------------------------------------
 * Telón de teatro
 * ------------------------------------------------------------ */
function curtainTimes() {
    return motionQuery.matches
        ? { close: 240, hold: 120, open: 240 }
        : { close: 1150, hold: 320, open: 1050 };
}

function closeCurtain(afterClose) {
    const t = curtainTimes();
    els.curtain.classList.add('is-active');
    // Forzar reflow para que la transición arranque desde abierto
    void els.curtain.offsetWidth;
    els.curtain.classList.add('is-closed');
    setTimeout(afterClose, t.close);
}

function openCurtain() {
    const t = curtainTimes();
    setTimeout(() => {
        els.curtain.classList.remove('is-closed');
        setTimeout(() => els.curtain.classList.remove('is-active'), t.open);
    }, t.hold);
}

/* ------------------------------------------------------------
 * Resultados
 * ------------------------------------------------------------ */
function renderResults(result) {
    els.resultsSubtitle.textContent = result.earlyEnd
        ? 'La integridad llegó a 0: la patrulla se detuvo antes de tiempo'
        : 'La patrulla ha terminado';

    els.statScore.textContent = String(result.score);
    els.statPrecision.textContent = `${result.precision}%`;
    els.statProtected.textContent = String(result.protectedCount);
    els.statThreats.textContent = String(result.threats);

    els.resultsMessage.textContent = closingMessage(result);
}

/* ------------------------------------------------------------
 * Sonido
 * ------------------------------------------------------------ */
function renderSound() {
    els.btnSound.textContent = soundEnabled() ? 'Sonido: sí' : 'Sonido: no';
    els.btnSound.setAttribute('aria-pressed', soundEnabled() ? 'true' : 'false');
}

/* ------------------------------------------------------------
 * Arranque
 * ------------------------------------------------------------ */
function init() {
    initSky($('sky-canvas'));
    renderPips();
    renderDifficulties();
    renderSound();

    game = createGame({
        arena: els.arena,
        onScore,
        onIntegrity,
        onProgress,
        onEnd,
        onPower,
    });

    els.btnHowto.addEventListener('click', () => showScreen('howto'));
    els.btnHowtoBack.addEventListener('click', () => showScreen('home'));

    els.btnExit.addEventListener('click', () => {
        // Salir nunca penaliza: la patrulla se abandona sin más.
        game.stop();
        renderDifficulties();
        showScreen('home');
    });

    els.btnPause.addEventListener('click', () => {
        if (game.isRunning()) {
            game.pause();
            els.pauseOverlay.classList.add('is-visible');
            els.btnResume.focus({ preventScroll: true });
        } else if (game.isPaused()) {
            els.pauseOverlay.classList.remove('is-visible');
            game.resume();
        }
    });

    // Si la ventana pierde el foco o la pestaña se oculta, la patrulla
    // se pausa sola y queda esperando al jugador.
    const autoPause = () => {
        if (game.isRunning()) {
            game.pause();
            els.pauseOverlay.classList.add('is-visible');
        }
    };
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) autoPause();
    });
    window.addEventListener('blur', autoPause);

    els.btnResume.addEventListener('click', () => {
        els.pauseOverlay.classList.remove('is-visible');
        game.resume();
    });

    els.btnQuitPause.addEventListener('click', () => {
        els.pauseOverlay.classList.remove('is-visible');
        game.stop();
        renderDifficulties();
        showScreen('home');
    });

    els.btnSound.addEventListener('click', () => {
        toggleSound();
        renderSound();
    });

    els.btnReplay.addEventListener('click', () => startGame(currentDifficulty));
    els.btnHome.addEventListener('click', () => {
        renderDifficulties();
        showScreen('home');
    });

    showScreen('home');
}

document.addEventListener('DOMContentLoaded', init);
