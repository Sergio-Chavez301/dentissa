<?php

namespace Tests\Feature;

use App\Models\Role;
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
}
