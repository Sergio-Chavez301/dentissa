<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas | Dentissa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body { overflow-x: hidden; }
        body { background: #f7f8f5; font-family: 'Segoe UI', sans-serif; color: #183a37; }
        .bg-dentissa { background-color: #d75078 !important; }
        .text-dentissa { color: #d75078 !important; }
        .card-offer { border: 0; border-radius: 18px; box-shadow: 0 12px 30px rgba(24,58,55,.08); }
        .tag { display: inline-block; padding: .45rem .8rem; background: rgba(215,80,120,.12); color: #b82c59; font-weight: 700; border-radius: 999px; font-size: .75rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <p class="text-uppercase text-dentissa mb-1 fw-bold small">Dentissa</p>
                <h1 class="fw-bold mb-0">Ofertas y promociones</h1>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Volver al inicio</a>
        </div>

        @if($ofertas->isNotEmpty())
            <div class="row g-4">
                @foreach($ofertas as $oferta)
                    <div class="col-md-4">
                        <div class="card card-offer h-100 p-4">
                            <span class="tag mb-3">{{ $oferta->tag ?? 'Promoción' }}</span>
                            <h3 class="fw-bold mb-2">{{ $oferta->titulo }}</h3>
                            @if($oferta->precio)
                                <div class="display-6 fw-bold text-dentissa mb-3">${{ number_format((float) $oferta->precio, 0) }}</div>
                            @else
                                <div class="display-6 fw-bold text-dentissa mb-3">Consulta</div>
                            @endif
                            <p class="text-muted mb-4">{{ $oferta->descripcion }}</p>
                            <a href="{{ route('home') }}#contacto" class="btn bg-dentissa text-white w-100">Solicitar información</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light border shadow-sm text-center py-4">
                <h3 class="fw-bold mb-2">No hay ofertas activas</h3>
                <p class="mb-0 text-muted">La administradora puede agregar promociones desde el panel de contenido web.</p>
            </div>
        @endif

        <div class="mt-5 p-4 bg-white rounded-4 shadow-sm">
            <h3 class="fw-bold mb-3">¿Qué incluye?</h3>
            <div class="row gy-3">
                <div class="col-md-4"><i class="bi bi-check-circle-fill text-dentissa me-2"></i> Valoración inicial profesional</div>
                <div class="col-md-4"><i class="bi bi-check-circle-fill text-dentissa me-2"></i> Plan de tratamiento claro</div>
                <div class="col-md-4"><i class="bi bi-check-circle-fill text-dentissa me-2"></i> Acompañamiento durante cada etapa</div>
            </div>
        </div>
    </div>
</body>
</html>
