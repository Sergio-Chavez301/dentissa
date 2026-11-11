<?php

namespace App\Http\Controllers;

use App\Models\OfficeSetting;
use App\Models\Oferta;
use App\Models\Servicio;
use App\Support\ChatbotMessageClassifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function respond(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'intent' => 'required_without:message|nullable|string|in:menu,services,offers,hours,location,appointment,contact,urgent,clinical',
            'message' => 'required_without:intent|nullable|string|max:500',
        ]);
        $intent = $validated['intent'] ?? app(ChatbotMessageClassifier::class)->classify($validated['message']);

        $office = OfficeSetting::current();
        $whatsappUrl = $office->whatsapp_digits
            ? 'https://wa.me/' . $office->whatsapp_digits . '?text=' . rawurlencode(
                'Hola, quisiera comunicarme con ' . $office->clinic_name . '.'
            )
            : null;

        [$message, $options] = match ($intent) {
            'services' => [
                $this->servicesMessage(),
                $this->menuOptions(),
            ],
            'offers' => [
                $this->offersMessage(),
                $this->menuOptions(),
            ],
            'hours' => [
                'El horario de atención es de lunes a sábado, de 9:00 a 19:00. Para solicitar una cita, elige una fecha y el equipo confirmará el horario.',
                [
                    ['label' => 'Solicitar una cita', 'action' => 'request'],
                    ['label' => 'Volver al menú', 'intent' => 'menu'],
                ],
            ],
            'location' => [
                $office->clinic_address
                    ? 'La dirección del consultorio es: ' . $office->clinic_address
                    : 'La dirección todavía no está publicada. Puedes contactar al consultorio para recibir indicaciones.',
                $whatsappUrl
                    ? [
                        ['label' => 'Contactar por WhatsApp', 'action' => 'link', 'url' => $whatsappUrl],
                        ['label' => 'Volver al menú', 'intent' => 'menu'],
                    ]
                    : $this->menuOptions(),
            ],
            'appointment' => [
                'Puedes enviar una solicitud con la fecha y el horario que prefieres. El equipo revisará la disponibilidad; la cita no queda confirmada hasta que el consultorio te contacte.',
                [
                    ['label' => 'Abrir formulario de cita', 'action' => 'request'],
                    ['label' => 'Volver al menú', 'intent' => 'menu'],
                ],
            ],
            'contact' => [
                $whatsappUrl
                    ? 'Puedes escribir directamente al equipo del consultorio por WhatsApp.'
                    : 'No hay un número de WhatsApp publicado. Puedes enviar una solicitud de cita y el equipo se pondrá en contacto contigo.',
                $whatsappUrl
                    ? [
                        ['label' => 'Abrir WhatsApp', 'action' => 'link', 'url' => $whatsappUrl],
                        ['label' => 'Volver al menú', 'intent' => 'menu'],
                    ]
                    : [
                        ['label' => 'Solicitar una cita', 'action' => 'request'],
                        ['label' => 'Volver al menú', 'intent' => 'menu'],
                    ],
            ],
            'urgent' => [
                'Este chat no puede valorar urgencias ni dar indicaciones clínicas. Si consideras que necesitas atención urgente, comunícate con los servicios de emergencia locales o contacta directamente al consultorio; no esperes una respuesta del chat.',
                $whatsappUrl
                    ? [
                        ['label' => 'Contactar al consultorio', 'action' => 'link', 'url' => $whatsappUrl],
                        ['label' => 'Volver al menú', 'intent' => 'menu'],
                    ]
                    : $this->menuOptions(),
            ],
            'clinical' => [
                'No puedo evaluar síntomas ni recomendar diagnósticos, medicamentos o tratamientos por chat. Un profesional dental debe valorar tu caso; puedes solicitar una cita para recibir orientación adecuada.',
                [
                    ['label' => 'Solicitar una valoración', 'action' => 'request'],
                    ['label' => 'Contactar al consultorio', 'intent' => 'contact'],
                ],
            ],
            'unknown' => [
                'No encontré esa información. Puedo ayudarte con servicios, ofertas, horarios, ubicación o solicitudes de cita. El chat no da consejos clínicos.',
                $this->menuOptions(),
            ],
            default => [
                '¡Hola! Puedo orientarte con información general del consultorio. ¿Qué te gustaría consultar?',
                $this->menuOptions(),
            ],
        };

        return response()->json([
            'message' => $message,
            'options' => $options,
        ]);
    }

    private function servicesMessage(): string
    {
        $services = Servicio::query()->orderBy('nombre')->get(['nombre', 'descripcion', 'precio']);

        if ($services->isEmpty()) {
            return 'Por el momento no hay servicios publicados. Puedes contactar al consultorio para más información.';
        }

        return "Estos son los servicios disponibles:\n" . $services
            ->map(fn (Servicio $service) => $service->nombre
                . ' — $' . number_format($service->precio, 2)
                . ($service->descripcion ? ': ' . $service->descripcion : ''))
            ->implode("\n");
    }

    private function offersMessage(): string
    {
        $offers = Oferta::query()
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->get(['titulo', 'descripcion', 'precio']);

        if ($offers->isEmpty()) {
            return 'No hay ofertas activas publicadas en este momento.';
        }

        return "Estas son las ofertas vigentes:\n" . $offers
            ->map(fn (Oferta $offer) => $offer->titulo
                . ($offer->precio !== null ? ' — $' . number_format($offer->precio, 2) : '')
                . "\n" . $offer->descripcion)
            ->implode("\n");
    }

    private function menuOptions(): array
    {
        return [
            ['label' => 'Ver servicios', 'intent' => 'services'],
            ['label' => 'Ver ofertas', 'intent' => 'offers'],
            ['label' => 'Horario de atención', 'intent' => 'hours'],
            ['label' => 'Ubicación', 'intent' => 'location'],
            ['label' => 'Solicitar una cita', 'intent' => 'appointment'],
            ['label' => 'Contactar al consultorio', 'intent' => 'contact'],
            ['label' => 'Tengo una urgencia', 'intent' => 'urgent'],
        ];
    }
}
