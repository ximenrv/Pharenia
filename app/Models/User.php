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
        'email',
        'birthdate',
        'role',
        'supervisor_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function children()
    {
        return $this->hasMany(ChildProfile::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}