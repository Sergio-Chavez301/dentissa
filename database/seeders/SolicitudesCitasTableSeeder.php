<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudesCitasTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('solicitudes_citas')->insert([
            [
                'nombre' => 'Diana',
                'apellidos' => 'Valenzuela Castro',
                'telefono' => '5552223333',
                'email' => 'diana.val@outlook.com',
                'fecha_hora_propuesta' => now()->addDays(2)->setTime(16, 20),
                'motivo_consulta' => 'Dolor agudo en muela de juicio superior izquierda.',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Roberto',
                'apellidos' => 'Ruiz Solís',
                'telefono' => '5557778888',
                'email' => 'roberto.r@gmail.com',
                'fecha_hora_propuesta' => now()->addDays(1)->setTime(10, 0),
                'motivo_consulta' => 'Revisión general y presupuesto para resinas.',
                'estado' => 'pendiente',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'nombre' => 'Laura',
                'apellidos' => 'Morales Vega',
                'telefono' => '5559990000',
                'email' => 'laura.m@hotmail.com',
                'fecha_hora_propuesta' => now()->addDays(3)->setTime(12, 30),
                'motivo_consulta' => 'Profilaxis simple y aplicación de flúor.',
                'estado' => 'confirmada',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
        ]);
    }
}