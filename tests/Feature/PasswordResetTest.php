<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_link_can_be_requested_for_existing_user(): void
    {
        Notification::fake();

        $role = Role::create([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Acceso administrativo',
        ]);

        $user = User::create([
            'username' => 'tester',
            'nombre' => 'Test',
            'apellidos' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            return $notification->toMail($user) instanceof \Illuminate\Notifications\Messages\MailMessage;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $role = Role::create([
            'id' => 2,
            'name' => 'Asistente',
            'description' => 'Acceso de asistencia',
        ]);

        $user = User::create([
            'username' => 'resetter',
            'nombre' => 'Reset',
            'apellidos' => 'User',
            'email' => 'reset@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'activo' => true,
        ]);

        $token = null;
        Notification::fake();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'nuevaPassword123',
            'password_confirmation' => 'nuevaPassword123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('nuevaPassword123', $user->fresh()->password));
    }
}
