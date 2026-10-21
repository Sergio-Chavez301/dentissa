<?php

namespace Tests\Feature;

use App\Models\CasoClinico;
use App\Models\Cita;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_asistente_dashboard_shows_summary_and_todays_appointments(): void
    {
        $role = Role::create([
            'id' => 2,
            'name' => 'Asistente',
            'description' => 'Asistencia clínica',
        ]);

        $user = User::create([
            'username' => 'asistente1',
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'email' => 'asistente@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $patient = Patient::create([
            'nombre' => 'Luis',
            'apellidos' => 'García',
            'telefono' => '5551234',
            'email' => 'luis@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $servicio = Servicio::create([
            'nombre' => 'Limpieza dental',
            'descripcion' => 'Limpieza',
            'precio' => 1200,
        ]);

        Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $servicio->id,
            'fecha' => Carbon::today()->toDateString(),
            'hora' => '10:30:00',
            'estado' => 'en_espera',
        ]);

        CasoClinico::create([
            'paciente_id' => $patient->id,
            'tratamiento_base' => 'Ortodoncia',
            'progreso' => 40,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.asistente'));

        $response->assertOk();
        $response->assertSee('Panel de asistente');
        $response->assertSee('Próximas citas');
        $response->assertSee('Ortodoncia');
    }

    public function test_paciente_dashboard_shows_personal_overview(): void
    {
        $role = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Paciente del sistema',
        ]);

        $user = User::create([
            'username' => 'paciente1',
            'nombre' => 'Marta',
            'apellidos' => 'López',
            'email' => 'marta@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'nombre' => 'Marta',
            'apellidos' => 'López',
            'telefono' => '5551234',
            'email' => 'marta@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $servicio = Servicio::create([
            'nombre' => 'Ortodoncia',
            'descripcion' => 'Control de ortodoncia',
            'precio' => 800,
        ]);
        $caso = CasoClinico::create([
            'paciente_id' => $patient->id,
            'tratamiento_base' => 'Ortodoncia',
            'progreso' => 65,
            'estado' => CasoClinico::ESTADO_ACTIVO,
        ]);
        Cita::create([
            'paciente_id' => $patient->id,
            'caso_clinico_id' => $caso->id,
            'servicio_id' => $servicio->id,
            'fecha' => '2026-10-10',
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_REALIZADA,
        ]);
        Cita::create([
            'paciente_id' => $patient->id,
            'caso_clinico_id' => $caso->id,
            'servicio_id' => $servicio->id,
            'fecha' => '2026-10-30',
            'hora' => '11:00:00',
            'estado' => Cita::ESTADO_CONFIRMADA,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.paciente'));

        $response->assertOk();
        $response->assertSee('Bienvenido(a), Marta López');
        $response->assertSee('Perfil personal');
        $response->assertSee('Mis Citas');
        $response->assertSee('Mis tratamientos');
        $response->assertSee('Confirmadas');
        $response->assertSee('Citas realizadas para este tratamiento');
        $response->assertSee('65%');
        $response->assertSee(route('ofertas.public'));
    }

    public function test_paciente_without_linked_profile_sees_guidance_instead_of_error(): void
    {
        $role = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Paciente del sistema',
        ]);

        $user = User::create([
            'username' => 'paciente_sin_perfil',
            'nombre' => 'Marta',
            'apellidos' => 'López',
            'email' => 'marta.sinperfil@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.paciente'));

        $response->assertOk();
        $response->assertSee('Tu expediente aún no está vinculado');
    }
}
