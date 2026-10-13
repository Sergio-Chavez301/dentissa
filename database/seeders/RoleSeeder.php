<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            1 => ['name' => 'Administrador', 'description' => 'Acceso total al sistema'],
            2 => ['name' => 'Asistente', 'description' => 'Asistencia en tareas clínicas'],
            3 => ['name' => 'Paciente', 'description' => 'Acceso para pacientes'],
        ];

        foreach ($roles as $id => $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $id],
                [...$role, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
