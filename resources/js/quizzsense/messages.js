/**
 * Mensajes motivacionales para la pantalla de resultados.
 * Tono cálido y serio, centrado en el esfuerzo y el aprendizaje,
 * nunca comparativo.
 */
import i18n from './i18n.json';

const locale = window.APP_LOCALE || 'es';
const MESSAGES = i18n[locale]?.messages ?? i18n['es'].messages;

/** Elige un mensaje aleatorio distinto (si es posible) del último mostrado. */
export function pickMessage(excludeIndex = -1) {
    let index = Math.floor(Math.random() * MESSAGES.length);
    if (MESSAGES.length > 1 && index === excludeIndex) {
        index = (index + 1) % MESSAGES.length;
    }
    return { index, text: MESSAGES[index] };
}
