<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function casosClinicos()
    {
        return $this->hasMany(CasoClinico::class, 'paciente_id');
    }
}