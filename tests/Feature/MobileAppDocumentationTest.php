<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MobileAppDocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_view_the_mobile_app_guide_from_the_dashboard(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::create([
            'username' => 'admin_mobile_guide',
            'nombre' => 'Admin',
            'apellidos' => 'Dentissa',
            'email' => 'admin-mobile-guide@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'activo' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.mobile-app-guide'));

        $response->assertOk();
        $response->assertSee('Guía de la app móvil');
        $response->assertSee('Presentación para la doctora');
        $response->assertSee('/img/diagramas/arquitectura-dentissa.svg');
        $response->assertSee('no registra el dispositivo Android');
        $response->assertDontSee('FIREBASE_SERVICE_ACCOUNT_JSON');
    }

    public function test_non_administrator_cannot_view_the_mobile_app_guide(): void
    {
        $this->seed(RoleSeeder::class);

        $assistant = User::create([
            'username' => 'assistant_mobile_guide',
            'nombre' => 'Asistente',
            'apellidos' => 'Dentissa',
            'email' => 'assistant-mobile-guide@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'activo' => true,
        ]);

        $this->actingAs($assistant)
            ->get(route('admin.mobile-app-guide'))
            ->assertForbidden();
    }
}
