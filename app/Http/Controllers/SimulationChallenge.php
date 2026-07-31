<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimulationChallenge as SimulationModel;
use Illuminate\Support\Facades\Auth;

class SimulationChallenge extends Controller
{
    public function getProgress(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $simulation = SimulationModel::where('user_id', $userId)
            ->where('simulation_is_completed', 0)
            ->first();

        if (!$simulation) {
            $lastAttempt = SimulationModel::where('user_id', $userId)->max('simulation_attempt') ?? 0;
            $newAttempt = $lastAttempt + 1;

            $simulation = SimulationModel::create([
                'user_id' => $userId,
                'simulation_attempt' => $newAttempt,
                'simulation_answers' => [],
                'simulation_current_step' => 1,
                'simulation_is_completed' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'step' => $simulation->simulation_current_step,
            'answers' => $simulation->simulation_answers ?? [],
        ]);
    }

    public function saveProgress(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $step = $request->input('current_step', 1);
        $answers = $request->input('answers', []);

        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        $simulation = SimulationModel::where('user_id', $userId)
            ->where('simulation_is_completed', 0)
            ->first();

        if ($simulation) {
            // Forzamos el guardado del arreglo asociativo de forma segura
            $simulation->simulation_answers = !empty($answers) ? $answers : ($simulation->simulation_answers ?? []);
            $simulation->simulation_current_step = $step;
            $simulation->save();
        } else {
            SimulationModel::create([
                'user_id' => $userId,
                'simulation_answers' => $answers,
                'simulation_current_step' => $step,
                'simulation_is_completed' => 0,
            ]);
        }

        return response()->json(['success' => true, 'step' => $step]);
    }

    public function submitSimulation(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $answers = $request->input('answers', []);
        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        $step = $request->input('current_step', 5);
        $empathyLevel = "Aliado Compasivo Destacado";

        $simulation = SimulationModel::where('user_id', $userId)
            ->where('simulation_is_completed', 0)
            ->first();

        if ($simulation) {
            $finalAnswers = !empty($answers) ? $answers : ($simulation->simulation_answers ?? []);

            $simulation->simulation_answers = $finalAnswers;
            $simulation->simulation_current_step = $step;
            $simulation->simulation_is_completed = 1;
            $simulation->simulation_empathy_level = $empathyLevel;
            $simulation->save();
        }

        return response()->json([
            'success' => true,
            'empathy_level' => $empathyLevel,
            'feedback' => '¡Felicidades! Has completado todas las situaciones demostrando un gran nivel de empatía y comprensión hacia el TEA.',
        ]);
    }
}