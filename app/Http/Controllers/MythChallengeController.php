<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MythChallenge;
use Illuminate\Support\Facades\Auth;

class MythChallengeController extends Controller
{
   public function getProgress(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => __('myth.unauthorized')], 401);
        }

        $challenge = MythChallenge::where('user_id', $userId)
            ->where('myth_is_completed', 0)
            ->first();

        if (!$challenge) {
            $lastAttempt = MythChallenge::where('user_id', $userId)->max('myth_attempt') ?? 0;
            $newAttempt = $lastAttempt + 1;

            $challenge = MythChallenge::create([
                'user_id' => $userId,
                'myth_attempt' => $newAttempt,
                'myth_answers' => [],
                'myth_current_step' => 1,
                'myth_is_completed' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'step' => $challenge->myth_current_step,
            'answers' => $challenge->myth_answers ?? [],
        ]);
    }

    public function saveProgress(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => __('myth.unauthorized')], 401);
        }

        $step = $request->input('current_step', 1);
        $answers = $request->input('answers', []);

        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        $challenge = MythChallenge::where('user_id', $userId)
            ->where('myth_is_completed', 0)
            ->first();

        if ($challenge) {
            $challenge->myth_answers = !empty($answers) ? $answers : ($challenge->myth_answers ?? []);
            $challenge->myth_current_step = $step;
            $challenge->save();
        } else {
            $lastAttempt = MythChallenge::where('user_id', $userId)->max('myth_attempt') ?? 0;
            MythChallenge::create([
                'user_id' => $userId,
                'myth_attempt' => $lastAttempt + 1,
                'myth_answers' => $answers,
                'myth_current_step' => $step,
                'myth_is_completed' => 0,
            ]);
        }

        return response()->json(['success' => true, 'step' => $step]);
    }

    public function submitChallenge(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => __('myth.unauthorized')], 401);
        }

        $answers = $request->input('answers', []);
        if (is_string($answers)) {
            $answers = json_decode($answers, true) ?? [];
        }

        $correctAnswers = [
            'q_1' => false,
            'q_2' => true,
            'q_3' => false,
            'q_4' => true,
            'q_5' => false,
            'q_6' => true,
            'q_7' => false,
            'q_8' => true,
            'q_9' => true,
            'q_10' => false,
        ];

        $correctCount = 0;
        foreach ($answers as $key => $answerData) {
            $rawChoice = is_array($answerData) ? ($answerData['userChoice'] ?? null) : $answerData;
            $userBool = filter_var($rawChoice, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            if ($userBool !== null && isset($correctAnswers[$key]) && $correctAnswers[$key] === $userBool) {
                $correctCount++;
            }
        }

        $resultText = "{$correctCount}/10 " . __('myth.hits');

        $challenge = MythChallenge::where('user_id', $userId)
            ->where('myth_is_completed', 0)
            ->first();

        if ($challenge) {
            $challenge->myth_answers = !empty($answers) ? $answers : ($challenge->myth_answers ?? []);
            $challenge->myth_current_step = 10;
            $challenge->myth_is_completed = 1;
            $challenge->myth_result = $resultText;
            $challenge->save();
        }

        return response()->json([
            'success' => true,
            'score' => $resultText,
            'feedback' => __('myth.completion_feedback'),
        ]);
    }

    public function resetChallenge(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => __('myth.unauthorized')], 401);
        }

        MythChallenge::where('user_id', $userId)
            ->where('myth_is_completed', 0)
            ->update([
                'myth_is_completed' => 1,
                'myth_result' => __('myth.abandoned'),
            ]);

        $lastAttempt = MythChallenge::where('user_id', $userId)->max('myth_attempt') ?? 0;

        $challenge = MythChallenge::create([
            'user_id' => $userId,
            'myth_attempt' => $lastAttempt + 1,
            'myth_answers' => [],
            'myth_current_step' => 1,
            'myth_is_completed' => 0,
        ]);

        return response()->json([
            'success' => true,
            'step' => $challenge->myth_current_step,
            'answers' => $challenge->myth_answers ?? [],
        ]);
    }
}