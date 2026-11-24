<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\OfficeSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_log_in_with_username_or_email(): void
    {
        Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);
        $user = User::create([
            'username' => 'admin_test',
            'nombre' => 'Admin',
            'apellidos' => 'Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'activo' => true,
        ]);

        foreach (['admin_test', 'admin@example.com'] as $identifier) {
            $this->post('/login', [
                'username' => $identifier,
                'password' => 'password123',
            ])->assertRedirect(route('dashboard.admin'));

            $this->assertAuthenticatedAs($user);
            auth()->logout();
        }
    }

    public function test_login_still_rejects_an_incorrect_password(): void
    {
        Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);
        User::create([
            'username' => 'admin_test',
            'nombre' => 'Admin',
            'apellidos' => 'Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'activo' => true,
        ]);

        $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'incorrecta',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_login_offers_whatsapp_contact_for_password_help(): void
    {
        OfficeSetting::current()->update([
            'whatsapp_phone' => '+52 55 1234 5678',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('https://wa.me/525512345678?text=')
            ->assertSee('¿Olvidaste tu contraseña? Contacta a la doctora por WhatsApp');
    }

    public function test_forgot_password_page_directs_users_to_the_clinic_whatsapp(): void
    {
        OfficeSetting::current()->update([
            'whatsapp_phone' => '+52 55 1234 5678',
        ]);

        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('https://wa.me/525512345678?text=')
            ->assertSee('Contactar por WhatsApp')
            ->assertDontSee('Enviar enlace');
    }

    public function test_mobile_mode_does_not_open_the_public_website(): void
    {
        $this->get('/login?mobile=1')
            ->assertOk()
            ->assertDontSee('href="/"');

        $this->get('/')
            ->assertRedirect(route('login', ['mobile' => 1]));
    }

    public function test_mobile_mode_redirects_an_authenticated_user_to_their_role_dashboard(): void
    {
        Role::create([
            'id' => 2,
            'name' => 'Asistente',
            'description' => 'Acceso de asistente',
        ]);
        $user = User::create([
            'username' => 'assistant_test',
            'nombre' => 'Asistente',
            'apellidos' => 'Test',
            'email' => 'assistant@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
            'activo' => true,
        ]);

        $this->actingAs($user)
            ->withSession(['mobile_app_mode' => true])
            ->get('/')
            ->assertRedirect(route('dashboard.asistente'));
    }
}
