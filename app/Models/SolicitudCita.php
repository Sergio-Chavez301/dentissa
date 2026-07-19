<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCita extends Model
{
    use HasFactory;

    // Constantes para estados
    const ESTADO_ESPERANDO_CONFIRMACION = 'esperando_confirmacion';
    const ESTADO_CANCELADA               = 'cancelada';
    const ESTADO_CONFIRMADA              = 'confirmada';

    protected $table = 'solicitudes_citas';
    protected $fillable = [
        'nombre', 'apellidos', 'telefono', 'email', 
        'fecha_hora_propuesta', 'motivo_consulta', 'estado', 'fecha_nacimiento'
    ];
}