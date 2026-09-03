/**
 * Motor del quiz: selección diaria de preguntas, barajado sin patrones
 * y cálculo del resumen por categorías.
 *
 * Reglas:
 * - Cada día se sirve una sesión de SESSION_SIZE preguntas.
 * - No se repite una pregunta ya vista hasta agotar el banco completo;
 *   entonces el historial se reinicia (el banco "se renueva").
 * - El orden de las 4 opciones se baraja al azar en cada pregunta, así la
 *   respuesta correcta no cae siempre en la misma posición/letra y el
 *   usuario no puede memorizar patrones.
 */
import i18n from './i18n.json';
import questionsEs from './data/questions.json';
import questionsEn from './data/questions-en.json';
import { loadState, saveState, todayKey } from './storage.js';

export const SESSION_SIZE = 10;

const locale = window.APP_LOCALE || 'es';

function t(key) {
    const parts = key.split('.');
    let value = i18n[locale];
    for (const part of parts) {
        if (value && typeof value === 'object' && part in value) {
            value = value[part];
        } else {
            value = undefined;
            break;
        }
    }
    if (value === undefined) {
        value = i18n['es'];
        for (const part of parts) {
            if (value && typeof value === 'object' && part in value) {
                value = value[part];
            } else {
                value = key;
                break;
            }
        }
    }
    return value ?? key;
}

export const CATEGORY_LABELS = t('categories') || {
    sobrecarga_sensorial: 'Sobrecarga sensorial',
    lenguaje_figurado: 'Lenguaje figurado y sarcasmo',
    intereses_especiales: 'Intereses y señales sociales',
    instrucciones_ambiguas: 'Instrucciones poco claras',
    fatiga_social: 'Fatiga social y autocuidado',
    cambios_rutina: 'Cambios e imprevistos',
    conflictos_pares: 'Conflictos entre compañeros',
    autodefensa_comunicacion: 'Pedir ayuda y poner límites',
    senales_peligro: 'Señales de peligro',
    espacios_publicos: 'Espacios públicos',
};

const BANK = locale === 'en' ? questionsEn : questionsEs;

/** Barajado Fisher–Yates con crypto.getRandomValues (mejor azar que Math.random). */
export function shuffle(array) {
    const a = [...array];
    const rand = new Uint32Array(1);
    for (let i = a.length - 1; i > 0; i--) {
        crypto.getRandomValues(rand);
        const j = rand[0] % (i + 1);
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}

/** Devuelve la pregunta con sus opciones barajadas (sin patrón de posición). */
export function withShuffledOptions(question) {
    return { ...question, options: shuffle(question.options) };
}

/**
 * Obtiene (o crea) la sesión de hoy. Si el usuario ya completó el quiz de
 * hoy devuelve null. Si hay una sesión a medio hacer, la reanuda.
 */
export function getTodaySession() {
    const state = loadState();
    const today = todayKey();

    if (state.lastCompletedDate === today) return null;

    if (state.session && state.session.date === today) {
        return state.session;
    }

    // Nueva sesión: elegir preguntas no vistas.
    let unseen = BANK.filter((q) => !state.seenIds.includes(q.id));
    if (unseen.length < SESSION_SIZE) {
        // Banco agotado (o casi): reiniciamos el historial y usamos todo el banco.
        state.seenIds = [];
        unseen = BANK;
    }

    const picked = shuffle(unseen).slice(0, SESSION_SIZE);
    const session = {
        date: today,
        questionIds: picked.map((q) => q.id),
        currentIndex: 0,
        answers: [], // { id, category, correct: boolean }
    };
    state.session = session;
    // Set oficial del día: las repeticiones en modo práctica usan estas mismas.
    state.dailySet = { date: today, questionIds: session.questionIds };
    saveState(state);
    return session;
}

/** Registra la respuesta a la pregunta actual y avanza el índice. */
export function recordAnswer(session, question, wasCorrect) {
    session.answers.push({
        id: question.id,
        category: question.category,
        correct: wasCorrect,
    });
    session.currentIndex += 1;

    // Persistir la sesión actualizada (permite reanudar tras recargar).
    const state = loadState();
    if (state.session && state.session.date === session.date) {
        state.session = session;
        saveState(state);
    }
}

/**
 * Crea una sesión de PRÁCTICA con exactamente las mismas preguntas del quiz
 * diario ya completado (mismo set y mismo orden). No toca el estado guardado:
 * es solo para repasar, tantas veces como se quiera, dentro del mismo día.
 * Devuelve null si hoy no hay quiz completado que repetir.
 */
export function getPracticeSession() {
    const state = loadState();
    const today = todayKey();

    if (state.lastCompletedDate !== today) return null;

    // Set del día: preferimos dailySet; si falta (sesiones guardadas con una
    // versión antigua), recurrimos a los ids guardados en el último resultado.
    let ids = null;
    if (state.dailySet && state.dailySet.date === today) {
        ids = state.dailySet.questionIds;
    } else if (state.lastResult && state.lastResult.date === today) {
        ids = state.lastResult.questionIds;
    }
    if (!Array.isArray(ids) || ids.length === 0) return null;

    // Seguridad: descartar ids que ya no existan en el banco.
    const valid = ids.filter((id) => BANK.some((q) => q.id === id));
    if (valid.length === 0) return null;

    return {
        date: today,
        questionIds: valid,
        currentIndex: 0,
        answers: [],
        practice: true,
    };
}

/** Marca la sesión como completada, actualiza historial, racha y resultado. */
export function completeSession(session, messageIndex) {
    const state = loadState();
    const today = todayKey();

    const answeredIds = session.questionIds.slice(0, session.answers.length);
    state.seenIds = [...new Set([...state.seenIds, ...answeredIds])];

    // Racha: suma solo si el último día de racha fue ayer (o nunca hubo).
    const yesterday = todayKey(new Date(Date.now() - 86400000));
    state.streak = state.lastStreakDate === yesterday ? state.streak + 1 : 1;
    state.lastStreakDate = today;

    state.lastCompletedDate = today;

    const correctCount = session.answers.filter((a) => a.correct).length;
    state.lastResult = {
        date: today,
        total: session.answers.length,
        correct: correctCount,
        messageIndex,
        questionIds: [...session.questionIds],
        categories: summarizeCategories(session.answers),
    };

    state.session = null;
    saveState(state);
    return state.lastResult;
}

/**
 * Resumen por categorías: cuáles fueron bien y cuáles conviene repasar.
 * Devuelve [{ key, label, correct, total }] solo de categorías presentes.
 */
export function summarizeCategories(answers) {
    const map = new Map();
    for (const a of answers) {
        if (!map.has(a.category)) map.set(a.category, { correct: 0, total: 0 });
        const entry = map.get(a.category);
        entry.total += 1;
        if (a.correct) entry.correct += 1;
    }
    return [...map.entries()].map(([key, v]) => ({
        key,
        label: CATEGORY_LABELS[key] ?? key,
        ...v,
    }));
}

/** Pregunta actual de la sesión, con opciones barajadas. */
export function currentQuestion(session) {
    const id = session.questionIds[session.currentIndex];
    const q = BANK.find((item) => item.id === id);
    return q ? withShuffledOptions(q) : null;
}
