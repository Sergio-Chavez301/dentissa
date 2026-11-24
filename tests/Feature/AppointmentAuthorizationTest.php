<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AppointmentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_cannot_update_appointments(): void
    {
        $this->actingAs($this->patientUser());

        $this->put(route('citas.update', 1))->assertForbidden();
    }

    public function test_patient_cannot_process_prospect_requests(): void
    {
        $this->actingAs($this->patientUser());

        $this->put(route('solicitudes.update', 1))->assertForbidden();
    }

    public function test_patient_cannot_access_staff_or_administrator_routes_by_changing_the_url(): void
    {
        $this->actingAs($this->patientUser());

        $this->get(route('dashboard.admin'))->assertForbidden()->assertSee('Acceso denegado');
        $this->get(route('dashboard.asistente'))->assertForbidden();
        $this->get(route('reportes.index'))->assertForbidden();
        $this->get(route('users.index'))->assertForbidden();
        $this->get(route('patients.index'))->assertForbidden();
        $this->get(route('citas.create'))->assertForbidden();
        $this->get(route('casos.create'))->assertForbidden();
        $this->get(route('solicitudes.index'))->assertForbidden();
        $this->get(route('servicios.index'))->assertForbidden();
        $this->get(route('horarios-bloqueados.index'))->assertForbidden();
        $this->get(route('admin.documentation.index'))->assertForbidden()->assertSee('Acceso denegado');
    }

    public function test_patient_can_open_the_general_appointment_request_form(): void
    {
        $user = $this->patientUser();
        Patient::create([
            'user_id' => $user->id,
            'nombre' => 'Alex',
            'apellidos' => 'Demo',
            'telefono' => '5551234567',
            'email' => $user->email,
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $this->actingAs($user)
            ->get(route('citas.patient.create'))
            ->assertOk()
            ->assertSee('Agendar Nueva Cita')
            ->assertSee('Seleccione un servicio...')
            ->assertDontSee('Registrar nuevo paciente para esta cita')
            ->assertDontSee('Paciente Existente');
    }

    public function test_assistant_cannot_access_administrator_only_routes(): void
    {
        $role = Role::create([
            'id' => 2,
            'name' => 'Asistente',
            'description' => 'Acceso de asistente',
        ]);
        $assistant = User::create([
            'username' => 'assistant',
            'nombre' => 'Ana',
            'apellidos' => 'Asistente',
            'email' => 'assistant@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);

        $this->actingAs($assistant)
            ->get(route('dashboard.admin'))
            ->assertForbidden();

        $this->actingAs($assistant)
            ->get(route('horarios-bloqueados.index'))
            ->assertForbidden();

        $this->actingAs($assistant)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_patient_profile_lookup_prefers_the_user_bound_record(): void
    {
        $user = $this->patientUser();
        $otherUser = User::create([
            'username' => 'other-patient',
            'nombre' => 'Otro',
            'apellidos' => 'Paciente',
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
            'activo' => true,
        ]);
        Patient::create([
            'user_id' => $otherUser->id,
            'nombre' => 'Perfil ajeno',
            'apellidos' => 'Paciente',
            'telefono' => '5550000',
            'email' => $user->email,
            'fecha_nacimiento' => '1990-01-01',
        ]);
        $ownPatient = Patient::create([
            'user_id' => $user->id,
            'nombre' => 'Perfil propio',
            'apellidos' => 'Paciente',
            'telefono' => '5551111',
            'email' => $user->email,
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $this->assertSame($ownPatient->id, Patient::forUser($user)?->id);
    }

    private function patientUser(): User
    {
        $role = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Acceso para pacientes',
        ]);

        return User::create([
            'username' => 'patient',
            'nombre' => 'Alex',
            'apellidos' => 'Demo',
            'email' => 'alex@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
    }
}