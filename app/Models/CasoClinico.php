<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasoClinico extends Model
{
    // Nombre exacto de tu tabla
    protected $table = 'casos_clinicos';

    protected $fillable = [
        'user_id',
        'tratamiento_base',
        'progreso',
        'estado',
    ];

    // Relación: El caso clínico pertenece a un paciente (Usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}