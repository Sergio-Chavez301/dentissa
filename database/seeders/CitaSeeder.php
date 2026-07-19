<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('citas')->insert([
            ['id' => 1, 'paciente_id' => 1, 'servicio_id' => 2, 'fecha' => '2026-07-16', 'hora' => '09:00:00', 'estado' => 'no_presento', 'created_at' => '2026-07-17 02:02:29', 'updated_at' => '2026-07-18 14:33:10'],
            ['id' => 2, 'paciente_id' => 2, 'servicio_id' => 1, 'fecha' => '2026-07-17', 'hora' => '10:30:00', 'estado' => 'realizada', 'created_at' => '2026-07-17 02:02:29', 'updated_at' => '2026-07-18 14:27:20'],
            ['id' => 3, 'paciente_id' => 3, 'servicio_id' => 3, 'fecha' => '2026-07-17', 'hora' => '12:00:00', 'estado' => 'cancelada', 'created_at' => '2026-07-17 02:02:29', 'updated_at' => '2026-07-18 14:40:45'],
            ['id' => 4, 'paciente_id' => 1, 'servicio_id' => 1, 'fecha' => '2026-07-20', 'hora' => '10:30:00', 'estado' => 'en_espera', 'created_at' => '2026-07-17 22:04:34', 'updated_at' => '2026-07-17 22:04:34'],
            ['id' => 5, 'paciente_id' => 5, 'servicio_id' => 2, 'fecha' => '2026-07-17', 'hora' => '18:40:00', 'estado' => 'en_espera', 'created_at' => '2026-07-17 22:06:18', 'updated_at' => '2026-07-17 22:06:18'],
            ['id' => 6, 'paciente_id' => 1, 'servicio_id' => 1, 'fecha' => '2026-07-18', 'hora' => '10:00:00', 'estado' => 'en_espera', 'created_at' => '2026-07-17 18:14:11', 'updated_at' => '2026-07-17 18:14:11'],
            ['id' => 7, 'paciente_id' => 6, 'servicio_id' => 1, 'fecha' => '2026-07-19', 'hora' => '16:20:00', 'estado' => 'en_espera', 'created_at' => '2026-07-17 18:43:03', 'updated_at' => '2026-07-17 18:43:03'],
            ['id' => 8, 'paciente_id' => 10, 'servicio_id' => 1, 'fecha' => '2026-07-20', 'hora' => '12:30:00', 'estado' => 'en_espera', 'created_at' => '2026-07-18 14:32:43', 'updated_at' => '2026-07-18 14:32:43'],
            ['id' => 9, 'paciente_id' => 11, 'servicio_id' => 1, 'fecha' => '2026-07-20', 'hora' => '12:30:00', 'estado' => 'en_espera', 'created_at' => '2026-07-18 14:33:57', 'updated_at' => '2026-07-18 14:33:57'],
            ['id' => 10, 'paciente_id' => 12, 'servicio_id' => 1, 'fecha' => '2026-07-19', 'hora' => '16:20:00', 'estado' => 'en_espera', 'created_at' => '2026-07-18 14:36:55', 'updated_at' => '2026-07-18 14:36:55'],
        ]);
    }
}
