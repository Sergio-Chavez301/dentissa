<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pacientes')->insert([
            [
                'id' => 1,
                'nombre' => 'Sergio',
                'apellidos' => 'Chávez Guardado',
                'telefono' => '1234567890',
                'fecha_nacimiento' => '1998-05-15',
                'edad' => 28,
                'alergias' => 'Ninguna',
                'enfermedades' => 'Ninguna',
                'tratamientos' => null,
                'created_at' => now()->subSeconds(42), // Para que aparezca "hace 42 segundos" en el panel
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Mariana',
                'apellidos' => 'Fuentes Ortiz',
                'telefono' => '0987654321',
                'fecha_nacimiento' => '2000-09-20',
                'edad' => 25,
                'alergias' => 'Penicilina',
                'enfermedades' => 'Ninguna',
                'tratamientos' => null,
                'created_at' => now()->subSeconds(42),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Alejandro',
                'apellidos' => 'Ruiz Peña',
                'telefono' => '5551234567',
                'fecha_nacimiento' => '1995-02-10',
                'edad' => 31,
                'alergias' => 'Ninguna',
                'enfermedades' => 'Asma',
                'tratamientos' => 'Inhalador',
                'created_at' => now()->subSeconds(42),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Beatriz',
                'apellidos' => 'Mendoza Torres',
                'telefono' => '5557654321',
                'fecha_nacimiento' => '1992-11-30',
                'edad' => 33,
                'alergias' => 'Polvo',
                'enfermedades' => 'Hipertensión',
                'tratamientos' => 'Control médico diario',
                'created_at' => now()->subSeconds(42),
                'updated_at' => now(),
            ],
        ]);
    }
}