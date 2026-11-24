<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_and_update_their_profile(): void
    {
        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);

        $user = User::create([
            'username' => 'perfiltest',
            'nombre' => 'Ana',
            'apellidos' => 'Pérez',
            'email' => 'perfil@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get('/profile');
        $response->assertStatus(200);

        $response = $this->post('/profile', [
            'nombre' => 'Ana María',
            'apellidos' => 'Pérez López',
            'email' => 'nuevo@example.com',
            'username' => 'perfiltest',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('profile.show'));
        $user->refresh();
        $this->assertSame('Ana María', $user->nombre);
        $this->assertSame('nuevo@example.com', $user->email);
    }

    public function test_patient_can_update_profile_data_but_not_their_username(): void
    {
        $role = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Acceso de paciente',
        ]);
        $user = User::create([
            'username' => 'paciente',
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'email' => 'paciente@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
        $patient = Patient::create([
            'user_id' => $user->id,
            'nombre' => 'Luis',
            'apellidos' => 'Paciente',
            'telefono' => '5551234567',
            'email' => 'paciente@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('readonly')
            ->assertSee('El nombre de usuario no se puede cambiar');

        $this->actingAs($user)
            ->post('/profile', [
                'nombre' => 'Nombre modificado',
                'apellidos' => 'Paciente',
                'username' => 'usuario-modificado',
                'email' => 'actualizado@example.com',
                'telefono' => '5557654321',
            ])
            ->assertRedirect(route('profile.show'));

        $user->refresh();
        $patient->refresh();
        $this->assertSame('Nombre modificado', $user->nombre);
        $this->assertSame('paciente', $user->username);
        $this->assertSame('actualizado@example.com', $user->email);
        $this->assertSame('Nombre modificado', $patient->nombre);
        $this->assertSame('actualizado@example.com', $patient->email);
        $this->assertSame('5557654321', $patient->telefono);
    }
}
