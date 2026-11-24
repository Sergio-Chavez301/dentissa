<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_personnel_registration_does_not_offer_or_accept_patient_role(): void
    {
        $this->createRoles();

        $admin = User::create([
            'username' => 'admin_personal',
            'nombre' => 'Admin',
            'apellidos' => 'Test',
            'email' => 'admin-personal@test.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'activo' => 1,
        ]);

        $this->actingAs($admin)
            ->get('/users/create')
            ->assertOk()
            ->assertDontSee('value="3"', false);

        $this->actingAs($admin)->post('/users', [
            'nombre' => 'Paciente',
            'apellidos' => 'De Prueba',
            'username' => 'paciente_de_prueba',
            'email' => 'paciente-de-prueba@test.com',
            'role_id' => 3,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('role_id');

        $this->assertDatabaseMissing('usuarios', [
            'username' => 'paciente_de_prueba',
        ]);
    }

    public function test_role_id_must_be_among_supported_roles(): void
    {
        $this->createRoles();

        $admin = User::create([
            'username' => 'admin_test',
            'nombre' => 'Admin',
            'apellidos' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'activo' => 1,
        ]);

        $response = $this->actingAs($admin)->post('/users', [
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'username' => 'juan_test',
            'email' => 'juan@test.com',
            'role_id' => 4,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('role_id');
    }

    private function createRoles(): void
    {
        foreach ([
            1 => 'Administrador',
            2 => 'Asistente',
            3 => 'Paciente',
        ] as $id => $name) {
            Role::create([
                'id' => $id,
                'name' => $name,
                'description' => $name,
            ]);
        }
    }
}
