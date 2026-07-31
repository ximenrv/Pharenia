<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationChallenge extends Model
{
    use HasFactory;

    protected $table = 'simulation_challenge';

    protected $fillable = [
        'user_id',
        'simulation_attempt',
        'simulation_answers',
        'simulation_current_step',
        'simulation_is_completed',
        'simulation_empathy_level',
    ];

    protected $casts = [
    'simulation_answers' => 'array',
];
}