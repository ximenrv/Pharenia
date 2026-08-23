/**
 * Helper compartido para enviar los resultados de los juegos de adolescentes
 * al backend de Laravel.
 *
 * Las rutas deben coincidir con las definidas en routes/web.php:
 *   POST /games/youth/quizzsense/record
 *   POST /games/youth/paises/record
 *   POST /games/youth/centinela/record
 */

function csrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function todayKey(date = new Date()) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

async function postJson(url, payload) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            // Fallos silenciosos: el juego no debe bloquearse si el servidor no responde.
            console.warn('Error guardando resultado:', response.status, await response.text());
            return null;
        }

        return await response.json();
    } catch (err) {
        console.warn('No se pudo guardar el resultado:', err);
        return null;
    }
}

export function saveQuizzsenseResult({ correctAnswers, totalQuestions, categorySummary }) {
    return postJson('/games/youth/quizzsense/record', {
        session_date: todayKey(),
        correct_answers: correctAnswers,
        total_questions: totalQuestions,
        category_summary: categorySummary || null,
    });
}

export function savePaisesResult({ continent, correctAnswers, totalQuestions }) {
    return postJson('/games/youth/paises/record', {
        continent,
        session_date: todayKey(),
        correct_answers: correctAnswers,
        total_questions: totalQuestions,
    });
}

export function saveCentinelaResult({ difficulty, score, precision, protectedCount, threats, integrityRemaining }) {
    return postJson('/games/youth/centinela/record', {
        difficulty,
        session_date: todayKey(),
        score,
        precision,
        protected_count: protectedCount,
        threats,
        integrity_remaining: integrityRemaining,
    });
}
