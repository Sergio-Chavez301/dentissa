<?php

namespace App\Support;

use Illuminate\Support\Str;

class ChatbotMessageClassifier
{
    public function classify(string $message): string
    {
        $message = Str::lower(Str::ascii($message));

        return match (true) {
            preg_match('/\b(dolor|duele|inflam\w*|hinch\w*|sangr\w*|infeccion|absceso|emergencia|urgencia|accidente|fractura|golpe|fiebre|pus|trauma)\b/', $message) === 1 => 'urgent',
            preg_match('/\b(horario|hora|abren|abierto|cierran|atienden)\b/', $message) === 1 => 'hours',
            preg_match('/\b(ubicacion|direccion|donde|mapa|llegar)\b/', $message) === 1 => 'location',
            preg_match('/\b(oferta|ofertas|promocion|promociones|descuento)\b/', $message) === 1 => 'offers',
            preg_match('/\b(servicio|servicios|tratamiento|limpieza|ortodoncia|blanqueamiento|precio|precios|cuesta|costo)\b/', $message) === 1 => 'services',
            preg_match('/\b(cita|agendar|agenda|reservar|disponibilidad|turno)\b/', $message) === 1 => 'appointment',
            preg_match('/\b(whatsapp|contacto|contactar|telefono|llamar)\b/', $message) === 1 => 'contact',
            preg_match('/\b(diagnostico|diagnosticar|medicamento|medicamentos|antibiotico|antibioticos|analgesico|dosis|receta|anestesia|recomiendas|recomendar)\b/', $message) === 1 => 'clinical',
            default => 'unknown',
        };
    }
}
