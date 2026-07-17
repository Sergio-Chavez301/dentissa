<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'username' => 'dra_melissa',
                'nombre' => 'Melissa',
                'apellidos' => 'López N.',
                'email' => 'dra.melissa@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 1,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'username' => 'asistente',
                'nombre' => 'Ana',
                'apellidos' => 'Gómez Pérez',
                'email' => 'asistente@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 2,
                'activo' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'username' => 'dr_carlos',
                'nombre' => 'Carlos',
                'apellidos' => 'Mendoza Torres',
                'email' => 'dr.carlos@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 3,
                'activo' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}