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
export const PROTECT_FIGURES = [
    { id: 'fig-nino', label: 'un niño' },
    { id: 'fig-perro', label: 'un perro' },
    { id: 'fig-gato', label: 'un gato' },
    { id: 'fig-serpiente', label: 'una serpiente' },
    { id: 'fig-arbol', label: 'un árbol' },
    { id: 'fig-familia', label: 'una familia' },
    { id: 'fig-amigos', label: 'dos amigos' },
];

export const THREAT_FIGURES = [
    { id: 'fig-encapuchado', label: 'una figura encapuchada' },
    { id: 'fig-monstruo', label: 'un monstruo' },
    { id: 'fig-mascara', label: 'una máscara amenazante' },
    { id: 'fig-espectro', label: 'un espectro' },
];

/** Dificultades de la patrulla. */
export const DIFFICULTIES = {
    facil: {
        key: 'facil',
        name: 'Primer paso',
        level: 'Fácil',
        desc: 'Pocas figuras, movimiento lento y mucho espacio entre ellas.',
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
        name: 'Mantente alerta',
        level: 'Normal',
        desc: 'Más figuras verdes y rojas, más velocidad y trayectorias variadas.',
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
        name: 'No pierdas el control',
        level: 'Difícil',
        desc: 'Figuras rápidas, cambios de dirección y mezclas constantes.',
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
    rapid: { id: 'fig-pow-rapida', label: 'Disparo veloz' },
    double: { id: 'fig-pow-doble', label: 'Doble disparo' },
};
export const POWERUP_DURATION = 8;      // segundos de efecto
export const POWERUP_EVERY = [9, 14];   // rango de aparición (segundos)

/** Mensajes de cierre según cómo fue la patrulla. Tono cálido, nunca punitivo. */
export function closingMessage({ score, precision, protectedCount, integrity, earlyEnd }) {
    let text;
    if (earlyEnd) {
        text = 'La patrulla se detiene aquí. Distinguir bajo presión es difícil, y cada intento afina la mirada.';
    } else if (precision >= 90 && score >= 15) {
        text = 'Mirada fina y pulso sereno: distinguiste con calma casi siempre.';
    } else if (precision >= 75) {
        text = 'Buen ojo. Cada patrulla entrena la pausa entre ver y actuar.';
    } else if (protectedCount > 0 && score === 0) {
        text = 'Protegiste sin disparar: a veces la mejor acción es no actuar. Cuando quieras, practica también la puntería.';
    } else {
        text = 'No todo lo que parece diferente es una amenaza. Aprender a distinguir lleva práctica, y hoy has practicado.';
    }
    return text;
}
