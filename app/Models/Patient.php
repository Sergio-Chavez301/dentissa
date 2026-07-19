<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'pacientes';

    protected $fillable = [
        'nombre', 
        'apellidos', 
        'telefono', 
        'email',
        'fecha_nacimiento', 
        'alergias', 
        'enfermedades', 
        'tratamientos'
    ];

    /**
     * Relación con Citas
     * Asumiendo que tu tabla de citas se llama 'citas'
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    /**
     * Relación con Casos Clínicos
     * Asumiendo que tu modelo se llama CasoClinico y la tabla 'casos_clinicos'
     */
    public function casosClinicos()
    {
        return $this->hasMany(CasoClinico::class, 'paciente_id');
    }
}