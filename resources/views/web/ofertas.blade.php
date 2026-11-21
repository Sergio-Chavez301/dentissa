<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Conoce las ofertas y promociones vigentes de {{ $officeSettings->clinic_name }} y solicita información por WhatsApp.">
    <title>Ofertas | {{ $officeSettings->clinic_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --dentissa-ink: #183a37;
            --dentissa-muted: #60736e;
            --dentissa-paper: #f7f8f5;
            --dentissa-rose: #d84170;
            --dentissa-line: #dbe4df;
        }

        body {
            min-height: 100vh;
            color: var(--dentissa-ink);
            background: var(--dentissa-paper);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .public-header {
            background: #fff;
            border-bottom: 1px solid var(--dentissa-line);
        }

        .public-header-inner,
        .public-main {
            width: min(1120px, calc(100% - 32px));
            margin-inline: auto;
        }

        .public-header-inner {
            min-height: 76px;
        }

        .public-logo {
            width: 170px;
            height: 58px;
            object-fit: contain;
        }

        .text-dentissa {
            color: var(--dentissa-rose);
        }

        .btn-dentissa {
            color: #fff;
            background: var(--dentissa-rose);
            border-color: var(--dentissa-rose);
        }

        .btn-dentissa:hover,
        .btn-dentissa:focus-visible {
            color: #fff;
            background: #b82c59;
            border-color: #b82c59;
        }

        .public-main {
            padding-block: 42px 56px;
        }

        .offer-card {
            border: 1px solid #e8eeea;
            border-radius: 12px;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .offer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px #183a3714 !important;
        }

        .offer-description {
            color: var(--dentissa-muted);
            white-space: pre-line;
        }

        @media (max-width: 575.98px) {
            .public-header-inner {
                min-height: 66px;
            }

            .public-logo {
                width: 140px;
                height: 50px;
            }

            .public-main {
                padding-top: 28px;
            }
        }
    </style>
</head>
<body>
    <header class="public-header">
        <nav class="public-header-inner d-flex justify-content-between align-items-center gap-3" aria-label="Navegación pública">
            <a href="{{ route('home') }}" aria-label="Volver al sitio de {{ $officeSettings->clinic_name }}">
                <img class="public-logo" src="{{ asset('img/logo.png') }}" alt="{{ $officeSettings->clinic_name }}">
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver al sitio
            </a>
        </nav>
    </header>

    <main class="public-main">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom">
            <div>
                <span class="d-block text-uppercase text-dentissa mb-1 fw-bold small">{{ $officeSettings->clinic_name }}</span>
                <h1 class="h2 fw-bold mb-2">Ofertas y promociones</h1>
                <p class="text-secondary mb-0">Conoce nuestras promociones y escríbenos para solicitar información.</p>
                @if($officeSettings->whatsapp_digits)
                    <p class="small text-secondary mt-2 mb-0">Al continuar, se abrirá WhatsApp para solicitar información de la oferta.</p>
                @endif
            </div>
            @if($officeSettings->whatsapp_digits)
                @php
                    $generalMessage = rawurlencode('Hola, quisiera información sobre las ofertas de ' . $officeSettings->clinic_name . '.');
                @endphp
                <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $generalMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-success" onclick="return confirm('Se abrirá WhatsApp para solicitar información sobre las ofertas. ¿Deseas continuar?')">
                    <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Consultar por WhatsApp
                </a>
            @else
                <button type="button" class="btn btn-success" onclick="alert('El WhatsApp del consultorio aún no está configurado. Contacta al administrador.')">
                    <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Consultar por WhatsApp
                </button>
            @endif
        </div>

        @if($ofertas->isNotEmpty())
            <div class="row g-3">
                @foreach($ofertas as $oferta)
                    @php
                        $offerMessage = rawurlencode(
                            'Hola, quisiera información sobre la oferta "' . $oferta->titulo . '" de ' . $officeSettings->clinic_name . '. ¿Podrían compartirme los detalles y su disponibilidad?'
                        );
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <article class="card offer-card shadow-sm h-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge rounded-pill text-bg-light text-dentissa align-self-start mb-3">{{ $oferta->tag ?? 'Promoción' }}</span>
                                <h2 class="h5 fw-bold mb-2">{{ $oferta->titulo }}</h2>
                                @if($oferta->precio)
                                    <div class="h3 fw-bold text-dentissa mb-3">${{ number_format((float) $oferta->precio, 0) }}</div>
                                @else
                                    <div class="h3 fw-bold text-dentissa mb-3">Consulta</div>
                                @endif
                                <p class="offer-description mb-4">{{ $oferta->descripcion }}</p>
                                @if($officeSettings->whatsapp_digits)
                                    <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $offerMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success mt-auto" onclick="return confirm('Se abrirá WhatsApp para solicitar más información sobre esta oferta. ¿Deseas continuar?')">
                                        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Solicitar más información
                                    </a>
                                @else
                                    <button type="button" class="btn btn-outline-success mt-auto" onclick="alert('El WhatsApp del consultorio aún no está configurado. Contacta al administrador.')">
                                        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Solicitar más información
                                    </button>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-tag text-dentissa fs-2" aria-hidden="true"></i>
                    <h2 class="h5 fw-bold mt-3">No hay ofertas activas</h2>
                    <p class="text-secondary mb-0">Pronto publicaremos nuevas promociones.</p>
                </div>
            </div>
        @endif

        <section class="card border-0 shadow-sm mt-4" aria-labelledby="office-location-title">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="text-secondary small text-uppercase fw-bold">Visítanos</span>
                    <h2 id="office-location-title" class="h5 fw-bold mt-1 mb-1">{{ $officeSettings->clinic_name }}</h2>
                    <p class="text-secondary mb-0">{{ $officeSettings->clinic_address ?: 'Ubicación próximamente disponible.' }}</p>
                </div>
                @if($officeSettings->map_link)
                    <a href="{{ $officeSettings->map_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">
                        <i class="bi bi-geo-alt me-2" aria-hidden="true"></i>Cómo llegar
                    </a>
                @endif
            </div>
            @if($officeSettings->map_embed_link)
                <iframe
                    src="{{ $officeSettings->map_embed_link }}"
                    title="Mapa de ubicación de {{ $officeSettings->clinic_name }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                    style="display: block; width: 100%; height: 360px; border: 0; border-radius: 0 0 6px 6px;">
                </iframe>
            @endif
        </section>
    </main>
</body>
</html>
