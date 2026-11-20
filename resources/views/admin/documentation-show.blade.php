<x-plantilla>
    <x-slot:title>{{ $details['title'] }} | Documentación Dentissa</x-slot:title>

    <style>
        .documentation-page {
            max-width: 1440px;
            margin: 0 auto;
        }

        .documentation-frame {
            display: block;
            width: 100%;
            height: calc(100dvh - 180px);
            min-height: 620px;
            border: 1px solid #e8e4e6;
            border-radius: 0.75rem;
            background: #fff;
        }

        @media (max-width: 767.98px) {
            .documentation-frame {
                height: 80vh;
                min-height: 540px;
            }
        }
    </style>

    <div class="documentation-page">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
            <div>
                <span class="text-uppercase fw-semibold small text-dentissa">Documentación del sistema</span>
                <h1 class="h3 fw-bold mt-1 mb-1">{{ $details['title'] }}</h1>
                <p class="text-muted mb-0">{{ $details['description'] }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.documentation.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Índice
                </a>
                <a href="{{ route('admin.mobile-app-guide') }}" class="btn btn-dentissa">
                    <i class="bi bi-phone me-1"></i>Guía móvil
                </a>
            </div>
        </div>

        <iframe
            class="documentation-frame"
            src="{{ route('admin.documentation.content', ['document' => $document]) }}"
            title="{{ $details['title'] }}"
        ></iframe>
    </div>
</x-plantilla>
