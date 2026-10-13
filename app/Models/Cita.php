<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public static function getBloquesAtencion(): array
    {
        return ['09:00:00', '10:00:00', '11:00:00', '12:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00'];
    }

    /**
     * Lógica profesional para obtener horarios disponibles
     */
    public static function getHorariosDisponibles($fecha, ?int $solicitudAConfirmar = null)
    {
        $fecha = Carbon::parse($fecha);
        if ($fecha->lt(Carbon::today())) {
            return [];
        }

        $fecha = $fecha->format('Y-m-d');

        $bloques = self::getBloquesAtencion();
        $bloques = array_values(array_filter($bloques, fn ($hora) => !Carbon::parse($fecha . ' ' . $hora)->isPast()));
        
        // Obtiene las horas ya ocupadas para la fecha, excluyendo las canceladas
        $ocupadas = self::where('fecha', $fecha)
                         ->where('estado', '!=', self::ESTADO_CANCELADA)
                         ->pluck('hora')
                         ->toArray();

        $solicitudesPendientes = SolicitudCita::whereDate('fecha_hora_propuesta', $fecha)
            ->where('estado', SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION)
            ->when($solicitudAConfirmar, fn ($query) => $query->where('id', '!=', $solicitudAConfirmar))
            ->pluck('fecha_hora_propuesta')
            ->map(fn ($fechaHora) => Carbon::parse($fechaHora)->format('H:i:s'))
            ->all();

        $bloqueados = HorarioBloqueado::whereDate('fecha', $fecha)
            ->pluck('hora')
            ->toArray();

        return array_values(array_diff($bloques, [...$ocupadas, ...$solicitudesPendientes, ...$bloqueados]));
    }
}