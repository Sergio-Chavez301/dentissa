<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        RolSeeder::class,
        ServiciosTableSeeder::class,
        PacientesTableSeeder::class,
        SolicitudesCitasTableSeeder::class,
        UsuariosSeeder::class,      
        CitasTableSeeder::class,    
        CasosClinicosTableSeeder::class 
    ]);
}
}