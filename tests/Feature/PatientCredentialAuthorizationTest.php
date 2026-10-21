<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PatientCredentialAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assistant_cannot_manage_patient_credentials(): void
    {
        Role::create(['id' => 2, 'name' => 'Asistente', 'description' => 'Asistencia']);
        Role::create(['id' => 3, 'name' => 'Paciente', 'description' => 'Paciente']);

        $assistant = User::create([
            'username' => 'asistente',
            'nombre' => 'Ana',
            'apellidos' => 'Asistente',
            'email' => 'asistente@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
        ]);
        $patientUser = User::create([
            'username' => 'paciente',
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'email' => 'paciente@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
        ]);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'telefono' => '5551234',
            'email' => 'paciente@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $this->actingAs($assistant)
            ->post(route('patients.credentials.update', $patient), [
                'username' => 'nuevo_usuario',
                'password' => 'nueva1234',
                'password_confirmation' => 'nueva1234',
            ])
            ->assertForbidden();

        $this->actingAs($assistant)
            ->post(route('patients.whatsapp', $patient))
            ->assertForbidden();

        $this->actingAs($assistant)
            ->put(route('users.update', $patientUser), [
                'nombre' => 'Luis',
                'apellidos' => 'Paciente',
                'username' => 'nuevo_usuario',
                'email' => 'paciente@example.com',
                'role_id' => 3,
            ])
            ->assertForbidden();

        $this->assertSame('paciente', $patientUser->fresh()->username);
    }

    public function test_patient_cannot_open_another_patients_record(): void
    {
        Role::create(['id' => 3, 'name' => 'Paciente', 'description' => 'Paciente']);

        $patientUser = User::create([
            'username' => 'paciente_uno',
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'email' => 'paciente@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
        ]);
        $ownPatient = Patient::create([
            'user_id' => $patientUser->id,
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'telefono' => '5551234',
            'email' => 'paciente@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);
        $otherPatient = Patient::create([
            'nombre' => 'Marta',
            'apellidos' => 'Otra',
            'telefono' => '5559876',
            'email' => 'marta@example.com',
            'fecha_nacimiento' => '1992-02-02',
        ]);

        $this->actingAs($patientUser)
            ->get(route('patients.show', $otherPatient))
            ->assertNotFound();

        $this->actingAs($patientUser)
            ->get(route('patients.show', $ownPatient))
            ->assertOk();
    }

    public function test_administrator_can_update_patient_username_and_password(): void
    {
        Role::create(['id' => 1, 'name' => 'Administrador', 'description' => 'Administración']);
        Role::create(['id' => 3, 'name' => 'Paciente', 'description' => 'Paciente']);

        $administrator = User::create([
            'username' => 'admin',
            'nombre' => 'Admin',
            'apellidos' => 'Dentissa',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
        ]);
        $patientUser = User::create([
            'username' => 'paciente',
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'email' => 'paciente@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
        ]);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'telefono' => '5551234',
            'email' => 'paciente@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $this->actingAs($administrator)
            ->post(route('patients.credentials.update', $patient), [
                'username' => 'luis_paciente',
                'password' => 'nueva1234',
                'password_confirmation' => 'nueva1234',
            ])
            ->assertRedirect(route('patients.show', $patient));

        $this->assertSame('luis_paciente', $patientUser->fresh()->username);
        $this->assertTrue(Hash::check('nueva1234', $patientUser->fresh()->password));
        $this->assertSessionHas('temp_password', 'nueva1234');
    }
}