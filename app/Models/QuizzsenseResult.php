<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzsenseResult extends Model
{
    use HasFactory;

    protected $table = 'quizzsense_results';

    protected $fillable = [
        'email',
        'session_date',
        'correct_answers',
        'total_questions',
        'category_summary',
    ];

    protected $casts = [
        'session_date' => 'date',
        'category_summary' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
