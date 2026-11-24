<?php

namespace App\Models;

use App\Models\User;
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

    public static function forUser(User $user): ?self
    {
        $patient = self::where('user_id', $user->id)->first();
        if ($patient || !$user->email) {
            return $patient;
        }

        $matches = self::whereNull('user_id')
            ->where('email', $user->email)
            ->limit(2)
            ->get();

        return $matches->count() === 1 ? $matches->first() : null;
    }

    public function casosClinicos()
    {
        return $this->hasMany(CasoClinico::class, 'paciente_id');
    }
}