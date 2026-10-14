<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'nombre' => 'Lucía',
                'apellidos' => 'Hernández Ruiz',
                'telefono' => '5551002001',
                'email' => 'lucia.hernandez@example.com',
                'fecha_nacimiento' => '1992-04-18',
                'alergias' => null,
                'enfermedades' => null,
                'tratamientos' => null,
            ],
            [
                'nombre' => 'Mateo',
                'apellidos' => 'García López',
                'telefono' => '5551002002',
                'email' => 'mateo.garcia@example.com',
                'fecha_nacimiento' => '1985-09-07',
                'alergias' => 'Penicilina',
                'enfermedades' => null,
                'tratamientos' => null,
            ],
            [
                'nombre' => 'Valentina',
                'apellidos' => 'Martínez Cruz',
                'telefono' => '5551002003',
                'email' => 'valentina.martinez@example.com',
                'fecha_nacimiento' => '2001-01-22',
                'alergias' => null,
                'enfermedades' => 'Asma',
                'tratamientos' => null,
            ],
            [
                'nombre' => 'Diego',
                'apellidos' => 'Sánchez Torres',
                'telefono' => '5551002004',
                'email' => 'diego.sanchez@example.com',
                'fecha_nacimiento' => '1978-12-03',
                'alergias' => null,
                'enfermedades' => null,
                'tratamientos' => 'Uso de retenedor dental',
            ],
            [
                'nombre' => 'Camila',
                'apellidos' => 'Ramírez Flores',
                'telefono' => '5551002005',
                'email' => 'camila.ramirez@example.com',
                'fecha_nacimiento' => '1996-06-14',
                'alergias' => null,
                'enfermedades' => null,
                'tratamientos' => null,
            ],
        ];

        foreach ($patients as $patient) {
            Patient::updateOrCreate(
                ['email' => $patient['email']],
                $patient
            );
        }
    }
}