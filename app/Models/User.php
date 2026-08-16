<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'birthdate',
        'role',
        'parent_pin',
        'supervisor_id', // Asegúrate de incluir esta si quieres asignarla por fillable
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'parent_pin',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date:Y-m-d',
            'password' => 'hashed',
        ];
    }

    public function children()
    {
        return $this->hasMany(ChildProfile::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}