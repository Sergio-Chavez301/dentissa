<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'nombre' => 'Admin',
                'apellidos' => 'Sistema',
                'email' => 'admin@dentissa.com',
                'role' => 'Administrador',
            ],
            [
                'username' => 'asistente',
                'nombre' => 'Ana',
                'apellidos' => 'Gómez',
                'email' => 'asistente@dentissa.com',
                'role' => 'Asistente',
            ],
            [
                'username' => 'paciente',
                'nombre' => 'Sergio',
                'apellidos' => 'Chávez',
                'email' => 'paciente@dentissa.com',
                'role' => 'Paciente',
            ],
        ];

        foreach ($users as $user) {
            $roleId = DB::table('roles')->where('name', $user['role'])->value('id');

            DB::table('usuarios')->updateOrInsert(
                ['username' => $user['username']],
                [
                    'nombre' => $user['nombre'],
                    'apellidos' => $user['apellidos'],
                    'email' => $user['email'],
                    'password' => Hash::make('12345'),
                    'role_id' => $roleId,
                    'remember_token' => null,
                    'activo' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
