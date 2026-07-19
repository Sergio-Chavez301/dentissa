<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UsuarioSeeder::class,
            PacienteSeeder::class,
            ServicioSeeder::class,
            SolicitudCitaSeeder::class,
            CitaSeeder::class,
            CasoClinicoSeeder::class,
        ]);
    }
}
