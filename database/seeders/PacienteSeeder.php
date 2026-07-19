<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pacientes')->insert([
            ['id' => 1, 'nombre' => 'Sergio', 'apellidos' => 'Chávez Guardado', 'telefono' => '1234567890', 'fecha_nacimiento' => '1998-05-15', 'alergias' => 'Ninguna', 'enfermedades' => 'Ninguna', 'tratamientos' => null, 'created_at' => '2026-07-17 02:01:45', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 2, 'nombre' => 'Mariana', 'apellidos' => 'Fuentes Ortiz', 'telefono' => '0987654321', 'fecha_nacimiento' => '2000-09-20', 'alergias' => 'Penicilina', 'enfermedades' => 'Ninguna', 'tratamientos' => null, 'created_at' => '2026-07-17 02:01:45', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 3, 'nombre' => 'Alejandro', 'apellidos' => 'Ruiz Peña', 'telefono' => '5551234567', 'fecha_nacimiento' => '1995-02-10', 'alergias' => 'Ninguna', 'enfermedades' => 'Asma', 'tratamientos' => 'Inhalador', 'created_at' => '2026-07-17 02:01:45', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 4, 'nombre' => 'Beatriz', 'apellidos' => 'Mendoza Torres', 'telefono' => '5557654321', 'fecha_nacimiento' => '1992-11-30', 'alergias' => 'Polvo', 'enfermedades' => 'Hipertensión', 'tratamientos' => 'Control médico diario', 'created_at' => '2026-07-17 02:01:45', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 5, 'nombre' => 'Sergio Gabriel', 'apellidos' => 'Chavez Del Carmen', 'telefono' => '7581100078', 'fecha_nacimiento' => '2005-09-08', 'alergias' => 'ninguna', 'enfermedades' => 'ninguna', 'tratamientos' => 'limpieza de caries', 'created_at' => '2026-07-17 20:30:34', 'updated_at' => '2026-07-17 20:30:34'],
            ['id' => 6, 'nombre' => 'Diana', 'apellidos' => 'Valenzuela Castro', 'telefono' => '5552223333', 'fecha_nacimiento' => '2005-09-08', 'alergias' => 'zscscz', 'enfermedades' => 'szzsc', 'tratamientos' => 'sczcsz', 'created_at' => '2026-07-17 18:43:03', 'updated_at' => '2026-07-17 18:50:30'],
            ['id' => 7, 'nombre' => 'awsdad', 'apellidos' => 'awdaw', 'telefono' => '645454', 'fecha_nacimiento' => '2026-06-30', 'alergias' => '45454ef<', 'enfermedades' => 'sc<cs', 'tratamientos' => 'sczcsz', 'created_at' => '2026-07-17 18:50:14', 'updated_at' => '2026-07-17 18:50:14'],
            ['id' => 8, 'nombre' => 'Laura', 'apellidos' => 'Morales Vega', 'telefono' => '5559990000', 'fecha_nacimiento' => '2005-09-08', 'alergias' => null, 'enfermedades' => null, 'tratamientos' => null, 'created_at' => '2026-07-18 14:27:47', 'updated_at' => '2026-07-18 14:27:47'],
            ['id' => 9, 'nombre' => 'Laura', 'apellidos' => 'Morales Vega', 'telefono' => '5559990000', 'fecha_nacimiento' => '2005-09-08', 'alergias' => null, 'enfermedades' => null, 'tratamientos' => null, 'created_at' => '2026-07-18 14:31:23', 'updated_at' => '2026-07-18 14:31:23'],
            ['id' => 10, 'nombre' => 'Laura', 'apellidos' => 'Morales Vega', 'telefono' => '5559990000', 'fecha_nacimiento' => '2005-09-08', 'alergias' => null, 'enfermedades' => null, 'tratamientos' => null, 'created_at' => '2026-07-18 14:32:43', 'updated_at' => '2026-07-18 14:32:43'],
            ['id' => 11, 'nombre' => 'Laura', 'apellidos' => 'Morales Vega', 'telefono' => '5559990000', 'fecha_nacimiento' => '2005-09-08', 'alergias' => null, 'enfermedades' => null, 'tratamientos' => null, 'created_at' => '2026-07-18 14:33:56', 'updated_at' => '2026-07-18 14:33:56'],
            ['id' => 12, 'nombre' => 'Diana', 'apellidos' => 'Valenzuela Castro', 'telefono' => '5552223333', 'fecha_nacimiento' => '2005-09-08', 'alergias' => null, 'enfermedades' => null, 'tratamientos' => null, 'created_at' => '2026-07-18 14:36:54', 'updated_at' => '2026-07-18 14:36:54'],
            ['id' => 13, 'nombre' => 'pipepipe', 'apellidos' => 'papepa', 'telefono' => '7587415821', 'fecha_nacimiento' => '2026-07-21', 'alergias' => 'ninguna', 'enfermedades' => 'ninguna', 'tratamientos' => 'ninguna', 'created_at' => '2026-07-18 19:05:49', 'updated_at' => '2026-07-18 19:05:49'],
        ]);
    }
}
