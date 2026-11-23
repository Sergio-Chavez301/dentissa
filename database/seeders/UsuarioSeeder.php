<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            1 => ['name' => 'Administrador', 'description' => 'Acceso total al sistema'],
            2 => ['name' => 'Asistente', 'description' => 'Asistencia en tareas clínicas'],
            3 => ['name' => 'Paciente', 'description' => 'Acceso para pacientes'],
        ];

        foreach ($roles as $id => $role) {
            DB::table('roles')->updateOrInsert(['id' => $id], [
                ...$role,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        $users = [
            [
                'username' => 'admin',
                'nombre' => 'Admin',
                'apellidos' => 'Sistema',
                'email' => 'admin@dentissa.com',
                'role_id' => 1,
            ],
            [
                'username' => 'asistente',
                'nombre' => 'Ana',
                'apellidos' => 'Gómez',
                'email' => 'asistente@dentissa.com',
                'role_id' => 2,
            ],
            [
                'username' => 'paciente',
                'nombre' => 'Sergio',
                'apellidos' => 'Chávez',
                'email' => 'paciente@dentissa.com',
                'role_id' => 3,
            ],
        ];

        foreach ($users as $user) {
            DB::table('usuarios')->updateOrInsert(
                ['username' => $user['username']],
                [
                    'nombre' => $user['nombre'],
                    'apellidos' => $user['apellidos'],
                    'email' => $user['email'],
                    'password' => Hash::make('12345678'),
                    'role_id' => $user['role_id'],
                    'remember_token' => null,
                    'activo' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            if ($user['role_id'] === 3) {
                $userId = DB::table('usuarios')->where('username', $user['username'])->value('id');
                DB::table('pacientes')->updateOrInsert(
                    ['email' => $user['email']],
                    [
                        'user_id' => $userId,
                        'nombre' => $user['nombre'],
                        'apellidos' => $user['apellidos'],
                        'telefono' => '5551000001',
                        'fecha_nacimiento' => '1990-01-01',
                        'alergias' => null,
                        'enfermedades' => null,
                        'tratamientos' => null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
