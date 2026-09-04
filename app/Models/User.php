<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Roles del sistema. El rol "visitor" (Visitante General) solo puede
     * registrarse con una edad mayor a 12 años cumplidos.
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_ADULT_TEA = 'adult_tea';
    public const ROLE_ALLY = 'ally_no_tea';
    public const ROLE_TEEN = 'teen';
    public const ROLE_VISITOR = 'visitor';

    /**
     * Roles con acceso a la visualización general de la plataforma.
     * El Visitante General navega igual que un Adulto Autogestor (TEA),
     * pero SIN las funciones exclusivas de administración.
     */
    public const GENERAL_ACCESS_ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_ADULT_TEA,
        self::ROLE_ALLY,
        self::ROLE_TEEN,
        self::ROLE_VISITOR,
    ];

    /** Roles con acceso exclusivo al panel de administración. */
    public const ADMIN_ROLES = [
        self::ROLE_ADMIN,
    ];

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

    /**
     * Determina si el usuario posee alguno de los roles indicados.
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isVisitor(): bool
    {
        return $this->role === self::ROLE_VISITOR;
    }

    public function isAdultTea(): bool
    {
        return $this->role === self::ROLE_ADULT_TEA;
    }

    public function isAlly(): bool
    {
        return $this->role === self::ROLE_ALLY;
    }

    public function isTeen(): bool
    {
        return $this->role === self::ROLE_TEEN;
    }

    /**
     * Política de acceso general: el Visitante General (mayor de 12 años)
     * ve la plataforma igual que el rol adult_tea, sin tocar administración.
     */
    public function hasGeneralAccess(): bool
    {
        return in_array($this->role, self::GENERAL_ACCESS_ROLES, true);
    }

    /**
     * Política de acceso administrativo: SOLO el rol admin.
     */
    public function canAccessAdmin(): bool
    {
        return in_array($this->role, self::ADMIN_ROLES, true);
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