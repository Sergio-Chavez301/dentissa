<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Nombre exacto de tu tabla de usuarios
    protected $table = 'usuarios';

    protected $fillable = [
        'username',
        'nombre',
        'apellidos',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación con el Rol
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'user_id');
    }

    public function casosClinicos()
    {
        return $this->hasMany(CasoClinico::class, 'user_id');
    }
}