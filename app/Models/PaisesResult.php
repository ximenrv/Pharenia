<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaisesResult extends Model
{
    use HasFactory;

    protected $table = 'paises_results';

    protected $fillable = [
        'email',
        'continent',
        'session_date',
        'correct_answers',
        'total_questions',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
