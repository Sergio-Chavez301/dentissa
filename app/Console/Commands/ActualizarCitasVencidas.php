<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;

class ActualizarCitasVencidas extends Command
{
    protected $signature = 'citas:actualizar-vencidas';

    protected $description = 'Marca automáticamente como "no_presento" las citas pasadas que siguen en estado "en_espera"';

    public function handle()
    {
        $hoy = Carbon::today();
        
        $citasVencidas = Cita::where('estado', Cita::ESTADO_EN_ESPERA)
            ->where('fecha', '<', $hoy)
            ->get();

        $count = $citasVencidas->count();

        foreach ($citasVencidas as $cita) {
            $cita->update(['estado' => Cita::ESTADO_NO_PRESENTO]);
        }

        $this->info("Se han marcado $count cita(s) como 'no presentó'.");
    }
}