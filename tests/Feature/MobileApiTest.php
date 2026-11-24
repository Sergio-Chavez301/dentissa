<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Oferta;
use App\Models\OfficeSetting;
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
        OfficeSetting::current()->update([
            'clinic_name' => 'Consultorio Dentissa',
            'whatsapp_phone' => '+52 55 1234 5678',
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
            ->assertJsonPath('offers.0.whatsapp_digits', '525512345678')
            ->assertJsonPath('offers.0.clinic_name', 'Consultorio Dentissa')
            ->assertJsonMissing(['name' => 'Otro Paciente']);
    }

    public function test_staff_can_log_in_and_view_the_staff_dashboard(): void
    {
        foreach ([1 => 'Administrador', 2 => 'Asistente'] as $roleId => $roleName) {
            $user = $this->createUser("staff{$roleId}", "staff{$roleId}@example.com", roleId: $roleId);
            $login = $this->postJson('/api/mobile/login', [
                'username' => $user->username,
                'password' => 'password123',
            ])->assertOk()
                ->assertJsonPath('user.role_id', $roleId);

            $this->withToken($login->json('token'))
                ->getJson('/api/mobile/dashboard')
                ->assertOk()
                ->assertJsonPath('role_id', $roleId)
                ->assertJsonPath('staff.role_name', $roleName);
        }
    }

    public function test_mobile_support_contact_returns_the_clinic_whatsapp_number(): void
    {
        OfficeSetting::current()->update([
            'whatsapp_phone' => '+52 55 1234 5678',
        ]);

        $this->getJson('/api/mobile/support-contact')
            ->assertOk()
            ->assertJsonPath('whatsapp_digits', '525512345678');
    }

    public function test_mobile_login_accepts_email_as_the_account_identifier(): void
    {
        $user = $this->createUser('staff', 'staff@example.com', roleId: 1);

        $this->postJson('/api/mobile/login', [
            'username' => $user->email,
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('user.name', 'Ana Paciente')
            ->assertJsonPath('user.role_id', 1);
    }

    public function test_login_rejects_disabled_and_unlinked_patient_accounts(): void
    {
        $this->createPatient('disabled', 'disabled@example.com', active: false);
        $this->createUser('unlinked', 'unlinked@example.com');

        foreach (['disabled', 'unlinked'] as $username) {
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
            'origen' => Cita::ORIGEN_APP,
        ]);
    }

    public function test_patient_can_request_an_appointment_for_an_active_treatment(): void
    {
        $patient = $this->createPatient('patient', 'patient@example.com');
        $treatment = CasoClinico::create([
            'paciente_id' => $patient->id,
            'tratamiento_base' => 'Ortodoncia',
            'progreso' => 25,
            'estado' => CasoClinico::ESTADO_ACTIVO,
        ]);
        $token = $patient->user->createToken('test')->plainTextToken;
        $date = now()->addDays(2)->toDateString();

        $this->withToken($token)
            ->postJson('/api/mobile/treatment-appointments', [
                'treatment_id' => $treatment->id,
                'date' => $date,
                'time' => '09:00',
            ])
            ->assertCreated()
            ->assertJsonPath('appointment.status', Cita::ESTADO_EN_ESPERA)
            ->assertJsonPath('appointment.service', 'Ortodoncia');

        $this->assertDatabaseHas('citas', [
            'paciente_id' => $patient->id,
            'caso_clinico_id' => $treatment->id,
            'fecha' => $date,
            'hora' => '09:00:00',
            'estado' => Cita::ESTADO_EN_ESPERA,
            'origen' => Cita::ORIGEN_APP,
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

    public function test_patient_can_register_and_remove_their_push_device(): void
    {
        $patient = $this->createPatient('patient', 'patient@example.com');
        $token = $patient->user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/mobile/devices', [
                'token' => 'fcm-device-token',
                'platform' => 'android',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('push_devices', [
            'user_id' => $patient->user_id,
            'token' => 'fcm-device-token',
            'platform' => 'android',
        ]);

        $this->withToken($token)
            ->deleteJson('/api/mobile/devices', [
                'token' => 'fcm-device-token',
            ])
            ->assertOk();

        $this->assertDatabaseMissing('push_devices', [
            'token' => 'fcm-device-token',
        ]);
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
