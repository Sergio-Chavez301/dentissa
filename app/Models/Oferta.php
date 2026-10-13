<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertas';

    protected $fillable = [
        'titulo',
        'tag',
        'descripcion',
        'precio',
        'orden',
        'activo',
    ];

    protected $casts = [
        'precio' => 'float',
        'orden' => 'integer',
        'activo' => 'boolean',
    ];
}
