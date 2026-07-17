<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitasTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('citas')->insert([
            [
                'paciente_id' => 1, // Sergio Chávez
                'servicio_id' => 2, // Endodoncia Molar (asumiendo IDs de tu ServiciosSeeder)
                'fecha' => now()->format('Y-m-d'),
                'hora' => '09:00:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => 2, // Mariana Fuentes
                'servicio_id' => 1, // Resina Fotopolimerizable
                'fecha' => now()->format('Y-m-d'),
                'hora' => '10:30:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => 3, // Alejandro Ruiz
                'servicio_id' => 3, // Limpieza Dental Profunda
                'fecha' => now()->format('Y-m-d'),
                'hora' => '12:00:00',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}