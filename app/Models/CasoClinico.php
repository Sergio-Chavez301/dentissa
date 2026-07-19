<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasoClinico extends Model
{
    use HasFactory;

    protected $table = 'casos_clinicos';

    // Definición de constantes para los estados
    const ESTADO_ACTIVO = 'activo';
    const ESTADO_FINALIZADO = 'finalizado';
    const ESTADO_SUSPENDIDO = 'suspendido';

    protected $fillable = [
        'paciente_id',
        'tratamiento_base',
        'progreso',
        'estado',
    ];

    /**
     * Relación con el modelo Paciente.
     */
    public function paciente()
    {
        return $this->belongsTo(Patient::class, 'paciente_id');
    }

    /**
     * Scope para filtrar casos activos (útil para el dashboard).
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }
}