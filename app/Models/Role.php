<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // 1. Le indicamos a Laravel que tu tabla se llama 'roles'
    protected $table = 'roles';

    // 2. Definimos los campos que se pueden llenar de forma masiva
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relación inversa: Un Rol tiene muchos Usuarios.
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}