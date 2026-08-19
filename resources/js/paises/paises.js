/**
 * Motor del juego "QuizzSense · Países del mundo".
 *
 * Reglas:
 * - Elige un continente y localiza en el mapa los países propuestos.
 * - 10 países por ronda (o todos los disponibles si hay menos).
 * - Sin temporizador ni penalización: feedback cálido y se aprende del error.
 */
import { initSky } from '../quizzsense/sky.js';
import america from './data/america.json';
import europa from './data/europa.json';
import asia from './data/asia.json';
import africa from './data/africa.json';
import oceania from './data/oceania.json';

const $ = (id) => document.getElementById(id);

const CONTINENTS = {
    america: { data: america, label: 'América', color: '#4caf7d' },
    europa:  { data: europa,  label: 'Europa',  color: '#8577c9' },
    asia:    { data: asia,    label: 'Asia',    color: '#e0a83c' },
    africa:  { data: africa,  label: 'África',  color: '#d89c4a' },
    oceania: { data: oceania, label: 'Oceanía', color: '#6d5ba8' },
};

const ROUND_SIZE = 10;

let currentContinent = null;
let roundCountries = [];
let currentIndex = 0;
let correctCount = 0;
let answered = false;

/* ------------------------------------------------------------
 * Utilidades
 * ------------------------------------------------------------ */
function shuffle(array) {
    const a = [...array];
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}

function showScreen(name) {
    document.querySelectorAll('.screen').forEach((s) => {
        s.classList.toggle('is-active', s.dataset.screen === name);
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function slowScrollTo(element) {
    const targetY = window.scrollY + element.getBoundingClientRect().top - 24;
    const startY = window.scrollY;
    const distance = targetY - startY;
    if (Math.abs(distance) < 4) return;

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) {
        window.scrollTo(0, targetY);
        return;
    }

    const duration = Math.min(550, Math.max(280, Math.abs(distance) * 0.9));
    const start = performance.now();

    function step(now) {
        const p = Math.min(1, (now - start) / duration);
        const eased = 1 - Math.pow(1 - p, 3);
        window.scrollTo(0, startY + distance * eased);
        if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

/* ------------------------------------------------------------
 * Pantalla de inicio
 * ------------------------------------------------------------ */
function initHome() {
    $('btn-start').addEventListener('click', () => showScreen('continents'));
    $('btn-howto').addEventListener('click', () => showScreen('howto'));
    showScreen('home');
}

/* ------------------------------------------------------------
 * Pantalla "Cómo funciona"
 * ------------------------------------------------------------ */
function initHowto() {
    $('btn-howto-back').addEventListener('click', () => showScreen('home'));
}

/* ------------------------------------------------------------
 * Selección de continente
 * ------------------------------------------------------------ */
function renderContinents() {
    const grid = $('continent-grid');
    grid.innerHTML = '';

    Object.entries(CONTINENTS).forEach(([key, { label, color }]) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'option-btn';
        btn.style.setProperty('--btn-edge', color);
        btn.style.background = `linear-gradient(180deg, color-mix(in srgb, ${color} 35%, #f8dd94) 0%, ${color} 100%)`;
        btn.style.color = '#231703';
        btn.innerHTML = `<span class="option-letter" aria-hidden="true">${label.charAt(0)}</span><span>${label}</span>`;
        btn.addEventListener('click', () => startGame(key));
        grid.appendChild(btn);
    });

    $('btn-continents-back').addEventListener('click', () => showScreen('home'));
}

/* ------------------------------------------------------------
 * Juego
 * ------------------------------------------------------------ */
function startGame(key) {
    currentContinent = CONTINENTS[key];
    const entries = Object.entries(currentContinent.data.paths).map(([code, info]) => ({
        code,
        name: info.nombre,
    }));
    roundCountries = shuffle(entries).slice(0, Math.min(ROUND_SIZE, entries.length));
    currentIndex = 0;
    correctCount = 0;
    answered = false;
    showScreen('game');
    renderQuestion();
}

function renderQuestion() {
    answered = false;
    const country = roundCountries[currentIndex];
    const total = roundCountries.length;

    $('progress-label').textContent = `País ${currentIndex + 1} de ${total}`;
    $('progress-bar').setAttribute('aria-valuemax', String(total));
    $('progress-bar').setAttribute('aria-valuenow', String(currentIndex + 1));
    $('progress-fill').style.width = `${(currentIndex / total) * 100}%`;
    $('continent-chip').textContent = currentContinent.label;
    $('country-name').textContent = country.name;
    $('feedback-wrap').classList.add('hidden');

    renderMap();
    slowScrollTo($('screen-game'));
}

function renderMap() {
    const svg = $('map-svg');
    const { data, color } = currentContinent;
    const viewBox = data.viewBox || `0 0 ${data.width} ${data.height}`;

    svg.setAttribute('viewBox', viewBox);
    svg.innerHTML = '';

    Object.entries(data.paths).forEach(([code, info]) => {
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', info.path);
        path.setAttribute('class', 'map-country');
        path.setAttribute('data-code', code);
        path.setAttribute('role', 'button');
        path.setAttribute('tabindex', '0');
        path.setAttribute('aria-label', info.nombre);
        path.addEventListener('click', () => onCountryClick(code));
        path.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                onCountryClick(code);
            }
        });
        svg.appendChild(path);
    });

    // Ajustar color de acierto al continente
    document.documentElement.style.setProperty('--continent-correct', color);
}

function onCountryClick(code) {
    if (answered) return;
    answered = true;

    const correctCode = roundCountries[currentIndex].code;
    const isCorrect = code === correctCode;
    if (isCorrect) correctCount += 1;

    const paths = document.querySelectorAll('.map-country');
    paths.forEach((p) => {
        p.classList.add('is-disabled');
        if (p.dataset.code === correctCode) {
            p.classList.remove('is-disabled');
            p.classList.add('is-correct');
        } else if (p.dataset.code === code && !isCorrect) {
            p.classList.remove('is-disabled');
            p.classList.add('is-wrong');
        }
    });

    const feedbackPanel = $('feedback-panel');
    feedbackPanel.classList.toggle('is-good', isCorrect);
    feedbackPanel.classList.toggle('is-kind', !isCorrect);
    $('feedback-title').textContent = isCorrect
        ? '¡Muy bien!'
        : 'Casi… este es un buen momento para aprender.';
    $('feedback-text').textContent = isCorrect
        ? `Has encontrado ${roundCountries[currentIndex].name}.`
        : `Este país es ${roundCountries[currentIndex].name}. Sigue intentándolo, cada acierto cuenta.`;
    $('btn-next').textContent = currentIndex + 1 >= roundCountries.length ? 'Ver resultados' : 'Siguiente →';
    $('feedback-wrap').classList.remove('hidden');
    slowScrollTo($('feedback-wrap'));
    $('btn-next').focus({ preventScroll: true });
}

function onNext() {
    if (!answered) return;
    currentIndex += 1;
    if (currentIndex >= roundCountries.length) {
        finishGame();
    } else {
        renderQuestion();
    }
}

function finishGame() {
    const total = roundCountries.length;
    $('results-score').textContent = String(correctCount);
    $('results-total').textContent = String(total);

    const messages = [
        'Cada país que localizas amplía tu mapa mental del mundo.',
        'Explorar mapas es una forma tranquila de aprender geografía.',
        'No importa el resultado: lo importante es seguir curioseando.',
        'Hoy has practicado tu orientación en el mapa.',
        'Cada intento cuenta, ¡sigue explorando continentes!',
    ];
    const messageIndex = Math.floor(Math.random() * messages.length);
    $('results-message').textContent = messages[messageIndex];

    showScreen('results');
}

/* ------------------------------------------------------------
 * Arranque
 * ------------------------------------------------------------ */
function init() {
    initSky($('sky-canvas'));
    initHome();
    initHowto();
    renderContinents();

    $('btn-exit').addEventListener('click', () => showScreen('home'));
    $('btn-next').addEventListener('click', onNext);
    $('btn-home').addEventListener('click', () => showScreen('home'));
    $('btn-other-continent').addEventListener('click', () => showScreen('continents'));
}

document.addEventListener('DOMContentLoaded', init);
