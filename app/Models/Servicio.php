<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
    ];

    /**
     * Relación con las citas: Un servicio puede tener muchas citas asociadas.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id');
    }
}