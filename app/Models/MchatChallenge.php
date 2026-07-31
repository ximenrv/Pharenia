<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MchatChallenge extends Model
{
    use HasFactory;

    protected $table = 'mchat_challenge';

    protected $fillable = [
        'user_id',
        'mchat_attempt', // <-- Cambiado de attempt_number a mchat_attempt
        'mchat_answers',
        'mchat_current_step',
        'mchat_is_completed',
        'mchat_total_score',
        'mchat_risk_level',
    ];

    protected $casts = [
        'mchat_answers' => 'array',
    ];
}