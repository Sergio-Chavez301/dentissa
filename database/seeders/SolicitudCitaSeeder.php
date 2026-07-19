<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudCitaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('solicitudes_citas')->insert([
            ['id' => 1, 'nombre' => 'Diana', 'apellidos' => 'Valenzuela Castro', 'telefono' => '5552223333', 'email' => 'diana.val@outlook.com', 'fecha_hora_propuesta' => '2026-07-19 16:20:00', 'motivo_consulta' => 'Dolor agudo en muela de juicio superior izquierda.', 'estado' => 'confirmada', 'created_at' => '2026-07-17 02:02:28', 'updated_at' => '2026-07-18 14:36:55', 'fecha_nacimiento' => '2005-09-08'],
            ['id' => 2, 'nombre' => 'Roberto', 'apellidos' => 'Ruiz Solís', 'telefono' => '5557778888', 'email' => 'roberto.r@gmail.com', 'fecha_hora_propuesta' => '2026-07-18 10:00:00', 'motivo_consulta' => 'Revisión general y presupuesto para resinas.', 'estado' => 'cancelada', 'created_at' => '2026-07-16 02:02:28', 'updated_at' => '2026-07-18 14:47:43', 'fecha_nacimiento' => '2005-09-08'],
            ['id' => 3, 'nombre' => 'Laura', 'apellidos' => 'Morales Vega', 'telefono' => '5559990000', 'email' => 'laura.m@hotmail.com', 'fecha_hora_propuesta' => '2026-07-20 12:30:00', 'motivo_consulta' => 'Profilaxis simple y aplicación de flúor.', 'estado' => 'confirmada', 'created_at' => '2026-07-15 02:02:28', 'updated_at' => '2026-07-18 14:33:57', 'fecha_nacimiento' => '2005-09-08'],
            ['id' => 4, 'nombre' => 'serxgz', 'apellidos' => 'pepe', 'telefono' => '4541214545', 'email' => '7415@gmail.com', 'fecha_hora_propuesta' => '2026-07-19 05:21:31', 'motivo_consulta' => 'wdwdwdwdw', 'estado' => 'cancelada', 'created_at' => null, 'updated_at' => '2026-07-18 19:30:55', 'fecha_nacimiento' => '2000-01-01'],
            ['id' => 5, 'nombre' => 'ddsdssdsd', 'apellidos' => 'sdsdsds', 'telefono' => '84651324895', 'email' => 'dwdd@dwdwd.com', 'fecha_hora_propuesta' => '2026-07-19 05:22:52', 'motivo_consulta' => 'wdwdwdw', 'estado' => 'esperando_confirmacion', 'created_at' => null, 'updated_at' => null, 'fecha_nacimiento' => '2016-07-01'],
        ]);
    }
}
