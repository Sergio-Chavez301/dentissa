<?php

namespace Tests\Feature;

use App\Models\Oferta;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfertaWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_active_offers_from_database(): void
    {
        Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);

        Oferta::create([
            'titulo' => 'Limpieza + valoración',
            'descripcion' => 'Incluye limpieza y valoración.',
            'precio' => 1299,
            'tag' => 'Oferta del mes',
            'orden' => 1,
            'activo' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Limpieza + valoración');
    }
}
