<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;

class ActualizarCitasVencidas extends Command
{
    protected $signature = 'citas:actualizar-vencidas';

    protected $description = 'Marca automáticamente como "no_presento" las citas vencidas pendientes o confirmadas';

    public function handle()
    {
        $hoy = Carbon::today();
        
        $citasVencidas = Cita::whereIn('estado', [Cita::ESTADO_EN_ESPERA, Cita::ESTADO_CONFIRMADA])
            ->where('fecha', '<', $hoy)
            ->get();

        $count = $citasVencidas->count();

        foreach ($citasVencidas as $cita) {
            $cita->update(['estado' => Cita::ESTADO_NO_PRESENTO]);
        }

        $this->info("Se han marcado $count cita(s) como 'no presentó'.");
    }
}