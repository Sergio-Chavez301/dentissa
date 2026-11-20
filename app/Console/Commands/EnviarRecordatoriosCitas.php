<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Services\FirebasePushNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:enviar-recordatorios';

    protected $description = 'Envía notificaciones push 24 horas antes de las citas confirmadas';

    public function handle(FirebasePushNotificationService $pushNotifications): int
    {
        $windowStart = now()->addDay();
        $windowEnd = $windowStart->copy()->addMinute();
        $appointments = Cita::with(['paciente.user.pushDevices'])
            ->where('estado', Cita::ESTADO_CONFIRMADA)
            ->whereNull('reminder_sent_at')
            ->whereBetween('fecha', [$windowStart->toDateString(), $windowEnd->toDateString()])
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            $appointmentAt = Carbon::parse($appointment->fecha . ' ' . $appointment->hora);
            if ($appointmentAt->lt($windowStart) || $appointmentAt->gt($windowEnd)) {
                continue;
            }

            $user = $appointment->paciente?->user;
            if (!$user || !$user->activo || $user->pushDevices->isEmpty()) {
                continue;
            }

            try {
                $result = $pushNotifications->sendToUser(
                    $user,
                    'Recordatorio de cita',
                    'Te recordamos que tienes una cita en Dentissa mañana. Abre la app para consultar los detalles.',
                    [
                        'type' => 'appointment_reminder',
                        'appointment_id' => (string) $appointment->id,
                    ]
                );

                if ($result['sent'] > 0) {
                    $appointment->forceFill(['reminder_sent_at' => now()])->save();
                    $sent += $result['sent'];
                }

                $failed += $result['failed'];
            } catch (Throwable $exception) {
                report($exception);
                $failed++;
            }
        }

        $this->info("Recordatorios enviados a {$sent} dispositivo(s); {$failed} envío(s) fallido(s).");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
