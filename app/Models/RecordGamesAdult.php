<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordGamesAdult extends Model
{
    use HasFactory;

    protected $table = 'record_games_adults';

    protected $fillable = [
        'email',
        'stars_OfertaOEngano',
        'stars_SigueLaReceta',
        'stars_CuentasClaras',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
