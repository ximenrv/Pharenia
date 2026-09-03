<?php

namespace App\Http\Controllers;

use App\Services\LumenService;
use App\Support\ProfanityFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LumenController extends Controller
{
    public function __construct(protected LumenService $lumen)
    {
    }

    /**
     * POST /lumen/chat
     *
     * Muro de acceso: solo usuarios autenticados o niños con sesión PIN.
     * Aplica censura suave (Nivel 2) y delega la respuesta empática (Nivel 1)
     * al modelo de IA mediante el prompt de LumenService.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array', 'max:12'],
            'history.*.role' => ['required_with:history', 'in:user,assistant'],
            'history.*.content' => ['required_with:history', 'string', 'max:1000'],
        ]);

        $speaker = $this->lumen->resolveSpeaker();

        if (! $speaker) {
            return response()->json([
                'requires_auth' => true,
                'reply' => __('¡Hola! Me encantaría platicar contigo, pero necesito que inicies sesión o te registres para que seamos amigos oficiales. ¿Creamos tu cuenta?'),
                'mood' => 'feliz',
            ], 401);
        }

        // Nivel 2: censura suave del mensaje antes de procesarlo.
        $censored = ProfanityFilter::censor($validated['message']);
        $isUpset = ProfanityFilter::containsProfanity($validated['message']);

        $history = $validated['history'] ?? [];
        $history[] = ['role' => 'user', 'content' => $censored];

        $result = $this->lumen->chat($history, $speaker, $isUpset);

        return response()->json([
            'reply' => $result['reply'],
            'mood' => $result['mood'],
            'censored_message' => $censored,
        ]);
    }
}
