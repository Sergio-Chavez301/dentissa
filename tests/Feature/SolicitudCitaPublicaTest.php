<?php

namespace Tests\Feature;

use App\Models\SolicitudCita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolicitudCitaPublicaTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_pending_request_reserves_its_proposed_slot(): void
    {
        $date = now()->addDays(7)->toDateString();

        SolicitudCita::create([
            'nombre' => 'Ana',
            'apellidos' => 'Rojas',
            'telefono' => '+525512345678',
            'email' => 'ana@example.com',
            'fecha_hora_propuesta' => $date . ' 09:00:00',
            'fecha_nacimiento' => '1990-01-01',
            'estado' => SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION,
        ]);

        $this->getJson(route('api.disponibilidad', ['fecha' => $date]))
            ->assertOk()
            ->assertJsonMissing(['09:00:00']);

        $this->from(route('home'))->post(route('solicitud.storePublic'), [
            'nombre' => 'Luis',
            'apellidos' => 'Pérez',
            'telefono' => '+525576543210',
            'email' => 'luis@example.com',
            'fecha_propuesta' => $date,
            'hora_propuesta' => '09:00',
            'fecha_nacimiento' => '1992-02-02',
        ])->assertRedirect(route('home'))
            ->assertSessionHasErrors('hora_propuesta');

        $this->assertDatabaseCount('solicitudes_citas', 1);
    }
}