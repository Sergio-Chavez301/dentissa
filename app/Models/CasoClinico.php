<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasoClinico extends Model
{
    use HasFactory;

    // CORRECCIÓN: Le indicamos a Laravel el nombre exacto de la tabla en español
    protected $table = 'casos_clinicos';

    protected $fillable = [
        'paciente_id',
        'tratamiento_base',
        'progreso',
        'estado',
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
}