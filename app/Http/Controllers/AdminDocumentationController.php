<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class AdminDocumentationController extends Controller
{
    private const DOCUMENTS = [
        'index.html' => [
            'title' => 'Índice de documentación',
            'description' => 'Acceso al material técnico, los procesos y el modelo de datos.',
        ],
        'diagrama-base-datos.html' => [
            'title' => 'Diagrama de base de datos',
            'description' => 'Entidades, campos, claves foráneas y asociaciones Eloquent.',
        ],
        'flujo-sistema.html' => [
            'title' => 'Flujos del sistema',
            'description' => 'Procesos de solicitudes, agenda, tratamientos y acceso por rol.',
        ],
        'documentacion-sistema.html' => [
            'title' => 'Referencia técnica',
            'description' => 'Arquitectura, módulos, permisos, configuración y despliegue.',
        ],
    ];

    public function index()
    {
        return view('admin.documentation-index', [
            'documents' => self::DOCUMENTS,
        ]);
    }

    public function show(string $document)
    {
        abort_unless(isset(self::DOCUMENTS[$document]), 404);

        $path = base_path('documentacion/informacion/'.$document);
        abort_unless(File::isFile($path), 404);

        $html = File::get($path);
        $html = preg_replace_callback('/<body([^>]*)>/i', function (array $matches): string {
            return $matches[0].view('admin.documentation-toolbar')->render();
        }, $html, 1, $replacements);

        abort_unless($replacements === 1, 500, 'No se pudo preparar la página de documentación.');

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
