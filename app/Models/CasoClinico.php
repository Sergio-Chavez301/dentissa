<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasoClinico extends Model
{
    use HasFactory;

    protected $table = 'casos_clinicos';

    const ESTADO_ACTIVO = 'activo';
    const ESTADO_FINALIZADO = 'finalizado';
    const ESTADO_SUSPENDIDO = 'suspendido';

    protected $fillable = [
        'paciente_id',
        'tratamiento_base',
        'progreso',
        'estado',
    ];

    public function paciente()
    {
        return $this->belongsTo(Patient::class, 'paciente_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'caso_clinico_id')
            ->orderBy('fecha')
            ->orderBy('hora');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }
}