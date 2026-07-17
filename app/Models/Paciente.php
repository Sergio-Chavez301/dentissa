<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellidos', 'telefono', 'fecha_nacimiento', 'edad', 'alergias', 'enfermedades', 'tratamientos'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function casosClinicos()
    {
        return $this->hasMany(CasoClinico::class, 'paciente_id');
    }
}