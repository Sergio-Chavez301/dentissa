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

class CitaStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_cita_state_can_be_updated_to_realizada_or_cancelada(): void
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

        $this->put(route('citas.update', $cita->id), ['estado' => Cita::ESTADO_REALIZADA])
            ->assertRedirect(route('citas.index'));

        $this->assertSame(Cita::ESTADO_REALIZADA, $cita->fresh()->estado);

        $this->put(route('citas.update', $cita->id), ['estado' => Cita::ESTADO_CANCELADA])
            ->assertRedirect(route('citas.index'));

        $this->assertSame(Cita::ESTADO_CANCELADA, $cita->fresh()->estado);
    }
}
