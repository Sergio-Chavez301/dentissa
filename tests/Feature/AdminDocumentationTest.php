<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_open_documentation_hub_and_interactive_diagrams(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::create([
            'username' => 'admin_docs',
            'nombre' => 'Admin',
            'apellidos' => 'Dentissa',
            'email' => 'admin-docs@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'activo' => true,
        ]);

        $hub = $this->actingAs($admin)->get(route('admin.documentation.index'));
        $hub->assertOk();
        $hub->assertSee('Documentación de Dentissa');
        $hub->assertSee('Mapa de arquitectura');
        $hub->assertSee(route('admin.documentation.show', ['document' => 'diagrama-base-datos.html']));

        $diagram = $this->get(route('admin.documentation.show', ['document' => 'diagrama-base-datos.html']));
        $diagram->assertOk();
        $diagram->assertSee('Diagrama de base de datos');
        $diagram->assertSee(route('admin.documentation.index'), false);
        $diagram->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public function test_non_administrator_cannot_open_admin_documentation(): void
    {
        $this->seed(RoleSeeder::class);

        $assistant = User::create([
            'username' => 'assistant_docs',
            'nombre' => 'Asistente',
            'apellidos' => 'Dentissa',
            'email' => 'assistant-docs@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'activo' => true,
        ]);

        $this->actingAs($assistant)
            ->get(route('admin.documentation.index'))
            ->assertForbidden();

        $this->get(route('admin.documentation.show', ['document' => 'flujo-sistema.html']))
            ->assertForbidden();
    }

    public function test_document_route_only_serves_allowlisted_files(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::create([
            'username' => 'admin_docs_allowlist',
            'nombre' => 'Admin',
            'apellidos' => 'Dentissa',
            'email' => 'admin-docs-allowlist@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'activo' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/documentacion/../../.env')
            ->assertNotFound();
    }
}
