<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildProfile extends Model
{
    use HasFactory;

    protected $table = 'child_profile';

    protected $fillable = [
        'user_id',
        'name',
        'birthdate',
        'parent_pin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}