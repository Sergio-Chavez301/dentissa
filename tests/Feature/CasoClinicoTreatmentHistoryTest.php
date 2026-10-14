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

class CasoClinicoTreatmentHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_case_detail_shows_appointments_linked_to_its_treatment(): void
    {
        [$assistant, $patient, $case, $service] = $this->createTreatmentData();
        $appointmentDate = Carbon::today()->addDays(6);

        Cita::create([
            'paciente_id' => $patient->id,
            'caso_clinico_id' => $case->id,
            'servicio_id' => $service->id,
            'fecha' => $appointmentDate->toDateString(),
            'hora' => '10:00:00',
            'estado' => Cita::ESTADO_REALIZADA,
        ]);

        $response = $this->actingAs($assistant)->get(route('casos.show', $case));

        $response->assertOk();
        $response->assertSee('Citas del tratamiento');
        $response->assertSee('Ajuste de ortodoncia');
        $response->assertSee($appointmentDate->format('d/m/Y'));
        $response->assertSee('Realizada');
        $response->assertSee('caso_clinico_id='.$case->id);
    }

    public function test_appointment_cannot_be_linked_to_another_patients_case(): void
    {
        [$assistant, $patient, $case, $service] = $this->createTreatmentData();
        $otherPatient = Patient::create([
            'nombre' => 'Elena',
            'apellidos' => 'López',
            'telefono' => '5552002',
            'email' => 'elena@example.com',
            'fecha_nacimiento' => '1993-05-12',
        ]);
        $otherCase = CasoClinico::create([
            'paciente_id' => $otherPatient->id,
            'tratamiento_base' => 'Blanqueamiento',
            'progreso' => 0,
            'estado' => CasoClinico::ESTADO_ACTIVO,
        ]);

        $response = $this->actingAs($assistant)->post(route('citas.store'), [
            'paciente_id' => $patient->id,
            'caso_clinico_id' => $otherCase->id,
            'servicio_id' => $service->id,
            'fecha' => Carbon::today()->addDays(2)->toDateString(),
            'hora' => '09:00:00',
        ]);

        $response->assertSessionHasErrors('caso_clinico_id');
        $this->assertDatabaseMissing('citas', [
            'paciente_id' => $patient->id,
            'caso_clinico_id' => $otherCase->id,
        ]);
    }

    private function createTreatmentData(): array
    {
        $role = Role::create([
            'name' => 'Asistente',
            'description' => 'Asistencia clínica',
        ]);

        $assistant = User::create([
            'username' => 'asistente-tratamientos',
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'email' => 'asistente-tratamientos@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $patient = Patient::create([
            'nombre' => 'Lucía',
            'apellidos' => 'Hernández',
            'telefono' => '5552001',
            'email' => 'lucia-tratamientos@example.com',
            'fecha_nacimiento' => '1992-04-18',
        ]);

        $case = CasoClinico::create([
            'paciente_id' => $patient->id,
            'tratamiento_base' => 'Ortodoncia',
            'progreso' => 25,
            'estado' => CasoClinico::ESTADO_ACTIVO,
        ]);

        $service = Servicio::create([
            'nombre' => 'Ajuste de ortodoncia',
            'descripcion' => 'Control y ajuste mensual',
            'precio' => 800,
        ]);

        return [$assistant, $patient, $case, $service];
    }
}