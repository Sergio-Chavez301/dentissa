<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Administrador', 'description' => 'Acceso total al sistema', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Asistente', 'description' => 'Asistencia en tareas clínicas', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Odontólogo', 'description' => 'Odontólogo especialista', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}