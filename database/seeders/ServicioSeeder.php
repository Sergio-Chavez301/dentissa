<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('servicios')->insert([
            ['id' => 1, 'nombre' => 'Limpieza Dental Profunda', 'descripcion' => 'Eliminación de sarro con ultrasonido y pulido coronario.', 'precio' => 600.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 2, 'nombre' => 'Endodoncia Molar', 'descripcion' => 'Tratamiento de conductos en piezas molares para salvar la pieza dental.', 'precio' => 2500.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 3, 'nombre' => 'Ortodoncia Correctiva', 'descripcion' => 'Instalación y ajuste mensual de brackets metálicos.', 'precio' => 1500.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 4, 'nombre' => 'Resina Fotopolimerizable', 'descripcion' => 'Restauración estética de cavidades causadas por caries utilizando resina de alta calidad.', 'precio' => 500.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 5, 'nombre' => 'Extracción de Tercer Molar', 'descripcion' => 'Cirugía menor para remover la muela del juicio de forma segura.', 'precio' => 1200.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 6, 'nombre' => 'Blanqueamiento Dental Láser', 'descripcion' => 'Tratamiento de aclaramiento químico acelerado por luz LED en consultorio.', 'precio' => 1800.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 7, 'nombre' => 'Corona de Porcelana', 'descripcion' => 'Rehabilitación dental estética mediante funda de porcelana de alta resistencia.', 'precio' => 3500.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 8, 'nombre' => 'Aplicación de Flúor Infantil', 'descripcion' => 'Tratamiento preventivo para fortalecer el esmalte de los dientes de los niños.', 'precio' => 300.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 9, 'nombre' => 'Guarda Oclusal', 'descripcion' => 'Placa rígida de acrílico hecha a medida para el tratamiento del bruxismo.', 'precio' => 1100.00, 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
        ]);
    }
}
