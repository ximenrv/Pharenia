/**
 * Motor del juego "QuizzSense · Países del mundo".
 *
 * Reglas:
 * - Elige un continente y localiza en el mapa los países propuestos.
 * - 10 países por ronda (o todos los disponibles si hay menos).
 * - Sin temporizador ni penalización: feedback cálido y se aprende del error.
 */
import { initSky } from '../quizzsense/sky.js';
import { savePaisesResult } from '../utils/teen-records.js';
import i18n from './i18n.json';
import americaEs from './data/america.json';
import americaEn from './data/america-en.json';
import europaEs from './data/europa.json';
import europaEn from './data/europa-en.json';
import asiaEs from './data/asia.json';
import asiaEn from './data/asia-en.json';
import africaEs from './data/africa.json';
import africaEn from './data/africa-en.json';
import oceaniaEs from './data/oceania.json';
import oceaniaEn from './data/oceania-en.json';

const $ = (id) => document.getElementById(id);

const locale = window.APP_LOCALE || 'es';
const t = (key, vars = {}) => {
    let text = i18n[locale]?.[key];
    if (text === undefined) text = i18n.es?.[key];
    if (text === undefined) return key;
    if (Array.isArray(text)) return text;
    return text.replace(/\{(\w+)\}/g, (_, k) => (vars[k] !== undefined ? vars[k] : `{${k}}`));
};

const continentData = {
    america: locale === 'en' ? americaEn : americaEs,
    europa:  locale === 'en' ? europaEn  : europaEs,
    asia:    locale === 'en' ? asiaEn    : asiaEs,
    africa:  locale === 'en' ? africaEn  : africaEs,
    oceania: locale === 'en' ? oceaniaEn : oceaniaEs,
};

const CONTINENTS = {
    america: { data: continentData.america, label: t('continents.america'), color: '#4caf7d' },
    europa:  { data: continentData.europa,  label: t('continents.europa'),  color: '#8577c9' },
    asia:    { data: continentData.asia,    label: t('continents.asia'),    color: '#e0a83c' },
    africa:  { data: continentData.africa,  label: t('continents.africa'),  color: '#d89c4a' },
    oceania: { data: continentData.oceania, label: t('continents.oceania'), color: '#6d5ba8' },
};

const ROUND_SIZE = 10;

let currentContinent = null;
let currentContinentKey = null;
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
    currentContinentKey = key;
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

    $('progress-label').textContent = t('game.progress', { current: currentIndex + 1, total });
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
    const country = roundCountries[currentIndex].name;
    $('feedback-title').textContent = isCorrect
        ? t('feedback.correct_title')
        : t('feedback.incorrect_title');
    $('feedback-text').textContent = isCorrect
        ? t('feedback.correct_text', { country })
        : t('feedback.incorrect_text', { country });
    $('btn-next').textContent = currentIndex + 1 >= roundCountries.length ? t('feedback.see_results') : t('feedback.next');
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

    const messages = t('results.messages');
    const messageIndex = Math.floor(Math.random() * messages.length);
    $('results-message').textContent = messages[messageIndex];

    // Persistir el resultado de esta sesión por continente.
    if (currentContinentKey) {
        savePaisesResult({
            continent: currentContinentKey,
            correctAnswers: correctCount,
            totalQuestions: total,
        });
    }

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
