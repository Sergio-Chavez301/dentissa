<x-plantilla>
    <x-slot:title>Guía de la app móvil | Dentissa</x-slot:title>

    <style>
        .mobile-guide {
            max-width: 1050px;
            margin: 0 auto;
        }

        .mobile-guide-content {
            color: #34343b;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .mobile-guide-content h1,
        .mobile-guide-content h2,
        .mobile-guide-content h3 {
            color: #1e1e24;
            font-weight: 700;
            line-height: 1.3;
        }

        .mobile-guide-content h1 {
            font-size: 1.8rem;
            margin-bottom: 1.25rem;
        }

        .mobile-guide-content h2 {
            border-bottom: 1px solid #eee;
            font-size: 1.35rem;
            margin-top: 2rem;
            padding-bottom: 0.5rem;
        }

        .mobile-guide-content h3 {
            font-size: 1.1rem;
            margin-top: 1.5rem;
        }

        .mobile-guide-content code {
            background: #f5f2f3;
            border-radius: 0.25rem;
            color: #a33154;
            padding: 0.1rem 0.3rem;
            overflow-wrap: anywhere;
        }

        .mobile-guide-content pre {
            background: #24232a;
            border-radius: 0.5rem;
            color: #fff;
            overflow-x: auto;
            padding: 1rem;
        }

        .mobile-guide-content pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .mobile-guide-content blockquote {
            background: #fff3f6;
            border-left: 4px solid #d75078;
            border-radius: 0.25rem;
            margin: 1rem 0;
            padding: 0.75rem 1rem;
        }

        .mobile-guide-content table {
            display: block;
            max-width: 100%;
            overflow-x: auto;
            width: max-content;
        }

        .mobile-guide-content th,
        .mobile-guide-content td {
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
        }

        .mobile-guide-content th {
            background: #f8f9fa;
        }

        .mobile-guide-content img {
            height: auto;
            max-width: 100%;
        }
    </style>

    <div class="mobile-guide">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Guía de la app móvil</h1>
                <p class="text-muted mb-0">Preparación, configuración, distribución y solución de problemas.</p>
            </div>
            <a href="{{ route('admin.documentation.index') }}" class="btn btn-dentissa shrink-0">
                <i class="bi bi-arrow-left me-1"></i>Volver a documentación
            </a>
        </div>

        <article class="card border-0 shadow-sm">
            <div class="card-body p-3 p-md-5 mobile-guide-content">
                {!! $documentation !!}
            </div>
        </article>

        <article class="card border-0 shadow-sm mt-4">
            <div class="card-body p-3 p-md-5 mobile-guide-content">
                {!! $presentation !!}
            </div>
        </article>
    </div>
</x-plantilla>
