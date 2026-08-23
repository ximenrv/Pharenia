/**
 * Controlador de UI del quiz diario de QuizzSense.
 * Conecta el motor (engine.js) con las pantallas del Blade.
 *
 * Principios de interacción:
 * - Sin temporizadores: el usuario controla el ritmo (botón "Siguiente").
 * - Feedback cálido y nunca punitivo.
 * - Movimiento suave y predecible; se respeta prefers-reduced-motion (CSS).
 */
import {
    SESSION_SIZE,
    getTodaySession,
    getPracticeSession,
    currentQuestion,
    recordAnswer,
    completeSession,
    summarizeCategories,
    CATEGORY_LABELS,
} from './engine.js';
import { loadState, todayKey } from './storage.js';
import { pickMessage } from './messages.js';
import { initSky } from './sky.js';
import { saveQuizzsenseResult } from '../utils/teen-records.js';

const $ = (id) => document.getElementById(id);

const els = {
    streakBadge: $('streak-badge'),
    // home
    homeAvailable: $('home-available'),
    homeCompleted: $('home-completed'),
    homeResultSummary: $('home-result-summary'),
    btnStart: $('btn-start'),
    btnHowto: $('btn-howto'),
    // howto
    btnHowtoBack: $('btn-howto-back'),
    btnRepeatHome: $('btn-repeat-home'),
    homeRepeatWrap: $('home-repeat-wrap'),
    // quiz
    btnExit: $('btn-exit'),
    progressLabel: $('progress-label'),
    progressBar: $('progress-bar'),
    progressFill: $('progress-fill'),
    progressStars: $('progress-stars'),
    practiceBanner: $('practice-banner'),
    questionCategory: $('question-category'),
    questionText: $('question-text'),
    optionsGrid: $('options-grid'),
    feedbackWrap: $('feedback-wrap'),
    feedbackPanel: $('feedback-panel'),
    feedbackTitle: $('feedback-title'),
    feedbackText: $('feedback-text'),
    btnNext: $('btn-next'),
    // results
    resultsTitle: $('results-title'),
    resultsScore: $('results-score'),
    resultsTotal: $('results-total'),
    resultsMessage: $('results-message'),
    resultsStrong: $('results-strong'),
    resultsPractice: $('results-practice'),
    resultsPracticeNote: $('results-practice-note'),
    btnHome: $('btn-home'),
    btnReplay: $('btn-replay'),
};

const LETTERS = ['A', 'B', 'C', 'D'];

let session = null;
let question = null;
let answered = false;
let practiceMode = false;
let lastMessageIndex = -1;

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
 * Home
 * ------------------------------------------------------------ */
function renderHome() {
    practiceMode = false;
    els.practiceBanner.classList.add('hidden');
    const state = loadState();

    if (state.streak > 0) {
        els.streakBadge.textContent =
            state.streak === 1
                ? '1 día seguido practicando'
                : `${state.streak} días seguidos practicando`;
        els.streakBadge.classList.remove('hidden');
    } else {
        els.streakBadge.classList.add('hidden');
    }

    const completedToday = state.lastCompletedDate === todayKey();
    els.homeAvailable.classList.toggle('hidden', completedToday);
    els.homeCompleted.classList.toggle('hidden', !completedToday);

    if (completedToday && state.lastResult) {
        els.homeResultSummary.textContent =
            `Acertaste ${state.lastResult.correct} de ${state.lastResult.total} situaciones.`;
    }

    // El botón de repetir solo aparece si hay un set del día disponible.
    if (completedToday) {
        els.homeRepeatWrap.classList.toggle('hidden', getPracticeSession() === null);
    }

    showScreen('home');
}

/* ------------------------------------------------------------
 * Quiz
 * ------------------------------------------------------------ */
function startQuiz() {
    session = getTodaySession();
    if (!session) {
        // Ya estaba completado hoy (doble clic, recarga, etc.)
        renderHome();
        return;
    }
    practiceMode = false;
    els.practiceBanner.classList.add('hidden');
    showScreen('quiz');
    renderQuestion();
}

/**
 * Modo práctica: repite EXACTAMENTE las mismas preguntas del quiz diario
 * ya completado. No guarda nada: ni resultado, ni racha, ni historial.
 * Se puede repetir sin límite dentro del mismo día.
 */
function startPractice() {
    const practice = getPracticeSession();
    if (!practice) {
        renderHome();
        return;
    }
    session = practice;
    practiceMode = true;
    els.practiceBanner.classList.remove('hidden');
    showScreen('quiz');
    renderQuestion();
}

function renderQuestion() {
    question = currentQuestion(session);
    if (!question) {
        finishQuiz();
        return;
    }

    answered = false;
    const index = session.currentIndex;

    // Progreso
    els.progressLabel.textContent = practiceMode
        ? `Práctica · Pregunta ${index + 1} de ${SESSION_SIZE}`
        : `Pregunta ${index + 1} de ${SESSION_SIZE}`;
    els.progressBar.setAttribute('aria-valuenow', String(index + 1));
    els.progressFill.style.width = `${(index / SESSION_SIZE) * 100}%`;
    renderStars(index);

    // Pregunta
    els.questionCategory.textContent = CATEGORY_LABELS[question.category] ?? question.category;
    els.questionText.textContent = question.situation;

    // Opciones (ya barajadas por el motor: sin patrón de posición)
    els.optionsGrid.innerHTML = '';
    question.options.forEach((option, i) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'option-btn';
        btn.dataset.correct = option.correct ? 'true' : 'false';

        const letter = document.createElement('span');
        letter.className = 'option-letter';
        letter.setAttribute('aria-hidden', 'true');
        letter.textContent = LETTERS[i];

        const text = document.createElement('span');
        text.textContent = option.text;

        btn.append(letter, text);
        btn.style.animationDelay = `${i * 80}ms`; // entrada escalonada suave
        btn.addEventListener('click', () => onAnswer(btn, option));
        els.optionsGrid.appendChild(btn);
    });

    els.feedbackWrap.classList.add('hidden');
    // Volver suavemente arriba: tras el feedback el usuario queda abajo.
    slowScrollTo(document.getElementById('screen-quiz'));
    els.optionsGrid.querySelector('button')?.focus({ preventScroll: true });
}

function renderStars(currentIndex) {
    els.progressStars.innerHTML = '';
    const correctIds = new Set(session.answers.filter((a) => a.correct).map((a) => a.id));

    for (let i = 0; i < session.questionIds.length; i++) {
        const star = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        star.setAttribute('class', 'progress-star h-4 w-4');
        star.setAttribute('viewBox', '0 0 24 24');
        const use = document.createElementNS('http://www.w3.org/2000/svg', 'use');
        use.setAttribute('href', '#star');
        star.appendChild(use);

        const qid = session.questionIds[i];
        if (correctIds.has(qid)) star.classList.add('is-done');
        if (i === currentIndex) star.classList.add('is-current');
        els.progressStars.appendChild(star);
    }
}

/* ------------------------------------------------------------
 * Scroll lento y suave (el scrollTo nativo 'smooth' es demasiado
 * rápido y el focus() normal produce un salto brusco de pantalla)
 * ------------------------------------------------------------ */
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
        // easeOutCubic: arranque ágil y frenada suave, sin sensación de tirón
        const eased = 1 - Math.pow(1 - p, 3);
        window.scrollTo(0, startY + distance * eased);
        if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

function onAnswer(btn, option) {
    if (answered) return;
    answered = true;

    const wasCorrect = option.correct === true;
    if (practiceMode) {
        // Solo en memoria: la práctica nunca escribe en el estado guardado.
        session.answers.push({ id: question.id, category: question.category, correct: wasCorrect });
        session.currentIndex += 1;
    } else {
        recordAnswer(session, question, wasCorrect);
    }

    // Pintar estados: correcto siempre visible, elegido marcado, resto atenuado
    els.optionsGrid.querySelectorAll('.option-btn').forEach((b) => {
        b.disabled = true;
        if (b.dataset.correct === 'true') b.classList.add('is-correct');
        else if (b === btn) b.classList.add('is-wrong');
        else b.classList.add('is-muted');
    });

    // Feedback cálido
    els.feedbackPanel.classList.toggle('is-good', wasCorrect);
    els.feedbackPanel.classList.toggle('is-kind', !wasCorrect);
    els.feedbackTitle.textContent = wasCorrect
        ? 'Muy bien pensado.'
        : 'Esa es una opción posible, pero hay una mejor manera. Veamos por qué.';
    els.feedbackText.textContent = question.explanation;
    els.btnNext.textContent =
        session.currentIndex >= SESSION_SIZE ? 'Ver mis resultados' : 'Siguiente →';

    els.feedbackWrap.classList.remove('hidden');
    els.progressFill.style.width = `${(session.currentIndex / SESSION_SIZE) * 100}%`;
    renderStars(session.currentIndex);

    // El foco va al botón sin mover la pantalla de golpe; el desplazamiento
    // hacia el feedback se hace con una animación lenta.
    els.btnNext.focus({ preventScroll: true });
    slowScrollTo(els.feedbackWrap);
}

function onNext() {
    if (!answered) return;
    if (session.currentIndex >= SESSION_SIZE) {
        finishQuiz();
    } else {
        renderQuestion();
    }
}

/* ------------------------------------------------------------
 * Resultados
 * ------------------------------------------------------------ */
function finishQuiz() {
    if (practiceMode) {
        renderResults({
            practice: true,
            correct: session.answers.filter((a) => a.correct).length,
            total: session.answers.length,
        });
        session = null;
        showScreen('results');
        return;
    }

    const { index, text } = pickMessage(lastMessageIndex);
    lastMessageIndex = index;

    const result = completeSession(session, index);

    // Persistir el resultado oficial del día en la base de datos.
    saveQuizzsenseResult({
        correctAnswers: result.correct,
        totalQuestions: result.total,
        categorySummary: result.categories || null,
    });

    renderResults({
        practice: false,
        correct: result.correct,
        total: result.total,
        message: text,
    });

    session = null;
    showScreen('results');
}

/** Pinta la pantalla de resultados, tanto del quiz oficial como de la práctica. */
function renderResults({ practice, correct, total, message }) {
    els.resultsTitle.textContent = practice ? 'Fin de la práctica' : 'Resultados de hoy';
    els.resultsScore.textContent = String(correct);
    els.resultsTotal.textContent = String(total);

    if (practice) {
        els.resultsMessage.textContent =
            'Repasar las mismas situaciones refuerza lo aprendido. Esta ronda fue solo práctica.';
    } else {
        els.resultsMessage.textContent = message;
    }

    // Aviso claro de que el modo práctica no deja registro
    els.resultsPracticeNote.classList.toggle('hidden', !practice);
    els.btnReplay.textContent = practice
        ? 'Repetir otra vez (sigue sin guardarse)'
        : 'Jugar de nuevo (modo práctica)';

    const categories = summarizeCategories(session.answers);
    const strong = categories.filter((c) => c.correct === c.total);
    const practiceList = categories.filter((c) => c.correct < c.total);

    els.resultsStrong.innerHTML = '';
    els.resultsPractice.innerHTML = '';

    if (strong.length > 0) {
        els.resultsStrong.appendChild(
            buildCategoryBlock('Te fue muy bien en…', strong)
        );
    }
    if (practiceList.length > 0) {
        els.resultsPractice.appendChild(
            buildCategoryBlock('Conviene seguir practicando', practiceList)
        );
    }
}

function buildCategoryBlock(title, categories) {
    const wrap = document.createElement('div');
    const h = document.createElement('p');
    h.className = 'mb-3 font-semibold text-gold-300';
    h.textContent = title;
    wrap.appendChild(h);

    const list = document.createElement('div');
    list.className = 'flex flex-wrap gap-2';
    categories.forEach((c) => {
        const chip = document.createElement('span');
        chip.className = 'category-chip !normal-case !tracking-normal !text-sm';
        chip.textContent = `${c.label} (${c.correct}/${c.total})`;
        list.appendChild(chip);
    });
    wrap.appendChild(list);
    return wrap;
}

/* ------------------------------------------------------------
 * Arranque
 * ------------------------------------------------------------ */
function init() {
    initSky($('sky-canvas'));

    els.btnStart.addEventListener('click', startQuiz);
    els.btnHowto.addEventListener('click', () => showScreen('howto'));
    els.btnHowtoBack.addEventListener('click', renderHome);
    els.btnExit.addEventListener('click', () => {
        // Salir nunca penaliza: la sesión queda guardada y se reanuda al volver.
        session = null;
        renderHome();
    });
    els.btnNext.addEventListener('click', onNext);
    els.btnHome.addEventListener('click', renderHome);
    els.btnReplay.addEventListener('click', startPractice);
    els.btnRepeatHome.addEventListener('click', startPractice);

    renderHome();
}

document.addEventListener('DOMContentLoaded', init);
