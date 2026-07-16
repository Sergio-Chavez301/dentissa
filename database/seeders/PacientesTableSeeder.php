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
                'nombre' => 'Sergio',
                'apellidos' => 'Chávez Guardado',
                'telefono' => '5551234567',
                'fecha_nacimiento' => '1998-05-15',
                'edad' => 28,
                'alergias' => 'Penicilina',
                'enfermedades' => 'Ninguna',
                'tratamientos' => 'Resina en molar inferior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Beatriz',
                'apellidos' => 'Domínguez Ruiz',
                'telefono' => '5559876543',
                'fecha_nacimiento' => '1995-10-22',
                'edad' => 30,
                'alergias' => 'Ninguna',
                'enfermedades' => 'Hipertensión controlada',
                'tratamientos' => 'Limpieza ultrasónica semestral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carlos',
                'apellidos' => 'Mendoza Torres',
                'telefono' => '5554443322',
                'fecha_nacimiento' => '1990-01-30',
                'edad' => 36,
                'alergias' => 'Aspirina',
                'enfermedades' => 'Diabetes Tipo 2',
                'tratamientos' => 'Tratamiento de ortodoncia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Mariana',
                'apellidos' => 'Fuentes Ortiz',
                'telefono' => '7421112233',
                'fecha_nacimiento' => '2001-08-14',
                'edad' => 24,
                'alergias' => 'Látex',
                'enfermedades' => 'Ninguna',
                'tratamientos' => 'Endodoncia preventiva',
                'created_at' => now()->subDays(5),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Alejandro',
                'apellidos' => 'Ruiz Peña',
                'telefono' => '7425556677',
                'fecha_nacimiento' => '1987-12-05',
                'edad' => 38,
                'alergias' => 'Ninguna',
                'enfermedades' => 'Asma leve',
                'tratamientos' => 'Blanqueamiento dental y guardas nocturnas',
                'created_at' => now()->subDays(12),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Diana',
                'apellidos' => 'Salgado Leyva',
                'telefono' => '7428889900',
                'fecha_nacimiento' => '2004-03-25',
                'edad' => 22,
                'alergias' => 'Sulfa',
                'enfermedades' => 'Ninguna',
                'tratamientos' => 'Extracción de terceros molares',
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Roberto',
                'apellidos' => 'Gómez Valenzo',
                'telefono' => '7424445566',
                'fecha_nacimiento' => '1975-06-18',
                'edad' => 51,
                'alergias' => 'Clindamicina',
                'enfermedades' => 'Hipotiroidismo',
                'tratamientos' => 'Corona de circonio e implante',
                'created_at' => now()->subDays(20),
                'updated_at' => now(),
            ],
        ]);
    }
}