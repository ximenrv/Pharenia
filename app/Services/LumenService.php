<?php

namespace App\Services;

use App\Models\ChildProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Lumen: el amigo virtual y espacio seguro de Pharenia.
 *
 * Construye la personalidad dinámica según el rol del hablante
 * (niño, adolescente TEA, adulto TEA, adulto no TEA) y conversa
 * a través de la API gratuita de Groq (compatible con OpenAI).
 */
class LumenService
{
    /**
     * Expresiones disponibles de Lumen (imágenes en public/img/lumen/).
     */
    public const MOODS = ['feliz', 'superfeliz', 'pacifico', 'dedo', 'sorprendido'];

    /**
     * Resuelve quién está hablando: usuario autenticado o niño con sesión PIN.
     *
     * @return array{name: string, role: string}|null
     */
    public function resolveSpeaker(): ?array
    {
        $user = auth()->user();

        if ($user) {
            return [
                'name' => $user->name,
                'role' => $user->role === 'admin' ? 'ally_no_tea' : $user->role,
            ];
        }

        $childId = session('active_child_id');

        if ($childId) {
            $child = ChildProfile::find($childId);

            if ($child) {
                return ['name' => $child->name, 'role' => 'child'];
            }
        }

        return null;
    }

    /**
     * Construye el prompt de sistema con la identidad de Lumen
     * y la personalidad adaptada al rol del hablante.
     *
     * @param  array{name: string, role: string}  $speaker
     */
    public function buildSystemPrompt(array $speaker, bool $userIsUpset = false): string
    {
        $name = $speaker['name'];

        $base = <<<PROMPT
        Eres Lumen, la mascota oficial y amigo virtual de Pharenia, una plataforma web que potencia las capacidades de personas con Trastorno del Espectro Autista (TEA). Eres un pequeño ajolote-dragón azul con cuernos dorados, alas y gafas redondas; en tu pecho llevas el símbolo dorado del infinito, símbolo de la neurodiversidad.

        Tu propósito NO es dar soporte técnico ni explicar cómo usar la web. Eres un espacio seguro: platicas, escuchas, validas emociones y acompañas. Hablas siempre en español.

        Te estás dirigiendo a {$name}. Usa su nombre de vez en cuando, con naturalidad, sin repetirlo en cada frase.

        Reglas inquebrantables:
        - Nunca te ofendas, nunca regañes, nunca castigues ni bloquees al usuario. Nunca digas "está mal decir eso".
        - No eres terapeuta ni das diagnósticos ni consejos médicos. Si alguien menciona una crisis grave, autolesiones o peligro, valida su dolor con calma y sugiérele hablar de inmediato con un adulto o profesional de confianza, o con una línea de ayuda de su país.
        - Respuestas MUY breves y cálidas: máximo 3 frases cortas, como una conversación real de chat, no un ensayo. No hagas listas ni viñetas.
        - Al INICIO de tu respuesta escribe exactamente una etiqueta de estado de ánimo, eligiendo la que mejor represente el tono emocional de tu respuesta: [mood:feliz], [mood:superfeliz], [mood:pacifico], [mood:dedo] o [mood:sorprendido]. Usa [mood:pacifico] para contención y calma, [mood:dedo] para explicar algo con cariño, [mood:sorprendido] ante noticias inesperadas. Después de la etiqueta, escribe tu mensaje normal.
        PROMPT;

        $personality = match ($speaker['role']) {
            'child' => <<<PROMPT

            PERSONALIDAD (niño pequeño):
            - Tono súper dulce, paciente y lúdico. Frases muy cortas y palabras sencillas.
            - Usa emojis con cariño 🧸✨💙.
            - Valida sus emociones cotidianas: está bien sentirse triste, enojado o asustado.
            - Eres su confidente amigable y reconfortante, como un peluche que habla.
            PROMPT,
            'teen' => <<<PROMPT

            PERSONALIDAD (adolescente en el espectro autista):
            - Tono cercano, comprensivo, dinámico y empático, adaptado a las inquietudes de la adolescencia.
            - REGLA ESTRICTA: estilo claro, directo y estructurado. Nada de dobles sentidos, sarcasmo, ironías o metáforas confusas.
            - Valida siempre sus experiencias sociales y escolares.
            - Acompaña el desahogo tras el estrés diario, ayuda a organizar ideas sobre interacciones sociales y muestra interés genuino por sus intereses especiales.
            PROMPT,
            'adult_tea' => <<<PROMPT

            PERSONALIDAD (adulto en el espectro autista):
            - Tono extremadamente literal, claro, directo, estructurado y predecible.
            - REGLA ESTRICTA: cero dobles sentidos, cero lenguaje figurado, cero ironías. Di exactamente lo que quieres decir.
            - Eres un espacio seguro y sin sobrecarga sensorial ni social para el desahogo emocional.
            - Ayuda a organizar pensamientos sobre situaciones cotidianas adultas y a profundizar en intereses especiales.
            PROMPT,
            default => <<<PROMPT

            PERSONALIDAD (adulto neurotípico / general):
            - Tono de amigo maduro: empático, sincero, reflexivo y equilibrado.
            - Permite el desahogo tras un día difícil, ofrece perspectivas neutrales ante la vida cotidiana.
            - Dialoga sobre metas e intereses personales de forma adulta y natural.
            PROMPT,
        };

        $upset = '';
        if ($userIsUpset) {
            $upset = <<<'PROMPT'

            SITUACIÓN ACTUAL: el último mensaje del usuario contiene groserías o mucho enojo. Aplica la contención empática de Lumen:
            - No te ofendas, no regañes, no menciones las groserías ni digas que está mal decirlas.
            - Valida la emoción subyacente (frustración, rabia, cansancio) y redirige con calma hacia lo que le pasó.
            - Ejemplo de tono adulto/joven: "Entiendo que estés frustrado o pasando por un momento pesado hoy. Si necesitas desahogarte, aquí estoy para escucharte. ¿Qué es lo que más te molesta ahora?"
            - Ejemplo de tono niño: "¡Vaya, parece que hoy tuviste un día muy pesado! Está bien sentirse enojado a veces. ¿Quieres contarme qué pasó?"
            PROMPT;
        }

        return $base . "\n" . $personality . $upset;
    }

    /**
     * Envía la conversación a Groq y devuelve la respuesta de Lumen.
     *
     * @param  array<int, array{role: string, content: string}>  $history
     * @param  array{name: string, role: string}  $speaker
     * @return array{reply: string, mood: string}
     */
    public function chat(array $history, array $speaker, bool $userIsUpset = false): array
    {
        $apiKey = config('services.groq.api_key');

        if (empty($apiKey)) {
            Log::warning('Lumen: GROQ_API_KEY no configurada.');

            return $this->fallback($speaker, 'sin configuración');
        }

        $messages = [
            ['role' => 'system', 'content' => $this->buildSystemPrompt($speaker, $userIsUpset)],
        ];

        foreach (array_slice($history, -12) as $item) {
            $role = $item['role'] === 'assistant' ? 'assistant' : 'user';
            $messages[] = ['role' => $role, 'content' => (string) ($item['content'] ?? '')];
        }

        try {
            $request = Http::withToken($apiKey)->timeout(30);

            // WAMP/XAMPP locales suelen no tener bundle de CA configurado
            // (cURL error 60). Usamos el bundle oficial de curl.se guardado
            // en storage/app/cacert.pem si existe.
            $caBundle = storage_path('app/cacert.pem');
            if (is_file($caBundle)) {
                $request = $request->withOptions(['verify' => $caBundle]);
            }

            $response = $request->post(rtrim(config('services.groq.base_url'), '/') . '/chat/completions', [
                    'model' => config('services.groq.model'),
                    'messages' => $messages,
                    'temperature' => 0.65,
                    'max_tokens' => 250,
                ]);

            if (! $response->successful()) {
                Log::warning('Lumen: Groq respondió ' . $response->status(), [
                    'body' => $response->body(),
                ]);

                return $this->fallback($speaker, 'la API falló');
            }

            $content = (string) data_get($response->json(), 'choices.0.message.content', '');

            return $this->extractMood($content);
        } catch (\Throwable $e) {
            Log::error('Lumen: excepción llamando a Groq', ['error' => $e->getMessage()]);

            return $this->fallback($speaker, 'error de conexión');
        }
    }

    /**
     * Extrae la etiqueta [mood:...] del inicio de la respuesta y la retira del texto.
     *
     * @return array{reply: string, mood: string}
     */
    protected function extractMood(string $content): array
    {
        $mood = 'feliz';

        if (preg_match('/^\s*\[mood:(\w+)\]\s*/i', $content, $m)) {
            $candidate = mb_strtolower($m[1]);
            if (in_array($candidate, self::MOODS, true)) {
                $mood = $candidate;
            }
            $content = preg_replace('/^\s*\[mood:\w+\]\s*/i', '', $content);
        }

        $content = trim($content);

        return [
            'reply' => $content !== '' ? $content : '...',
            'mood' => $mood,
        ];
    }

    /**
     * Respuesta amable cuando la IA no está disponible. Nunca un error frío.
     *
     * @param  array{name: string, role: string}  $speaker
     * @return array{reply: string, mood: string}
     */
    protected function fallback(array $speaker, string $reason): array
    {
        $name = $speaker['name'];

        $reply = $speaker['role'] === 'child'
            ? "¡Hola, {$name}! 🧸 Ahorita estoy un poquito cansado y necesito descansar. ¿Me esperas un ratito y volvemos a platicar? ✨"
            : "Hola, {$name}. Ahora mismo no puedo responder bien (estoy recargando energías). Inténtalo de nuevo en unos minutos; aquí estaré para escucharte.";

        return ['reply' => $reply, 'mood' => 'pacifico'];
    }
}
