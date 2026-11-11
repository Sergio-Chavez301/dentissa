<?php

namespace Tests\Feature;

use App\Models\Oferta;
use App\Models\Servicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_menu_returns_guided_public_options(): void
    {
        $this->postJson(route('api.chatbot'), ['intent' => 'menu'])
            ->assertOk()
            ->assertJsonPath('options.0.label', 'Ver servicios')
            ->assertJsonPath('options.4.intent', 'appointment')
            ->assertJsonPath('options.6.intent', 'urgent');
    }

    public function test_chatbot_lists_services_and_only_active_offers(): void
    {
        Servicio::create([
            'nombre' => 'Limpieza dental',
            'descripcion' => 'Limpieza profesional.',
            'precio' => 900,
        ]);
        Oferta::create([
            'titulo' => 'Valoración inicial',
            'descripcion' => 'Promoción vigente.',
            'precio' => 500,
            'orden' => 1,
            'activo' => true,
        ]);
        Oferta::create([
            'titulo' => 'Oferta vencida',
            'descripcion' => 'No debe mostrarse.',
            'precio' => 300,
            'orden' => 2,
            'activo' => false,
        ]);

        $this->postJson(route('api.chatbot'), ['intent' => 'services'])
            ->assertOk()
            ->assertJsonPath(
                'message',
                'Estos son los servicios disponibles:' . "\n" . 'Limpieza dental — $900.00: Limpieza profesional.'
            );

        $this->postJson(route('api.chatbot'), ['intent' => 'offers'])
            ->assertOk()
            ->assertJsonPath(
                'message',
                'Estas son las ofertas vigentes:' . "\n" . 'Valoración inicial — $500.00' . "\n" . 'Promoción vigente.'
            )
            ->assertDontSee('Oferta vencida');
    }

    public function test_chatbot_routes_appointment_request_without_confirming_it(): void
    {
        $this->postJson(route('api.chatbot'), ['intent' => 'appointment'])
            ->assertOk()
            ->assertJsonPath('options.0.action', 'request')
            ->assertJsonPath('message', 'Puedes enviar una solicitud con la fecha y el horario que prefieres. El equipo revisará la disponibilidad; la cita no queda confirmada hasta que el consultorio te contacte.');
    }

    public function test_chatbot_answers_free_text_questions_with_server_classification(): void
    {
        $this->postJson(route('api.chatbot'), ['message' => '¿A qué hora abren?'])
            ->assertOk()
            ->assertJsonPath('message', 'El horario de atención es de lunes a sábado, de 9:00 a 19:00. Para solicitar una cita, elige una fecha y el equipo confirmará el horario.');
    }

    public function test_chatbot_rejects_unknown_intents(): void
    {
        $this->postJson(route('api.chatbot'), ['intent' => 'patient-record'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('intent');
    }
}
