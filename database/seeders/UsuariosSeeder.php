<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            // 1. Administrador (Dra. Melissa)
            [
                'id' => 1,
                'username' => 'sergiochavez',
                'nombre' => 'Melissa',
                'apellidos' => 'López N.',
                'email' => 'dra.melissa@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 1, // Administrador
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 2. Asistente (Ana)
            [
                'id' => 2,
                'username' => 'asistente',
                'nombre' => 'Ana',
                'apellidos' => 'Gómez Pérez',
                'email' => 'asistente@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 2, // Asistente
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 3. Paciente (Sergio)
            [
                'id' => 3,
                'username' => 'paciente',
                'nombre' => 'Sergio',
                'apellidos' => 'Chávez Guardado',
                'email' => 'sergio.chavez@gmail.com',
                'password' => Hash::make('123456789'),
                'role_id' => 3, // Paciente
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 4. Paciente (Mariana)
            [
                'id' => 4,
                'username' => 'mariana_f',
                'nombre' => 'Mariana',
                'apellidos' => 'Fuentes Ortiz',
                'email' => 'mariana.fuentes@gmail.com',
                'password' => Hash::make('123456789'),
                'role_id' => 3, // Paciente
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 5. Paciente (Alejandro)
            [
                'id' => 5,
                'username' => 'alejandro_r',
                'nombre' => 'Alejandro',
                'apellidos' => 'Ruiz Peña',
                'email' => 'ale.ruiz@gmail.com',
                'password' => Hash::make('123456789'),
                'role_id' => 3, // Paciente
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 6. Paciente (Beatriz)
            [
                'id' => 6,
                'username' => 'beatriz_m',
                'nombre' => 'Beatriz',
                'apellidos' => 'Mendoza Torres',
                'email' => 'beatriz.m@gmail.com',
                'password' => Hash::make('123456789'),
                'role_id' => 3, // Paciente
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 7. Odontólogo Especialista (Dr. Carlos)
            [
                'id' => 7,
                'username' => 'dr_carlos',
                'nombre' => 'Carlos',
                'apellidos' => 'Mendoza Torres',
                'email' => 'dr.carlos@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 4, // Odontologo
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 8. Recepcionista (Diana)
            [
                'id' => 8,
                'username' => 'recepcion_diana',
                'nombre' => 'Diana',
                'apellidos' => 'Salgado Leyva',
                'email' => 'recepcion@dentissa.com',
                'password' => Hash::make('123456789'),
                'role_id' => 5, // Recepcionista
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}