<?php

namespace Tests\Feature;

use App\Models\OfficeSetting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OfficeSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_update_office_location_and_whatsapp(): void
    {
        Role::create(['id' => 1, 'name' => 'Administrador', 'description' => 'Administración']);
        $administrator = User::create([
            'username' => 'admin',
            'nombre' => 'Admin',
            'apellidos' => 'Dentissa',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
        ]);

        $this->actingAs($administrator)
            ->put(route('admin.office-settings.update'), [
                'clinic_name' => 'Dentissa Centro',
                'clinic_address' => 'Av. Central 123, Ciudad',
                'map_url' => 'https://maps.google.com/?q=Dentissa',
                'whatsapp_phone' => '+52 55 1234 5678',
            ])
            ->assertRedirect(route('admin.office-settings.edit'));

        $this->assertSame('Dentissa Centro', OfficeSetting::current()->clinic_name);
        $this->assertSame('525512345678', OfficeSetting::current()->whatsapp_digits);
    }

    public function test_assistant_cannot_edit_office_location_or_whatsapp(): void
    {
        Role::create(['id' => 2, 'name' => 'Asistente', 'description' => 'Asistencia']);
        $assistant = User::create([
            'username' => 'asistente',
            'nombre' => 'Ana',
            'apellidos' => 'Asistente',
            'email' => 'asistente@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
        ]);

        $this->actingAs($assistant)
            ->get(route('admin.office-settings.edit'))
            ->assertForbidden();

        $this->actingAs($assistant)
            ->put(route('admin.office-settings.update'), [
                'clinic_name' => 'No autorizado',
                'clinic_address' => 'Dirección',
                'whatsapp_phone' => '+52 55 1234 5678',
            ])
            ->assertForbidden();
    }
}