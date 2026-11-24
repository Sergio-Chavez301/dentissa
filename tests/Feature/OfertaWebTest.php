<?php

namespace Tests\Feature;

use App\Models\Oferta;
use App\Models\OfficeSetting;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
        OfficeSetting::query()->whereKey(1)->update([
            'clinic_name' => 'Consultorio Dentissa',
            'whatsapp_phone' => '+52 55 1234 5678',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Limpieza + valoración');
        $response->assertSee('Solicitar más información');
        $response->assertSee('https://wa.me/525512345678?text=', false);
        $informationMessage = rawurlencode('Hola, quisiera solicitar información sobre los servicios de Consultorio Dentissa.');
        $response->assertSee('https://wa.me/525512345678?text=' . $informationMessage, false);
        $response->assertSee('aria-label="Solicitar información por WhatsApp"', false);
        $response->assertSee('<span>Solicitar información</span>', false);
        $response->assertSee('position: fixed', false);
        $response->assertDontSee('data-whatsapp-stop', false);
        $response->assertDontSee('updateWhatsappPosition', false);
        $response->assertSee('Se abrirá WhatsApp para solicitar más información sobre esta oferta. ¿Deseas continuar?');
    }

    public function test_home_page_hides_whatsapp_bubble_when_clinic_number_is_not_configured(): void
    {
        OfficeSetting::query()->whereKey(1)->update(['whatsapp_phone' => null]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('class="whatsapp-bubble"', false);
    }

    public function test_offer_page_is_public_and_links_each_offer_to_whatsapp(): void
    {
        $role = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Paciente del sistema',
        ]);
        $patient = User::create([
            'username' => 'patient_offers',
            'nombre' => 'Paciente',
            'apellidos' => 'Dentissa',
            'email' => 'patient-offers@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'activo' => true,
        ]);

        OfficeSetting::query()->whereKey(1)->update([
            'clinic_name' => 'Consultorio Dentissa',
            'whatsapp_phone' => '+52 55 1234 5678',
        ]);
        Oferta::create([
            'titulo' => 'Limpieza dental',
            'descripcion' => 'Limpieza y valoración.',
            'precio' => 1299,
            'tag' => 'Promoción',
            'orden' => 1,
            'activo' => true,
        ]);

        $response = $this->get(route('ofertas.public'));
        $whatsappMessage = rawurlencode(
            'Hola, quisiera información sobre la oferta "Limpieza dental" de Consultorio Dentissa. ¿Podrían compartirme los detalles y su disponibilidad?'
        );

        $response->assertOk()
            ->assertSee('Ofertas y promociones')
            ->assertSee('Solicitar más información')
            ->assertSee('Se abrirá WhatsApp para solicitar más información sobre esta oferta. ¿Deseas continuar?')
            ->assertSee('https://wa.me/525512345678?text=' . $whatsappMessage, false)
            ->assertSee(route('home'))
            ->assertSee('Volver al sitio')
            ->assertDontSee('Volver al dashboard');
    }

    public function test_patient_dashboard_offer_buttons_open_whatsapp_with_the_offer_name(): void
    {
        $role = Role::create([
            'id' => 3,
            'name' => 'Paciente',
            'description' => 'Paciente del sistema',
        ]);
        $patientUser = User::create([
            'username' => 'patient_dashboard_offers',
            'nombre' => 'Paciente',
            'apellidos' => 'Dentissa',
            'email' => 'patient-dashboard-offers@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'activo' => true,
        ]);
        Patient::create([
            'user_id' => $patientUser->id,
            'nombre' => 'Paciente',
            'apellidos' => 'Dentissa',
            'telefono' => '5551234567',
            'email' => 'patient-dashboard-offers@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ]);
        OfficeSetting::query()->whereKey(1)->update([
            'clinic_name' => 'Consultorio Dentissa',
            'whatsapp_phone' => '+52 55 1234 5678',
        ]);
        Oferta::create([
            'titulo' => 'Limpieza dental',
            'descripcion' => 'Limpieza y valoración.',
            'precio' => 1299,
            'tag' => 'Promoción',
            'orden' => 1,
            'activo' => true,
        ]);

        $response = $this->actingAs($patientUser)->get(route('dashboard.paciente'));

        $response->assertOk()
            ->assertSee('Ofertas activas')
            ->assertSee('Solicitar más información')
            ->assertSee('https://wa.me/525512345678?text=' . rawurlencode(
                'Hola, quisiera información sobre la oferta "Limpieza dental" de Consultorio Dentissa. ¿Podrían compartirme los detalles y su disponibilidad?'
            ), false)
            ->assertSee('Se abrirá WhatsApp para solicitar más información sobre esta oferta. ¿Deseas continuar?');

        $this->actingAs($patientUser)
            ->get(route('ofertas.public'))
            ->assertOk()
            ->assertSee('Volver al dashboard')
            ->assertSee(route('dashboard.paciente'))
            ->assertSee('Solicitar más información')
            ->assertDontSee('Volver al sitio');
    }
}
