/**
 * Catálogo de figuras de la patrulla.
 *
 * El color NUNCA describe la naturaleza de la figura, solo la acción
 * que debe tomar el jugador:
 * - protect (verde): elementos de la vida cotidiana que no se atacan.
 * - threat  (rojo):  amenazas que deben neutralizarse.
 *
 * Cada entrada referencia un <symbol> SVG definido en el Blade
 * (contorno sin relleno, stroke="currentColor").
 */
import i18n from './i18n.json';

const locale = typeof window !== 'undefined' && window.APP_LOCALE ? window.APP_LOCALE : 'es';

function t(key, vars = {}) {
    const parts = key.split('.');
    let value = i18n[locale];
    for (const part of parts) {
        value = value?.[part];
    }
    if (typeof value !== 'string') {
        value = i18n['es'];
        for (const part of parts) {
            value = value?.[part];
        }
    }
    if (typeof value !== 'string') return key;
    return value.replace(/\{(\w+)\}/g, (_, k) => (vars[k] !== undefined ? vars[k] : `{${k}}`));
}

export const PROTECT_FIGURES = [
    { id: 'fig-nino', label: t('figures.nino') },
    { id: 'fig-perro', label: t('figures.perro') },
    { id: 'fig-gato', label: t('figures.gato') },
    { id: 'fig-serpiente', label: t('figures.serpiente') },
    { id: 'fig-arbol', label: t('figures.arbol') },
    { id: 'fig-familia', label: t('figures.familia') },
    { id: 'fig-amigos', label: t('figures.amigos') },
];

export const THREAT_FIGURES = [
    { id: 'fig-encapuchado', label: t('threats.encapuchado') },
    { id: 'fig-monstruo', label: t('threats.monstruo') },
    { id: 'fig-mascara', label: t('threats.mascara') },
    { id: 'fig-espectro', label: t('threats.espectro') },
];

/** Dificultades de la patrulla. */
export const DIFFICULTIES = {
    facil: {
        key: 'facil',
        name: t('difficulty.facil.name'),
        level: t('difficulty.facil.level'),
        desc: t('difficulty.facil.desc'),
        total: 26,
        spawnEvery: 1.5,      // segundos entre apariciones
        speed: [30, 46],      // px/s de descenso
        maxOnScreen: 3,
        redRatio: 0.45,
        patterns: ['recto'],
        escorts: false,       // mezclas deliberadas roja + verdes
    },
    normal: {
        key: 'normal',
        name: t('difficulty.normal.name'),
        level: t('difficulty.normal.level'),
        desc: t('difficulty.normal.desc'),
        total: 40,
        spawnEvery: 1.1,
        speed: [46, 66],
        maxOnScreen: 5,
        redRatio: 0.5,
        patterns: ['recto', 'ondas'],
        escorts: false,
    },
    dificil: {
        key: 'dificil',
        name: t('difficulty.dificil.name'),
        level: t('difficulty.dificil.level'),
        desc: t('difficulty.dificil.desc'),
        total: 56,
        spawnEvery: 0.8,
        speed: [60, 88],
        maxOnScreen: 7,
        redRatio: 0.5,
        patterns: ['recto', 'ondas', 'zigzag'],
        escorts: true,
    },
};

export const START_INTEGRITY = 10;

/**
 * Tarjetas doradas coleccionables: caen de vez en cuando y se RECOGEN
 * con la nave (no hay que dispararles). Duran unos segundos.
 * - rapid:  disparo automático al doble de velocidad
 * - double: dos disparos en paralelo
 */
export const POWERUPS = {
    rapid: { id: 'fig-pow-rapida', label: t('powerups.rapid') },
    double: { id: 'fig-pow-doble', label: t('powerups.double') },
};
export const POWERUP_DURATION = 8;      // segundos de efecto
export const POWERUP_EVERY = [9, 14];   // rango de aparición (segundos)

/** Mensajes de cierre según cómo fue la patrulla. Tono cálido, nunca punitivo. */
export function closingMessage({ score, precision, protectedCount, integrity, earlyEnd }) {
    if (earlyEnd) {
        return t('closing.early');
    }
    if (precision >= 90 && score >= 15) {
        return t('closing.precise');
    }
    if (precision >= 75) {
        return t('closing.good');
    }
    if (protectedCount > 0 && score === 0) {
        return t('closing.nonviolent');
    }
    return t('closing.default');
}
