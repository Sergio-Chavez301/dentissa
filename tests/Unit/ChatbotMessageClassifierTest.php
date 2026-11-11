<?php

namespace Tests\Unit;

use App\Support\ChatbotMessageClassifier;
use PHPUnit\Framework\TestCase;

class ChatbotMessageClassifierTest extends TestCase
{
    public function test_it_maps_common_questions_to_public_topics(): void
    {
        $classifier = new ChatbotMessageClassifier();

        self::assertSame('services', $classifier->classify('¿Cuánto cuesta la limpieza dental?'));
        self::assertSame('offers', $classifier->classify('¿Tienen promociones?'));
        self::assertSame('hours', $classifier->classify('¿A qué hora abren?'));
        self::assertSame('location', $classifier->classify('¿Dónde está el consultorio?'));
        self::assertSame('appointment', $classifier->classify('Quiero agendar una cita'));
        self::assertSame('contact', $classifier->classify('¿Cuál es su WhatsApp?'));
    }

    public function test_it_routes_symptoms_and_clinical_advice_requests_safely(): void
    {
        $classifier = new ChatbotMessageClassifier();

        self::assertSame('urgent', $classifier->classify('Me duele mucho una muela'));
        self::assertSame('clinical', $classifier->classify('¿Qué medicamento recomiendas?'));
    }

    public function test_it_classifies_hours_questions_and_marks_unknown_topics(): void
    {
        self::assertSame('hours', (new ChatbotMessageClassifier())->classify('¿Atienden los domingos?'));
        self::assertSame('unknown', (new ChatbotMessageClassifier())->classify('¿Venden cepillos eléctricos?'));
    }
}
