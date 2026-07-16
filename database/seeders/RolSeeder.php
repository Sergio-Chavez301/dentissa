<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'administrador',
                'description' => 'Acceso total al sistema y control de la clínica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'asistente',
                'description' => 'Gestión de citas, pacientes y agenda diaria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'paciente',
                'description' => 'Acceso limitado para ver sus citas e historial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4, // <-- ESTE DEBE EXISTIR
                'name' => 'odontologo',
                'description' => 'Acceso a expedientes clínicos, evolución de tratamientos y recetas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5, // <-- ESTE DEBE EXISTIR
                'name' => 'recepcionista',
                'description' => 'Atención al cliente, cobros, facturación básica y registro de llamadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}