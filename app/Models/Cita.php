<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    // Constantes para estados
    const ESTADO_EN_ESPERA = 'en_espera';
    const ESTADO_REALIZADA  = 'realizada';
    const ESTADO_CANCELADA  = 'cancelada';
    const ESTADO_NO_PRESENTO = 'no_presento';

    protected $fillable = ['paciente_id', 'servicio_id', 'fecha', 'hora', 'estado'];

    public function paciente() { return $this->belongsTo(Patient::class, 'paciente_id'); }
    public function servicio() { return $this->belongsTo(Servicio::class, 'servicio_id'); }
}