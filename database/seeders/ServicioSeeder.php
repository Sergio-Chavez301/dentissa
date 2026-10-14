<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'nombre' => 'Limpieza dental',
                'descripcion' => 'Limpieza profesional y eliminación de sarro.',
                'precio' => 650.00,
            ],
            [
                'nombre' => 'Consulta de valoración',
                'descripcion' => 'Revisión general y plan de tratamiento.',
                'precio' => 400.00,
            ],
            [
                'nombre' => 'Resina dental',
                'descripcion' => 'Restauración dental con resina estética.',
                'precio' => 850.00,
            ],
            [
                'nombre' => 'Blanqueamiento dental',
                'descripcion' => 'Tratamiento estético de blanqueamiento en consultorio.',
                'precio' => 2200.00,
            ],
            [
                'nombre' => 'Extracción dental',
                'descripcion' => 'Extracción simple previa valoración profesional.',
                'precio' => 1200.00,
            ],
        ];

        foreach ($services as $service) {
            Servicio::updateOrCreate(
                ['nombre' => $service['nombre']],
                $service
            );
        }
    }
}