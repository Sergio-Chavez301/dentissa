<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AppAppointmentRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_requests_are_listed_and_can_be_confirmed_from_the_requests_page(): void
    {
        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);
        $admin = User::create([
            'username' => 'admin_app_requests',
            'nombre' => 'Admin',
            'apellidos' => 'Dentissa',
            'email' => 'admin-app-requests@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
        $patient = Patient::create([
            'nombre' => 'Paciente App',
            'apellidos' => 'Prueba',
            'telefono' => '5551234567',
            'email' => 'patient-app-requests@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);
        $internalPatient = Patient::create([
            'nombre' => 'Paciente Interno',
            'apellidos' => 'Prueba',
            'telefono' => '5557654321',
            'email' => 'patient-internal@example.com',
            'fecha_nacimiento' => '1991-01-01',
        ]);
        $service = Servicio::create([
            'nombre' => 'Consulta',
            'descripcion' => 'Consulta general',
            'precio' => 300,
        ]);
        $appointment = Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '09:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
            'origen' => Cita::ORIGEN_APP,
        ]);
        Cita::create([
            'paciente_id' => $internalPatient->id,
            'servicio_id' => $service->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
            'origen' => Cita::ORIGEN_CONSULTORIO,
        ]);

        $this->actingAs($admin)
            ->get(route('solicitudes.index'))
            ->assertOk()
            ->assertSee('Solicitudes de Cita')
            ->assertSee('Paciente App')
            ->assertSee('App')
            ->assertSee('Confirmar')
            ->assertDontSee('Paciente Interno');

        $this->actingAs($admin)
            ->post(route('citas.status', $appointment), [
                'estado' => Cita::ESTADO_CONFIRMADA,
                'return_to' => 'solicitudes.index',
            ])
            ->assertRedirect(route('solicitudes.index'));

        $this->assertSame(Cita::ESTADO_CONFIRMADA, $appointment->fresh()->estado);
    }
}
