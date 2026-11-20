<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Oferta;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_log_in_and_only_see_their_own_dashboard(): void
    {
        $patient = $this->createPatient('patient', 'patient@example.com');
        $otherPatient = $this->createPatient('other', 'other@example.com');
        $patient->update([
            'alergias' => 'Penicilina',
            'enfermedades' => 'Asma',
            'tratamientos' => 'Revisión semestral',
        ]);
        CasoClinico::create([
            'paciente_id' => $patient->id,
            'tratamiento_base' => 'Ortodoncia',
            'progreso' => 45,
            'estado' => 'activo',
        ]);
        Oferta::create([
            'titulo' => 'Limpieza con descuento',
            'tag' => 'Promoción',
            'descripcion' => 'Precio especial este mes.',
            'precio' => 400,
            'orden' => 1,
            'activo' => true,
        ]);
        $service = Servicio::create([
            'nombre' => 'Limpieza',
            'descripcion' => 'Limpieza dental',
            'precio' => 500,
        ]);
        Cita::create([
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => now()->addDays(2)->toDateString(),
            'hora' => '09:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);
        Cita::create([
            'paciente_id' => $otherPatient->id,
            'servicio_id' => $service->id,
            'fecha' => now()->addDays(3)->toDateString(),
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);

        $login = $this->postJson('/api/mobile/login', [
            'username' => 'patient',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('user.name', 'Ana Paciente');

        $this->withToken($login->json('token'))
            ->getJson('/api/mobile/dashboard')
            ->assertOk()
            ->assertJsonPath('patient.name', 'Ana Paciente')
            ->assertJsonPath('patient.username', 'patient')
            ->assertJsonPath('patient.birth_date', '1990-01-01')
            ->assertJsonPath('patient.allergies', 'Penicilina')
            ->assertJsonPath('appointment_counts.en_espera', 1)
            ->assertJsonCount(1, 'appointments')
            ->assertJsonPath('appointments.0.service', 'Limpieza')
            ->assertJsonPath('treatments.0.name', 'Ortodoncia')
            ->assertJsonPath('treatments.0.progress', 45)
            ->assertJsonPath('offers.0.title', 'Limpieza con descuento')
            ->assertJsonMissing(['name' => 'Otro Paciente']);
    }

    public function test_login_rejects_non_patient_disabled_and_unlinked_accounts(): void
    {
        $this->createPatient('disabled', 'disabled@example.com', active: false);
        $this->createUser('staff', 'staff@example.com', roleId: 1);
        $this->createUser('unlinked', 'unlinked@example.com');

        foreach (['disabled', 'staff', 'unlinked'] as $username) {
            $response = $this->postJson('/api/mobile/login', [
                'username' => $username,
                'password' => 'password123',
            ]);

            $response->assertStatus($username === 'unlinked' ? 403 : 401);
            $this->assertArrayNotHasKey('token', $response->json());
        }
    }

    public function test_patient_can_request_an_available_appointment_for_their_own_profile(): void
    {
        $patient = $this->createPatient('patient', 'patient@example.com');
        $service = Servicio::create([
            'nombre' => 'Consulta',
            'descripcion' => 'Consulta general',
            'precio' => 300,
        ]);
        $token = $patient->user->createToken('test')->plainTextToken;
        $date = now()->addDays(2)->toDateString();

        $this->withToken($token)
            ->postJson('/api/mobile/appointments', [
                'service_id' => $service->id,
                'date' => $date,
                'time' => '09:00',
            ])
            ->assertCreated()
            ->assertJsonPath('appointment.status', Cita::ESTADO_EN_ESPERA)
            ->assertJsonPath('appointment.service', 'Consulta');

        $this->assertDatabaseHas('citas', [
            'paciente_id' => $patient->id,
            'servicio_id' => $service->id,
            'fecha' => $date,
            'hora' => '09:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);
    }

    public function test_patient_cannot_request_an_unavailable_time_or_access_without_a_token(): void
    {
        $this->getJson('/api/mobile/dashboard')->assertUnauthorized();

        $patient = $this->createPatient('patient', 'patient@example.com');
        $service = Servicio::create([
            'nombre' => 'Consulta',
            'descripcion' => 'Consulta general',
            'precio' => 300,
        ]);
        $token = $patient->user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/mobile/appointments', [
                'service_id' => $service->id,
                'date' => now()->addDays(2)->toDateString(),
                'time' => '13:00',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('time');

        $this->assertDatabaseCount('citas', 0);
    }

    public function test_disabling_a_patient_account_revokes_access_for_its_existing_token(): void
    {
        $patient = $this->createPatient('patient', 'patient@example.com');
        $token = $patient->user->createToken('test')->plainTextToken;
        $patient->user->update(['activo' => false]);

        $this->withToken($token)
            ->getJson('/api/mobile/dashboard')
            ->assertForbidden()
            ->assertJsonPath('message', 'La cuenta no tiene acceso activo a la aplicación.');
    }

    private function createPatient(string $username, string $email, bool $active = true): Patient
    {
        $user = $this->createUser($username, $email, active: $active);

        return Patient::create([
            'user_id' => $user->id,
            'nombre' => $username === 'other' ? 'Otro' : 'Ana',
            'apellidos' => 'Paciente',
            'telefono' => '5551234567',
            'email' => $email,
            'fecha_nacimiento' => '1990-01-01',
        ]);
    }

    private function createUser(
        string $username,
        string $email,
        int $roleId = 3,
        bool $active = true,
    ): User {
        Role::firstOrCreate(
            ['id' => $roleId],
            ['name' => $roleId === 3 ? 'Paciente' : 'Administrador', 'description' => 'Rol de prueba'],
        );

        return User::create([
            'username' => $username,
            'nombre' => 'Ana',
            'apellidos' => 'Paciente',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role_id' => $roleId,
            'activo' => $active,
        ]);
    }
}
