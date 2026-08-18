<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MythChallenge extends Model
{
    use HasFactory;

    protected $table = 'myth_challenge';

    protected $fillable = [
        'user_id',
        'myth_attempt',
        'myth_answers',
        'myth_current_step',
        'myth_is_completed',
        'myth_result',
    ];

    protected $casts = [
        'myth_answers' => 'array',
    ];
}