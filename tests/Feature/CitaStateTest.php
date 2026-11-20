<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Servicio;
use App\Models\User;
use App\Models\PushDevice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Services\FirebasePushNotificationService;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CitaStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_cita_state_can_be_updated_to_confirmada_realizada_or_cancelada(): void
    {
        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);

        $user = User::create([
            'username' => 'citauser',
            'nombre' => 'Cita',
            'apellidos' => 'User',
            'email' => 'cita@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);

        $this->actingAs($user);

        $paciente = Patient::create([
            'nombre' => 'Ana',
            'apellidos' => 'Rojas',
            'telefono' => '5551234',
            'email' => 'ana@example.com',
            'fecha_nacimiento' => '1990-01-01',
            'alergias' => 'Ninguna',
            'enfermedades' => 'Ninguna',
            'tratamientos' => 'Ninguno',
        ]);

        $servicio = Servicio::create([
            'nombre' => 'Limpieza',
            'descripcion' => 'Limpieza dental',
            'precio' => 1000,
        ]);

        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'servicio_id' => $servicio->id,
            'fecha' => '2026-08-01',
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);

        $this->put(route('citas.update', $cita->id), [
            'fecha' => $cita->fecha,
            'hora' => $cita->hora,
            'estado' => Cita::ESTADO_CONFIRMADA,
        ])->assertRedirect(route('citas.index'));

        $this->assertSame(Cita::ESTADO_CONFIRMADA, $cita->fresh()->estado);

        $this->put(route('citas.update', $cita->id), [
            'fecha' => $cita->fecha,
            'hora' => $cita->hora,
            'estado' => Cita::ESTADO_REALIZADA,
        ])
            ->assertRedirect(route('citas.index'));

        $this->assertSame(Cita::ESTADO_REALIZADA, $cita->fresh()->estado);

        $this->put(route('citas.update', $cita->id), [
            'fecha' => $cita->fecha,
            'hora' => $cita->hora,
            'estado' => Cita::ESTADO_CANCELADA,
        ])
            ->assertRedirect(route('citas.index'));

        $this->assertSame(Cita::ESTADO_CANCELADA, $cita->fresh()->estado);
    }

    public function test_admin_can_confirm_then_mark_appointment_as_completed_or_no_show(): void
    {
        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);
        $user = User::create([
            'username' => 'admincitas',
            'nombre' => 'Admin',
            'apellidos' => 'Citas',
            'email' => 'admincitas@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
        $patient = Patient::create([
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'telefono' => '5551234',
            'email' => 'luis@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);
        $service = Servicio::create([
            'nombre' => 'Limpieza',
            'descripcion' => 'Limpieza dental',
            'precio' => 1000,
        ]);
        $cita = Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => '2026-10-21',
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);

        $this->actingAs($user)
            ->post(route('citas.status', $cita), ['estado' => Cita::ESTADO_CONFIRMADA])
            ->assertRedirect(route('citas.index'));
        $this->assertSame(Cita::ESTADO_CONFIRMADA, $cita->fresh()->estado);

        $this->post(route('citas.status', $cita), ['estado' => Cita::ESTADO_NO_PRESENTO])
            ->assertRedirect(route('citas.index'));
        $this->assertSame(Cita::ESTADO_NO_PRESENTO, $cita->fresh()->estado);
    }

    public function test_admin_can_reject_pending_appointment_and_cannot_skip_confirmation(): void
    {
        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);
        $user = User::create([
            'username' => 'admintransiciones',
            'nombre' => 'Admin',
            'apellidos' => 'Transiciones',
            'email' => 'admintransiciones@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
        $patient = Patient::create([
            'nombre' => 'Ana',
            'apellidos' => 'Paciente',
            'telefono' => '5554321',
            'email' => 'ana@example.com',
            'fecha_nacimiento' => '1992-02-02',
        ]);
        $service = Servicio::create([
            'nombre' => 'Consulta',
            'descripcion' => 'Consulta dental',
            'precio' => 500,
        ]);
        $cita = Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => '2026-10-22',
            'hora' => '11:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);

        $this->actingAs($user)
            ->post(route('citas.status', $cita), ['estado' => Cita::ESTADO_REALIZADA])
            ->assertSessionHasErrors('estado');
        $this->assertSame(Cita::ESTADO_EN_ESPERA, $cita->fresh()->estado);

        $this->post(route('citas.status', $cita), ['estado' => Cita::ESTADO_CANCELADA])
            ->assertRedirect(route('citas.index'));
        $this->assertSame(Cita::ESTADO_CANCELADA, $cita->fresh()->estado);
    }

    public function test_confirming_or_canceling_a_cita_sends_a_push_to_the_patient_device(): void
    {
        $adminRole = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);
        $patientRole = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Cuenta de paciente',
        ]);
        $administrator = User::create([
            'username' => 'pushadmin',
            'nombre' => 'Admin',
            'apellidos' => 'Push',
            'email' => 'pushadmin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'activo' => true,
        ]);
        $patientUser = User::create([
            'username' => 'pushpatient',
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'email' => 'pushpatient@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $patientRole->id,
            'activo' => true,
        ]);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'telefono' => '5551234',
            'email' => 'pushpatient@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);
        PushDevice::create([
            'user_id' => $patientUser->id,
            'token' => 'fcm-test-device',
            'platform' => 'android',
        ]);
        $service = Servicio::create([
            'nombre' => 'Limpieza',
            'descripcion' => 'Limpieza dental',
            'precio' => 1000,
        ]);
        $confirmedAppointment = Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => '2026-12-01',
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);
        $canceledAppointment = Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => '2026-12-02',
            'hora' => '11:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);

        $push = $this->mock(FirebasePushNotificationService::class);
        $push->shouldReceive('sendToUser')
            ->twice()
            ->withArgs(fn (User $user, string $title, string $body, array $data) =>
                $user->is($patientUser)
                && $title === 'Actualización de tu cita'
                && in_array($data['status'] ?? null, [Cita::ESTADO_CONFIRMADA, Cita::ESTADO_CANCELADA], true)
            )
            ->andReturn(['sent' => 1, 'failed' => 0]);

        $this->actingAs($administrator)
            ->post(route('citas.status', $confirmedAppointment), ['estado' => Cita::ESTADO_CONFIRMADA])
            ->assertRedirect(route('citas.index'));

        $this->post(route('citas.status', $canceledAppointment), ['estado' => Cita::ESTADO_CANCELADA])
            ->assertRedirect(route('citas.index'));
    }

    public function test_confirmed_appointment_reminder_is_sent_once_24_hours_before(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-20 09:00:00'));

        try {
            $role = Role::create([
                'id' => 3,
                'name' => 'Paciente',
                'description' => 'Cuenta de paciente',
            ]);
            $patientUser = User::create([
                'username' => 'reminderpatient',
                'nombre' => 'Ana',
                'apellidos' => 'Paciente',
                'email' => 'reminderpatient@example.com',
                'password' => Hash::make('password123'),
                'role_id' => $role->id,
                'activo' => true,
            ]);
            $patient = Patient::create([
                'user_id' => $patientUser->id,
                'nombre' => 'Ana',
                'apellidos' => 'Paciente',
                'telefono' => '5551234',
                'email' => 'reminderpatient@example.com',
                'fecha_nacimiento' => '1990-01-01',
            ]);
            PushDevice::create([
                'user_id' => $patientUser->id,
                'token' => 'fcm-reminder-device',
                'platform' => 'android',
            ]);
            $service = Servicio::create([
                'nombre' => 'Limpieza',
                'descripcion' => 'Limpieza dental',
                'precio' => 1000,
            ]);
            $appointment = Cita::create([
                'paciente_id' => $patient->id,
                'servicio_id' => $service->id,
                'fecha' => '2026-11-21',
                'hora' => '09:00:30',
                'estado' => Cita::ESTADO_CONFIRMADA,
            ]);

            $this->mock(FirebasePushNotificationService::class)
                ->shouldReceive('sendToUser')
                ->once()
                ->withArgs(fn (User $user, string $title, string $body, array $data) =>
                    $user->is($patientUser)
                    && $title === 'Recordatorio de cita'
                    && ($data['type'] ?? null) === 'appointment_reminder'
                )
                ->andReturn(['sent' => 1, 'failed' => 0]);

            $this->artisan('citas:enviar-recordatorios')->assertSuccessful();
            $this->assertNotNull($appointment->fresh()->reminder_sent_at);

            $this->artisan('citas:enviar-recordatorios')->assertSuccessful();
        } finally {
            Carbon::setTestNow();
        }
    }
}
