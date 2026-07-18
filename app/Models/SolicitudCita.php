<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCita extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_citas'; // Definimos el nombre de tu tabla

    protected $fillable = [
        'nombre', 
        'apellidos', 
        'telefono', 
        'email', 
        'fecha_hora_propuesta', 
        'motivo_consulta', 
        'estado',
        'fecha_nacimiento'
    ];
}   