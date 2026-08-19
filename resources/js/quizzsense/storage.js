/**
 * Persistencia del estado del quiz en localStorage.
 *
 * Estructura guardada (clave "quizzsense:v1"):
 * {
 *   seenIds: string[]            // preguntas ya mostradas (para no repetir hasta agotar el banco)
 *   lastCompletedDate: string    // 'YYYY-MM-DD' del último quiz completado
 *   streak: number               // días consecutivos completados
 *   lastStreakDate: string|null  // último día que sumó a la racha
 *   session: object|null         // sesión del día en curso (permite reanudar tras recargar)
 *   lastResult: object|null      // resumen del último quiz completado (para mostrarlo en Home)
 * }
 */

const STORAGE_KEY = 'quizzsense:v1';

const DEFAULT_STATE = {
    seenIds: [],
    lastCompletedDate: null,
    streak: 0,
    lastStreakDate: null,
    session: null,
    lastResult: null,
    dailySet: null, // { date, questionIds } — set oficial del día para el modo práctica
};

export function loadState() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return { ...DEFAULT_STATE };
        return { ...DEFAULT_STATE, ...JSON.parse(raw) };
    } catch {
        // Si el JSON está corrupto, empezamos de cero sin romper la app.
        return { ...DEFAULT_STATE };
    }
}

export function saveState(state) {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    } catch {
        // localStorage lleno o deshabilitado: la app sigue funcionando en memoria.
    }
}

/** Fecha local de hoy en formato 'YYYY-MM-DD'. */
export function todayKey(date = new Date()) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}
