<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizzsenseResult;
use App\Models\PaisesResult;
use App\Models\CentinelaResult;
use Illuminate\Support\Facades\Auth;

class TeenGameRecordController extends Controller
{
    /**
     * Guarda el resultado oficial de una sesión diaria de QuizzSense.
     * Solo se mantiene el primer resultado de cada día.
     */
    public function saveQuizzsenseResult(Request $request)
    {
        $request->validate([
            'session_date' => 'required|date_format:Y-m-d',
            'correct_answers' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
            'category_summary' => 'nullable|array',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $result = QuizzsenseResult::firstOrCreate(
            [
                'email' => $user->email,
                'session_date' => $request->session_date,
            ],
            [
                'correct_answers' => $request->correct_answers,
                'total_questions' => $request->total_questions,
                'category_summary' => $request->category_summary,
            ]
        );

        return response()->json([
            'success' => true,
            'created' => $result->wasRecentlyCreated,
            'data' => $result,
        ]);
    }

    /**
     * Guarda el resultado de una sesión de Países por continente.
     */
    public function savePaisesResult(Request $request)
    {
        $request->validate([
            'continent' => 'required|string|in:america,europa,asia,africa,oceania',
            'session_date' => 'required|date_format:Y-m-d',
            'correct_answers' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $result = PaisesResult::create([
            'email' => $user->email,
            'continent' => $request->continent,
            'session_date' => $request->session_date,
            'correct_answers' => $request->correct_answers,
            'total_questions' => $request->total_questions,
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Guarda el resultado de una partida de Centinela.
     */
    public function saveCentinelaResult(Request $request)
    {
        $request->validate([
            'difficulty' => 'required|string|in:facil,medio,dificil',
            'session_date' => 'required|date_format:Y-m-d',
            'score' => 'required|integer|min:0',
            'precision' => 'required|integer|min:0|max:100',
            'protected_count' => 'required|integer|min:0',
            'threats' => 'required|integer|min:0',
            'integrity_remaining' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $result = CentinelaResult::create([
            'email' => $user->email,
            'difficulty' => $request->difficulty,
            'session_date' => $request->session_date,
            'score' => $request->score,
            'precision' => $request->precision,
            'protected_count' => $request->protected_count,
            'threats' => $request->threats,
            'integrity_remaining' => $request->integrity_remaining,
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Obtiene todos los registros de juegos de adolescentes del usuario autenticado.
     */
    public function getRecords()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'quizzsense' => QuizzsenseResult::where('email', $user->email)
                ->orderBy('session_date', 'asc')
                ->get(),
            'paises' => PaisesResult::where('email', $user->email)
                ->orderBy('session_date', 'desc')
                ->get(),
            'centinela' => CentinelaResult::where('email', $user->email)
                ->orderBy('session_date', 'desc')
                ->get(),
        ]);
    }
}
