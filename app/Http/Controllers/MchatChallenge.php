<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MchatChallenge as MchatModel;

class MchatChallenge extends Controller
{
    public function index()
    {
        $progress = null;
        if (Auth::check()) {
            $progress = MchatModel::where('user_id', Auth::id())
                ->where('mchat_is_completed', false)
                ->latest()
                ->first();

            if (!$progress) {
                $progress = MchatModel::where('user_id', Auth::id())
                    ->latest()
                    ->first();

                if (!$progress || $progress->mchat_is_completed) {
                    // Calcula el número de intento automáticamente
                    $lastAttempt = MchatModel::where('user_id', Auth::id())->max('mchat_attempt');
                    $newAttemptNumber = $lastAttempt ? $lastAttempt + 1 : 1;

                    $progress = MchatModel::create([
                        'user_id' => Auth::id(),
                        'mchat_attempt' => $newAttemptNumber,
                        'mchat_answers' => [],
                        'mchat_current_step' => 1,
                        'mchat_is_completed' => false,
                    ]);
                }
            }
        }

        return view('information', compact('progress'));
    }

    public function saveProgress(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => __('mchat.unauthorized')], 401);
        }

        $step = $request->input('current_step', 1);
        $answers = $request->input('answers', []);

        // Asegurarnos de decodificar si viene como string
        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        // Busca el borrador activo del usuario para actualizarlo en tiempo real
        $progress = MchatModel::where('user_id', Auth::id())
            ->where('mchat_is_completed', false)
            ->latest()
            ->first();

        if ($progress) {
            $progress->mchat_answers = $answers;
            $progress->mchat_current_step = $step;
            $progress->save();
        } else {
            MchatModel::create([
                'user_id' => Auth::id(),
                'mchat_answers' => $answers,
                'mchat_current_step' => $step,
                'mchat_is_completed' => false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function calculateResult(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => __('mchat.unauthorized')], 401);
        }

        $answers = $request->input('answers', []);
        
        // Lógica de cálculo de puntaje M-CHAT-R
        $score = 0;
        foreach ($answers as $question => $answer) {
            if (in_array($question, ['q_2', 'q_5', 'q_12']) && $answer === 'no') {
                $score++;
            } elseif (!in_array($question, ['q_2', 'q_5', 'q_12']) && $answer === 'si') {
                $score++;
            }
        }

        $riskLevel = 'Bajo';
        if ($score >= 3 && $score <= 7) {
            $riskLevel = 'Medio';
        } elseif ($score >= 8) {
            $riskLevel = 'Alto';
        }

        // Buscamos el borrador activo actual y lo ACTUALIZAMOS
        $progress = MchatModel::where('user_id', Auth::id())
            ->where('mchat_is_completed', false)
            ->latest()
            ->first();

        if ($progress) {
            $progress->update([
                'mchat_answers' => $answers,
                'mchat_current_step' => 3,
                'mchat_is_completed' => true,
                'mchat_total_score' => $score,
                'mchat_risk_level' => $riskLevel,
            ]);
        } else {
            MchatModel::create([
                'user_id' => Auth::id(),
                'mchat_answers' => $answers,
                'mchat_current_step' => 3,
                'mchat_is_completed' => true,
                'mchat_total_score' => $score,
                'mchat_risk_level' => $riskLevel,
            ]);
        }

        return response()->json([
            'success' => true,
            'score' => $score,
            'risk_level' => $riskLevel,
            'feedback' => $this->getFeedbackByRisk($riskLevel),
        ]);
    }

    public function resetTest()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $lastAttempt = MchatModel::where('user_id', Auth::id())->max('mchat_attempt');
        $newAttemptNumber = $lastAttempt ? $lastAttempt + 1 : 1;

        MchatModel::create([
            'user_id' => Auth::id(),
            'mchat_attempt' => $newAttemptNumber,
            'mchat_answers' => [],
            'mchat_current_step' => 1,
            'mchat_is_completed' => false,
        ]);

        return redirect()->route('information', ['module' => 'mchat']);
    }

    private function getFeedbackByRisk($riskLevel)
    {
        switch ($riskLevel) {
            case 'Alto':
                return __('mchat.risk_high_feedback');
            case 'Medio':
                return __('mchat.risk_medium_feedback');
            default:
                return __('mchat.risk_low_feedback');
        }
    }
}