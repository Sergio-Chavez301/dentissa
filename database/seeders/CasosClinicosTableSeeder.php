<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasosClinicosTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('casos_clinicos')->insert([
            // Casos para Pacientes (usando IDs 3, 4, 5 y 6 de tu base de datos)
            [
                'user_id' => 4, 
                'tratamiento_base' => 'Limpieza Dental Profunda',
                'progreso' => 100,
                'estado' => 'finalizado',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(9),
            ],
            [
                'user_id' => 4,
                'tratamiento_base' => 'Carillas de Porcelana',
                'progreso' => 45,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'tratamiento_base' => 'Resina Fotopolimerizable',
                'progreso' => 100,
                'estado' => 'finalizado',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'user_id' => 5,
                'tratamiento_base' => 'Tratamiento de Conducto',
                'progreso' => 20,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'tratamiento_base' => 'Extracción de Cordales',
                'progreso' => 90,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'tratamiento_base' => 'Blanqueamiento Dental LED',
                'progreso' => 50,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Caso de Sergio
            [
                'user_id' => 3, 
                'tratamiento_base' => 'Ortodoncia Correctiva',
                'progreso' => 75,
                'estado' => 'activo',
                'created_at' => now()->subMonths(1),
                'updated_at' => now(),
            ],
        ]);
    }
}