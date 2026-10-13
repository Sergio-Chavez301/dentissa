<?php

namespace Database\Seeders;

use App\Models\Oferta;
use Illuminate\Database\Seeder;

class OfertaSeeder extends Seeder
{
    public function run(): void
    {
        $ofertas = [
            [
                'titulo' => 'Limpieza + valoración',
                'tag' => 'Oferta del mes',
                'descripcion' => 'Incluye valoración inicial, limpieza profunda y recomendaciones personalizadas para mantener sana tu sonrisa.',
                'precio' => 1299,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'titulo' => 'Blanqueamiento en consultorio',
                'tag' => 'Estética',
                'descripcion' => 'Un protocolo seguro para iluminar tu sonrisa con guía profesional durante todo el proceso.',
                'precio' => 1899,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'titulo' => 'Paquete integral',
                'tag' => 'Plan',
                'descripcion' => 'Ideal si buscas prevención, seguimiento y cuidado continuo para mantener una sonrisa saludable.',
                'precio' => 2499,
                'orden' => 3,
                'activo' => true,
            ],
        ];

        foreach ($ofertas as $oferta) {
            Oferta::updateOrCreate(
                ['titulo' => $oferta['titulo']],
                $oferta
            );
        }
    }
}
