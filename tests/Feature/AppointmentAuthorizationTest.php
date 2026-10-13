<?php

namespace Tests\Feature;

use App\Models\Role;
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