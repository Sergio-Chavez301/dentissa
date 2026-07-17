<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasosClinicosTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('casos_clinicos')->insert([
            [
                'paciente_id' => 2, // Mariana Fuentes
                'tratamiento_base' => 'Carillas de Porcelana',
                'progreso' => 45,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => 3, // Alejandro Ruiz
                'tratamiento_base' => 'Tratamiento de Conducto',
                'progreso' => 20,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => 4, // Beatriz Mendoza (Extracción)
                'tratamiento_base' => 'Extracción de Cordales',
                'progreso' => 90,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => 4, // Beatriz Mendoza (Blanqueamiento)
                'tratamiento_base' => 'Blanqueamiento Dental LED',
                'progreso' => 50,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => 1, // Sergio Chávez
                'tratamiento_base' => 'Ortodoncia Correctiva',
                'progreso' => 75,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}