<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'user_id',
        'servicio_id',
        'fecha',
        'hora',
        'estado',
    ];

    // Relación: La cita pertenece a un paciente (Usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: La cita corresponde a un servicio específico
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}