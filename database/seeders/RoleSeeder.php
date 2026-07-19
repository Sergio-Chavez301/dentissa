<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Administrador', 'description' => 'Acceso total al sistema', 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 2, 'name' => 'Asistente', 'description' => 'Asistencia en tareas clínicas', 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
            ['id' => 3, 'name' => 'Paciente', 'description' => 'Acceso para pacientes', 'created_at' => '2026-07-17 02:02:27', 'updated_at' => '2026-07-17 02:02:27'],
        ]);
    }
}
