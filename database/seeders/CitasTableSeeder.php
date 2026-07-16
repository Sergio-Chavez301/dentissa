<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitasTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('citas')->insert([
            // --- CITAS PARA HOY ---
            [
                'user_id' => 3, // Sergio
                'servicio_id' => 2, 
                'fecha' => now()->toDateString(),
                'hora' => '09:00:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4, // Mariana
                'servicio_id' => 4, // Endodoncia Anterior
                'fecha' => now()->toDateString(),
                'hora' => '10:30:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5, // Alejandro
                'servicio_id' => 1, 
                'fecha' => now()->toDateString(),
                'hora' => '12:00:00',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6, // Beatriz
                'servicio_id' => 5, // Extracción de Tercer Molar
                'fecha' => now()->toDateString(),
                'hora' => '14:30:00',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- CITAS PARA MAÑANA Y PRÓXIMOS DÍAS ---
            [
                'user_id' => 3, // Sergio
                'servicio_id' => 3, 
                'fecha' => now()->addDay()->toDateString(),
                'hora' => '16:00:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4, // Mariana
                'servicio_id' => 5, // Extracción de Tercer Molar
                'fecha' => now()->addDay()->toDateString(),
                'hora' => '11:00:00',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5, // Alejandro
                'servicio_id' => 4, // Endodoncia Anterior
                'fecha' => now()->addDays(2)->toDateString(),
                'hora' => '09:30:00',
                'estado' => 'confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6, // Beatriz
                'servicio_id' => 2, 
                'fecha' => now()->addDays(3)->toDateString(),
                'hora' => '15:00:00',
                'estado' => 'cancelada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}