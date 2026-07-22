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
        'user_id',
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
     * Relación con la tabla de Usuarios (Acceso Web)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con Citas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    /**
     * Relación con Casos Clínicos
     */
    public function casosClinicos()
    {
        return $this->hasMany(CasoClinico::class, 'paciente_id');
    }
}