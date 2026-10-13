<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioBloqueado extends Model
{
    protected $table = 'horarios_bloqueados';

    protected $fillable = ['fecha', 'hora', 'motivo', 'usuario_id'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}