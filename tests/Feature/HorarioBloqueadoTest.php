<?php

namespace Tests\Feature;

use App\Models\HorarioBloqueado;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HorarioBloqueadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_block_and_release_a_slot(): void
    {
        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso total al sistema',
        ]);
        $admin = User::create([
            'username' => 'admin',
            'nombre' => 'Sergio',
            'apellidos' => 'Demo',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
        $this->actingAs($admin);
        $fecha = now()->addDays(7)->toDateString();

        $this->post(route('horarios-bloqueados.store'), [
            'fecha' => $fecha,
            'hora' => '09:00:00',
            'motivo' => 'Mantenimiento',
        ])->assertRedirect(route('horarios-bloqueados.index'));

        $bloqueo = HorarioBloqueado::firstOrFail();
        $this->getJson(route('api.disponibilidad', ['fecha' => $fecha]))
            ->assertOk()
            ->assertJsonMissing(['09:00:00']);

        $this->delete(route('horarios-bloqueados.destroy', $bloqueo))
            ->assertRedirect(route('horarios-bloqueados.index'));

        $response = $this->getJson(route('api.disponibilidad', ['fecha' => $fecha]))->assertOk();
        $this->assertContains('09:00:00', $response->json('horarios'));
    }
}