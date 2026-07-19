<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Nota: los hashes de password se conservan tal cual del dump original
        // (bcrypt de Laravel). Si quieres contraseñas conocidas para pruebas
        // locales, reemplázalos por Hash::make('password').
        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'username' => 'dra_melissa',
                'nombre' => 'Melissa',
                'apellidos' => 'López N.',
                'email' => 'dra.melissa@dentissa.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'remember_token' => 'xSenhD0LG9vTxu5jo9yyuKsDxWhqfvnLyxgESHWXzSG4DI9iZlsZr6HH4zjF',
                'created_at' => '2026-07-17 02:02:28',
                'updated_at' => '2026-07-17 02:02:28',
                'activo' => 1,
            ],
            [
                'id' => 2,
                'username' => 'asistente',
                'nombre' => 'Ana',
                'apellidos' => 'Gómez Pérez',
                'email' => 'asistente@dentissa.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'remember_token' => 'ljshLfSbLhFuN7FOUQu11v0lj2yjF3qMP1rjTsEO761T0oOrOUeibzlVNLqw',
                'created_at' => '2026-07-17 02:02:29',
                'updated_at' => '2026-07-17 03:25:05',
                'activo' => 1,
            ],
            [
                'id' => 3,
                'username' => 'dr_carlos',
                'nombre' => 'Carlos',
                'apellidos' => 'Mendoza Torres',
                'email' => 'dr.carlos@dentissa.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'remember_token' => '5UQuPMovSiVMKRe4rAGOjXiCvtYFTFIWpEew1t6OgGtmQ8DkSVl8SCGzCmJI',
                'created_at' => '2026-07-17 02:02:29',
                'updated_at' => '2026-07-17 02:03:40',
                'activo' => 1,
            ],
            [
                'id' => 4,
                'username' => 'pepe1',
                'nombre' => 'pipilin',
                'apellidos' => 'sou joto',
                'email' => 'pepe@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'remember_token' => null,
                'created_at' => '2026-07-17 02:18:55',
                'updated_at' => '2026-07-17 02:19:27',
                'activo' => 0,
            ],
        ]);
    }
}
