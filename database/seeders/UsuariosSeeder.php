<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Sergio',
                'apellidos' => 'Chavez',
                'username' => 'sergiochavez',
                'email' => 'administrador@example.com',
                'rol_id' => 1,
                'password' => bcrypt('123456789'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
