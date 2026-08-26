<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordGamesChild extends Model
{
    use HasFactory;

    protected $table = 'record_games_children';

    protected $fillable = [
        'user_id',
        'child_profile_id',
        'record_Eco',
        'record_Guardianes',
        'record_Cazador',
    ];

    // Relación inversa opcional con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
