<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_id_must_be_among_supported_roles(): void
    {
        $this->seed(RoleSeeder::class);

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
}
