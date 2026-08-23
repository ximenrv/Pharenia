<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentinelaResult extends Model
{
    use HasFactory;

    protected $table = 'centinela_results';

    protected $fillable = [
        'email',
        'difficulty',
        'session_date',
        'score',
        'precision',
        'protected_count',
        'threats',
        'integrity_remaining',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
