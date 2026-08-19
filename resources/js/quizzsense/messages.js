/**
 * Mensajes motivacionales para la pantalla de resultados.
 * Tono cálido y serio, centrado en el esfuerzo y el aprendizaje,
 * nunca comparativo.
 */
export const MOTIVATIONAL_MESSAGES = [
    'Cada pregunta respondida es un paso más. El progreso real se construye día a día.',
    'Lo importante no es acertar siempre, sino detenerte a pensar cada situación. Hoy lo has hecho.',
    'Has dedicado tiempo a entender mejor las situaciones sociales. Ese avance se acumula.',
    'Aprender a manejar lo cotidiano lleva práctica, y hoy has practicado. Buen trabajo.',
    'Incluso las preguntas difíciles enseñan algo. Hoy entiendes un poco más que ayer.',
    'No hay respuestas perfectas: hay personas que aprenden. Y tú hoy has aprendido.',
    'Has completado tu sesión de hoy. Mañana habrá nuevas situaciones y tú tendrás más recursos.',
    'Cada situación que analizas con calma se convierte en una herramienta para la próxima vez.',
    'El esfuerzo de hoy es confianza para mañana. Sigue a tu ritmo.',
    'Pensar antes de reaccionar es una habilidad, y hoy la has entrenado.',
    'No se trata de no equivocarse nunca, sino de entender un poco más cada día.',
    'Enfrentarte a situaciones difíciles, incluso en un ejercicio, requiere valentía. La has demostrado.',
    'La constancia vale más que la perfección. Volver cada día ya es un logro.',
    'Cada día que practicas, tu repertorio de respuestas crece. Nos vemos en la próxima sesión.',
    'Hoy has dedicado unos minutos a conocerte mejor. Eso siempre merece la pena.',
];

/** Elige un mensaje aleatorio distinto (si es posible) del último mostrado. */
export function pickMessage(excludeIndex = -1) {
    let index = Math.floor(Math.random() * MOTIVATIONAL_MESSAGES.length);
    if (MOTIVATIONAL_MESSAGES.length > 1 && index === excludeIndex) {
        index = (index + 1) % MOTIVATIONAL_MESSAGES.length;
    }
    return { index, text: MOTIVATIONAL_MESSAGES[index] };
}
