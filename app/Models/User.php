<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'username',
        'nombre',
        'apellidos',
        'email',
        'password',
        'role_id', // Tu llave foránea exacta
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relación con el modelo de Rol.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}