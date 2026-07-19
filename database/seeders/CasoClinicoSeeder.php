<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasoClinicoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('casos_clinicos')->insert([
            ['id' => 1, 'paciente_id' => 2, 'tratamiento_base' => 'Carillas de Porcelana', 'progreso' => 100, 'estado' => 'activo', 'created_at' => '2026-07-17 02:02:30', 'updated_at' => '2026-07-18 17:28:55'],
            ['id' => 2, 'paciente_id' => 3, 'tratamiento_base' => 'Tratamiento de Conducto', 'progreso' => 20, 'estado' => 'activo', 'created_at' => '2026-07-17 02:02:30', 'updated_at' => '2026-07-17 02:02:30'],
            ['id' => 3, 'paciente_id' => 4, 'tratamiento_base' => 'Extracción de Cordales', 'progreso' => 90, 'estado' => 'activo', 'created_at' => '2026-07-17 02:02:30', 'updated_at' => '2026-07-17 02:02:30'],
            ['id' => 4, 'paciente_id' => 4, 'tratamiento_base' => 'Blanqueamiento Dental LED', 'progreso' => 50, 'estado' => 'activo', 'created_at' => '2026-07-17 02:02:30', 'updated_at' => '2026-07-17 02:02:30'],
            ['id' => 5, 'paciente_id' => 1, 'tratamiento_base' => 'Ortodoncia Correctiva', 'progreso' => 75, 'estado' => 'activo', 'created_at' => '2026-07-17 02:02:30', 'updated_at' => '2026-07-17 02:02:30'],
            ['id' => 6, 'paciente_id' => 5, 'tratamiento_base' => 'pepe', 'progreso' => 10, 'estado' => 'activo', 'created_at' => '2026-07-18 18:59:33', 'updated_at' => '2026-07-18 18:59:33'],
            ['id' => 7, 'paciente_id' => 13, 'tratamiento_base' => 'sexo', 'progreso' => 5, 'estado' => 'activo', 'created_at' => '2026-07-18 19:05:49', 'updated_at' => '2026-07-18 19:05:49'],
        ]);
    }
}
